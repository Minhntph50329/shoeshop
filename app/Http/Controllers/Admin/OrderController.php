<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Danh sách đơn hàng
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'payment', 'statuses' => function ($q) {
            $q->where('order_order_status.is_current', true);
        }])->latest();

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('fullname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($statusId = $request->input('status')) {
            $query->whereHas('statuses', function ($q) use ($statusId) {
                $q->where('order_statuses.id', $statusId)
                  ->where('order_order_status.is_current', true);
            });
        }

        // Filter by date range
        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $orders   = $query->paginate(15)->withQueryString();
        $statuses = OrderStatus::all();

        // Stats
        $stats = [
            'total'   => Order::count(),
            'pending' => $this->countByStatus(1),
            'shipping'=> $this->countByStatus(3),
            'done'    => $this->countByStatus(10),
            'cancelled'=> $this->countByStatus(8),
        ];

        return view('admin.orders.index', compact('orders', 'statuses', 'stats'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with([
            'user',
            'payment',
            'coupon',
            'items.product.images',
            'items.productVariant',
            'statuses',
            'statusHistories.status',
            'statusHistories.modifier',
            'paymentLogs',
        ])->findOrFail($id);

        $currentStatus = $order->getCurrentStatus();
        $allowedNextStatusIds = $currentStatus ? (Order::getTransitionMap()[$currentStatus->id] ?? []) : [1];
        $allStatuses = OrderStatus::whereIn('id', array_merge($currentStatus ? [$currentStatus->id] : [], $allowedNextStatusIds))->get();
        $statusColors  = Order::statusColors();

        return view('admin.orders.show', compact('order', 'currentStatus', 'allStatuses', 'statusColors'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,id',
            'note'      => 'nullable|string|max:500',
        ]);

        $order    = Order::findOrFail($id);
        $statusId = (int) $request->input('status_id');
        $note     = $request->input('note');
        $userId   = auth()->id();

        // Validate state machine transition
        if (!$order->canTransitionTo($statusId)) {
            return back()->with('error', 'Chuyển đổi trạng thái không hợp lệ theo quy tắc State Machine.');
        }

        DB::transaction(function () use ($order, $statusId, $note, $userId) {
            // Update pivot: set all is_current = false
            DB::table('order_order_status')
                ->where('order_id', $order->id)
                ->update(['is_current' => false]);

            // Update history: set all is_current = false
            OrderStatusHistory::where('order_id', $order->id)
                ->update(['is_current' => false]);

            // Upsert pivot record
            $exists = DB::table('order_order_status')
                ->where('order_id', $order->id)
                ->where('order_status_id', $statusId)
                ->exists();

            if ($exists) {
                DB::table('order_order_status')
                    ->where('order_id', $order->id)
                    ->where('order_status_id', $statusId)
                    ->update([
                        'is_current'  => true,
                        'modified_by' => $userId,
                        'note'        => $note,
                        'updated_at'  => now(),
                    ]);
            } else {
                DB::table('order_order_status')->insert([
                    'order_id'        => $order->id,
                    'order_status_id' => $statusId,
                    'modified_by'     => $userId,
                    'note'            => $note,
                    'is_current'      => true,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            // Insert history record
            OrderStatusHistory::create([
                'order_id'        => $order->id,
                'order_status_id' => $statusId,
                'modifier_id'     => $userId,
                'note'            => $note,
                'is_current'      => true,
            ]);

            // Handle special statuses
            if ($statusId == 8) { // Đã hủy
                $order->update(['cancelled_at' => now()]);

                // Hoàn lại số lượng tồn kho
                foreach ($order->items as $item) {
                    if ($item->product_variant_id) {
                        $variant = \App\Models\ProductVariant::find($item->product_variant_id);
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
        });

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    private function countByStatus(int $statusId): int
    {
        return Order::whereHas('statuses', function ($q) use ($statusId) {
            $q->where('order_statuses.id', $statusId)
              ->where('order_order_status.is_current', true);
        })->count();
    }
}