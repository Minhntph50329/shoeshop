<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Refund;
use App\Models\RefundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RefundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the refund request form
     */
    public function create($orderId)
    {
        $order = Order::with(['items.product.images', 'items.productVariant'])
            ->where('user_id', auth()->id())
            ->findOrFail($orderId);

        $currentStatus = $order->getCurrentStatus();
        $currentStatusId = $currentStatus ? $currentStatus->id : null;

        // Check if order is eligible for refund (must be in status 4: Giao hàng thành công or 10: Nhận hàng thành công)
        if (!in_array($currentStatusId, [4, 10])) {
            return redirect()->route('client.orders.show', $orderId)
                ->with('error', 'Chỉ những đơn hàng đã giao hàng thành công hoặc đã nhận hàng mới có thể yêu cầu trả hàng.');
        }

        // Check if a refund has already been requested
        $existingRefund = Refund::where('order_id', $order->id)
            ->whereIn('status', [Refund::STATUS_PENDING, Refund::STATUS_APPROVED, Refund::STATUS_COMPLETED])
            ->first();

        if ($existingRefund) {
            return redirect()->route('client.orders.show', $orderId)
                ->with('error', 'Đơn hàng này đã có yêu cầu trả hàng / hoàn tiền đang được xử lý hoặc đã hoàn thành.');
        }

        return view('client.account.refund', compact('order'));
    }

    /**
     * Store the refund request
     */
    public function store(Request $request, $orderId)
    {
        $order = Order::with('items')->where('user_id', auth()->id())->findOrFail($orderId);
        $currentStatus = $order->getCurrentStatus();
        $currentStatusId = $currentStatus ? $currentStatus->id : null;

        // Verify status eligibility
        if (!in_array($currentStatusId, [4, 10])) {
            return redirect()->route('client.orders.show', $orderId)
                ->with('error', 'Đơn hàng không đủ điều kiện trả hàng.');
        }

        // Double check existing requests
        $existingRefund = Refund::where('order_id', $order->id)
            ->whereIn('status', [Refund::STATUS_PENDING, Refund::STATUS_APPROVED, Refund::STATUS_COMPLETED])
            ->first();

        if ($existingRefund) {
            return redirect()->route('client.orders.show', $orderId)
                ->with('error', 'Đơn hàng này đã có yêu cầu trả hàng / hoàn tiền.');
        }

        $request->validate([
            'bank_account'   => 'required|string|max:100',
            'user_bank_name' => 'required|string|max:255',
            'bank_name'      => 'required|string|max:100',
            'reason'         => 'required|string|max:1000',
            'reason_image'   => 'nullable|image|max:2048', // max 2MB
            'items'          => 'required|array',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        // Filter items that have quantity > 0
        $selectedItems = array_filter($request->items, function ($itemData) {
            return isset($itemData['quantity']) && $itemData['quantity'] > 0;
        });

        if (empty($selectedItems)) {
            return back()->withInput()->with('error', 'Vui lòng chọn ít nhất 1 sản phẩm để trả.');
        }

        // Process image upload
        $imagePath = null;
        if ($request->hasFile('reason_image')) {
            $imagePath = $request->file('reason_image')->store('refunds', 'public');
        }

        DB::transaction(function () use ($order, $request, $selectedItems, $imagePath) {
            $totalAmount = 0;
            $refundItemRecords = [];

            foreach ($selectedItems as $itemId => $itemData) {
                $orderItem = $order->items->firstWhere('id', $itemId);
                if (!$orderItem) {
                    throw new \Exception('Sản phẩm yêu cầu trả không thuộc đơn hàng.');
                }

                $returnQty = (int) $itemData['quantity'];
                if ($returnQty > $orderItem->quantity) {
                    throw new \Exception("Số lượng trả sản phẩm {$orderItem->name} không được vượt quá số lượng đã mua ({$orderItem->quantity}).");
                }

                $itemPrice = $orderItem->effective_price;
                $lineTotal = $itemPrice * $returnQty;
                $totalAmount += $lineTotal;

                $refundItemRecords[] = [
                    'product_id'       => $orderItem->product_id,
                    'variant_id'       => $orderItem->product_variant_id,
                    'name'             => $orderItem->name,
                    'name_variant'     => $orderItem->name_variant,
                    'quantity'         => $returnQty,
                    'price'            => $orderItem->price,
                    'price_variant'    => $orderItem->price_variant,
                    'quantity_variant' => $orderItem->quantity_variant,
                ];
            }

            // Create refund
            $refund = Refund::create([
                'order_id'       => $order->id,
                'user_id'        => auth()->id(),
                'total_amount'   => $totalAmount,
                'bank_account'   => $request->bank_account,
                'user_bank_name' => $request->user_bank_name,
                'bank_name'      => $request->bank_name,
                'reason'         => $request->reason,
                'reason_image'   => $imagePath,
                'status'         => Refund::STATUS_PENDING,
                'is_send_money'  => false,
            ]);

            // Save refund items
            foreach ($refundItemRecords as $record) {
                $record['refund_id'] = $refund->id;
                RefundItem::create($record);
            }

            // Update order status: transition to status 5 (Chờ trả hàng)
            // 1. Set current status fields in pivot and histories to false
            DB::table('order_order_status')->where('order_id', $order->id)->update(['is_current' => false]);
            OrderStatusHistory::where('order_id', $order->id)->update(['is_current' => false]);

            // 2. Insert new current status 5
            $exists = DB::table('order_order_status')
                ->where('order_id', $order->id)
                ->where('order_status_id', 5)
                ->exists();

            if ($exists) {
                DB::table('order_order_status')
                    ->where('order_id', $order->id)
                    ->where('order_status_id', 5)
                    ->update([
                        'is_current'  => true,
                        'modified_by' => auth()->id(),
                        'note'        => 'Yêu cầu trả hàng / hoàn tiền được tạo',
                        'updated_at'  => now(),
                    ]);
            } else {
                DB::table('order_order_status')->insert([
                    'order_id'        => $order->id,
                    'order_status_id' => 5,
                    'modified_by'     => auth()->id(),
                    'note'            => 'Yêu cầu trả hàng / hoàn tiền được tạo',
                    'is_current'      => true,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            // 3. Insert status history record
            OrderStatusHistory::create([
                'order_id'        => $order->id,
                'order_status_id' => 5,
                'modifier_id'     => auth()->id(),
                'note'            => 'Yêu cầu trả hàng / hoàn tiền được tạo',
                'is_current'      => true,
            ]);
        });

        return redirect()->route('client.orders.show', $orderId)
            ->with('success', 'Yêu cầu trả hàng / hoàn tiền đã được gửi thành công. Vui lòng chờ phản hồi từ quản trị viên.');
    }
}
