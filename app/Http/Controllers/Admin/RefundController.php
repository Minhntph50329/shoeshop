<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Refund;
use App\Mail\RefundSuccessfulMail;
use App\Http\Requests\Admin\Refund\HandleRefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    /**
     * Danh sách các yêu cầu trả hàng
     */
    public function index(Request $request)
    {
        $query = Refund::with(['order', 'user'])->latest();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('order', function ($o) use ($search) {
                    $o->where('code', 'like', "%{$search}%");
                })->orWhereHas('user', function ($u) use ($search) {
                    $u->where('fullname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $refunds = $query->paginate(10)->withQueryString();
        $statuses = Refund::statusLabels();
        $statusColors = Refund::statusColors();

        // Count for stats
        $stats = [
            'total'     => Refund::count(),
            'pending'   => Refund::where('status', Refund::STATUS_PENDING)->count(),
            'approved'  => Refund::where('status', Refund::STATUS_APPROVED)->count(),
            'completed' => Refund::where('status', Refund::STATUS_COMPLETED)->count(),
            'rejected'  => Refund::where('status', Refund::STATUS_REJECTED)->count(),
        ];

        return view('admin.refunds.index', compact('refunds', 'statuses', 'statusColors', 'stats'));
    }

    /**
     * Xem chi tiết yêu cầu trả hàng
     */
    public function show($id)
    {
        $refund = Refund::with([
            'order.items',
            'user',
            'items.product.images',
            'items.productVariant',
        ])->findOrFail($id);

        $statusColors = Refund::statusColors();
        $statusLabels = Refund::statusLabels();

        return view('admin.refunds.show', compact('refund', 'statusColors', 'statusLabels'));
    }

    /**
     * Xử lý trạng thái yêu cầu trả hàng theo State Machine
     */
    public function handleAction(HandleRefundRequest $request, $id)
    {
        $validated = $request->validated();

        $refund = Refund::findOrFail($id);
        $order  = Order::findOrFail($refund->order_id);
        $action = $request->input('action');
        $aadminReason = $request->input('aadmin_reason');
        $userId = auth()->id();

        // Map action to next status in State Machine
        $nextStatus = null;
        if ($action === 'approve') {
            $nextStatus = Refund::STATUS_APPROVED;
        } elseif ($action === 'reject') {
            $nextStatus = Refund::STATUS_REJECTED;
            if (empty($aadminReason)) {
                return back()->with('error', 'Vui lòng cung cấp lý do từ chối yêu cầu trả hàng.');
            }
        } elseif ($action === 'complete') {
            $nextStatus = Refund::STATUS_COMPLETED;
        }

        // Validate state transition
        if (!$refund->canTransitionTo($nextStatus)) {
            return back()->with('error', 'Chuyển đổi trạng thái trả hàng không hợp lệ theo State Machine.');
        }

        // Handle uploaded transaction proof image
        $proofImagePath = null;
        if ($request->hasFile('img_refunded_money')) {
            $proofImagePath = $request->file('img_refunded_money')->store('refunds', 'public');
        }

        DB::transaction(function () use ($refund, $order, $nextStatus, $aadminReason, $userId, $proofImagePath) {
            // Update refund status
            $refund->status = $nextStatus;
            if ($aadminReason) {
                $refund->aadmin_reason = $aadminReason;
            }

            // Handle transition-specific logic
            if ($nextStatus === Refund::STATUS_APPROVED) {
                // Keep order status as 5 (Chờ trả hàng), but add a status history note
                $this->updateOrderStatus($order->id, 5, $userId, 'Quản trị viên đã phê duyệt yêu cầu trả hàng. Khách hàng hãy gửi lại sản phẩm.');

            } elseif ($nextStatus === Refund::STATUS_REJECTED) {
                // Transition order back to "Giao hàng thành công" (4)
                $this->updateOrderStatus($order->id, 4, $userId, 'Quản trị viên đã từ chối yêu cầu trả hàng với lý do: ' . $aadminReason);

            } elseif ($nextStatus === Refund::STATUS_COMPLETED) {
                // Mark money sent as true
                $refund->is_send_money = true;
                
                if ($proofImagePath) {
                    $order->img_refunded_money = $proofImagePath;
                    $order->save();
                }

                // Transition order to 7 (Hoàn tiền)
                $this->updateOrderStatus($order->id, 7, $userId, 'Đã nhận sản phẩm trả lại và thực hiện hoàn tiền thành công.');

                // Restock the returned items
                foreach ($refund->items as $item) {
                    if ($item->variant_id) {
                        $variant = \App\Models\ProductVariant::find($item->variant_id);
                        if ($variant) {
                            $variant->increment('stock', $item->quantity);
                        }
                    } else {
                        $product = \App\Models\Product::find($item->product_id);
                        if ($product) {
                            $product->increment('stock', $item->quantity);
                        }
                    }
                }
            }

            $refund->save();

            // Send Email if completed successfully
            if ($nextStatus === Refund::STATUS_COMPLETED) {
                try {
                    $recipientEmail = $order->email ?? ($refund->user->email ?? null);
                    if ($recipientEmail) {
                        Mail::to($recipientEmail)->send(new RefundSuccessfulMail($refund));
                    }
                } catch (\Exception $e) {
                    // Log mail error but do not break transaction
                    logger()->error('Không thể gửi email hoàn tiền thành công: ' . $e->getMessage());
                }
            }
        });

        return redirect()->route('admin.refunds.show', $id)
            ->with('success', 'Cập nhật trạng thái yêu cầu trả hàng thành công!');
    }

    /**
     * Helper to transition order status
     */
    private function updateOrderStatus($orderId, $statusId, $userId, $note)
    {
        // 1. Set current status fields in pivot and histories to false
        DB::table('order_order_status')->where('order_id', $orderId)->update(['is_current' => false]);
        OrderStatusHistory::where('order_id', $orderId)->update(['is_current' => false]);

        // 2. Upsert status pivot record
        $exists = DB::table('order_order_status')
            ->where('order_id', $orderId)
            ->where('order_status_id', $statusId)
            ->exists();

        if ($exists) {
            DB::table('order_order_status')
                ->where('order_id', $orderId)
                ->where('order_status_id', $statusId)
                ->update([
                    'is_current'  => true,
                    'modified_by' => $userId,
                    'note'        => $note,
                    'updated_at'  => now(),
                ]);
        } else {
            DB::table('order_order_status')->insert([
                'order_id'        => $orderId,
                'order_status_id' => $statusId,
                'modified_by'     => $userId,
                'note'            => $note,
                'is_current'      => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // 3. Insert history record
        OrderStatusHistory::create([
            'order_id'        => $orderId,
            'order_status_id' => $statusId,
            'modifier_id'     => $userId,
            'note'            => $note,
            'is_current'      => true,
        ]);
    }
}
