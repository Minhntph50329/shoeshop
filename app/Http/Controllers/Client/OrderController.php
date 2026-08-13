<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Danh sách đơn hàng của client
     */
    public function index(Request $request)
    {
        $query = Order::with(['items.product.images', 'items.productVariant', 'statuses' => function ($q) {
            $q->where('order_order_status.is_current', true);
        }])
        ->where('user_id', auth()->id())
        ->latest();

        // Filter by status tab
        if ($statusId = $request->input('status')) {
            $query->whereHas('statuses', function ($q) use ($statusId) {
                $q->where('order_statuses.id', $statusId)
                  ->where('order_order_status.is_current', true);
            });
        }

        // Search by code
        if ($search = $request->input('search')) {
            $query->where('code', 'like', "%{$search}%");
        }

        $orders   = $query->paginate(8)->withQueryString();
        $statuses = OrderStatus::all();

        // Count per status for tabs
        $counts = [];
        foreach ($statuses as $status) {
            $counts[$status->id] = Order::where('user_id', auth()->id())
                ->whereHas('statuses', function ($q) use ($status) {
                    $q->where('order_statuses.id', $status->id)
                      ->where('order_order_status.is_current', true);
                })->count();
        }
        $counts['all'] = Order::where('user_id', auth()->id())->count();

        $statusColors = Order::statusColors();

        return view('client.account.orders', compact('orders', 'statuses', 'counts', 'statusColors'));
    }

    /**
     * Chi tiết đơn hàng của client
     */
    public function show($id)
    {
        $order = Order::with([
            'items.product.images',
            'items.productVariant',
            'items.review',
            'payment',
            'coupon',
            'statusHistories.status',
            'statuses',
            'paymentLogs',
            'refunds.items',
        ])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        $currentStatus = $order->getCurrentStatus();
        $statusColors  = Order::statusColors();

        return view('client.account.order-detail', compact('order', 'currentStatus', 'statusColors'));
    }

    /**
     * Client hủy đơn hàng
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_note'   => 'nullable|string|max:500',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Only allow cancel if status is "Chờ xác nhận" (id=1) / transition to Đã hủy (8) is valid
        if (!$order->canTransitionTo(8)) {
            return back()->with('error', 'Chỉ có thể hủy đơn hàng ở trạng thái Chờ xác nhận.');
        }

        DB::transaction(function () use ($order, $request) {
            // Update order
            $order->update([
                'cancel_reason' => $request->cancel_reason,
                'cancel_note'   => $request->cancel_note,
                'cancelled_at'  => now(),
            ]);

            // Set all is_current = false
            DB::table('order_order_status')->where('order_id', $order->id)->update(['is_current' => false]);
            OrderStatusHistory::where('order_id', $order->id)->update(['is_current' => false]);

            // Set status to Đã hủy (8)
            $exists = DB::table('order_order_status')->where('order_id', $order->id)->where('order_status_id', 8)->exists();
            if ($exists) {
                DB::table('order_order_status')->where('order_id', $order->id)->where('order_status_id', 8)
                    ->update(['is_current' => true, 'modified_by' => auth()->id(), 'updated_at' => now()]);
            } else {
                DB::table('order_order_status')->insert([
                    'order_id'        => $order->id,
                    'order_status_id' => 8,
                    'modified_by'     => auth()->id(),
                    'note'            => $request->cancel_reason,
                    'is_current'      => true,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            OrderStatusHistory::create([
                'order_id'        => $order->id,
                'order_status_id' => 8,
                'modifier_id'     => auth()->id(),
                'note'            => $request->cancel_reason,
                'is_current'      => true,
            ]);

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
        });

        return redirect()->route('client.orders.show', $id)
            ->with('success', 'Đơn hàng đã được hủy thành công.');
    }

    /**
     * Client xác nhận đã nhận hàng
     */
    public function confirm($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        // Only allow confirm if status is "Giao hàng thành công" (id=4) / transition to Nhận hàng thành công (10) is valid
        if (!$order->canTransitionTo(10)) {
            return back()->with('error', 'Chỉ có thể xác nhận khi đơn hàng đã được giao thành công.');
        }

        DB::transaction(function () use ($order) {
            DB::table('order_order_status')->where('order_id', $order->id)->update(['is_current' => false]);
            OrderStatusHistory::where('order_id', $order->id)->update(['is_current' => false]);

            $exists = DB::table('order_order_status')->where('order_id', $order->id)->where('order_status_id', 10)->exists();
            if ($exists) {
                DB::table('order_order_status')->where('order_id', $order->id)->where('order_status_id', 10)
                    ->update(['is_current' => true, 'modified_by' => auth()->id(), 'updated_at' => now()]);
            } else {
                DB::table('order_order_status')->insert([
                    'order_id'        => $order->id,
                    'order_status_id' => 10,
                    'modified_by'     => auth()->id(),
                    'note'            => 'Khách hàng xác nhận đã nhận hàng',
                    'is_current'      => true,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            OrderStatusHistory::create([
                'order_id'        => $order->id,
                'order_status_id' => 10,
                'modifier_id'     => auth()->id(),
                'note'            => 'Khách hàng xác nhận đã nhận hàng',
                'is_current'      => true,
            ]);
        });

        return redirect()->route('client.orders.show', $id)
            ->with('success', 'Cảm ơn bạn đã xác nhận nhận hàng!');
    }

    /**
     * Cập nhật địa chỉ nhận hàng (chỉ khi ở trạng thái Chờ xác nhận)
     */
    public function updateAddress(Request $request, $id)
    {
        $request->validate([
            'fullname'     => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email'        => 'required|email|max:255',
            'address'      => 'required|string|max:500',
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        $currentStatus = $order->getCurrentStatus();

        if (!$currentStatus || $currentStatus->id !== 1) {
            return back()->with('error', 'Chỉ có thể thay đổi địa chỉ khi đơn hàng ở trạng thái Chờ xác nhận.');
        }

        $order->update([
            'fullname'     => $request->fullname,
            'phone_number' => $request->phone_number,
            'email'        => $request->email,
            'address'      => $request->address,
        ]);

        // Ghi lại lịch sử cập nhật
        OrderStatusHistory::create([
            'order_id'        => $order->id,
            'order_status_id' => 1,
            'modifier_id'     => auth()->id(),
            'note'            => 'Khách hàng thay đổi địa chỉ nhận hàng',
            'is_current'      => true,
        ]);

        return redirect()->route('client.orders.show', $id)
            ->with('success', 'Cập nhật địa chỉ nhận hàng thành công.');
    }
}
