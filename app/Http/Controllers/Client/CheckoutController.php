<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\PaymentLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Trang checkout
     */
    public function index()
    {
        $user = auth()->user();

        $selectedItemIds = request()->query('items') ? explode(',', request()->query('items')) : [];

        // Load cart
        $cart = Cart::with(['items' => function($q) use ($selectedItemIds) {
            if (!empty($selectedItemIds)) {
                $q->whereIn('id', $selectedItemIds);
            }
        }, 'items.product.images', 'items.variant.attributeValues.attribute'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $payments     = Payment::where('is_active', true)->get();
        $allAddresses = $user->addresses()->latest()->get();
        $addresses    = $allAddresses->where('is_default', true)->first()
                     ?? $allAddresses->first();

        return view('client.checkout.index', compact('cart', 'payments', 'addresses', 'allAddresses', 'user'));
    }

    /**
     * Trang thêm địa chỉ mới từ Checkout
     */
    public function createAddress()
    {
        return view('client.checkout.create-address');
    }

    /**
     * Lưu địa chỉ mới từ Checkout
     */
    public function storeAddress(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'fullname'     => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'province'     => 'required|string|max:255',
            'district'     => 'required|string|max:255',
            'ward'         => 'required|string|max:255',
            'street'       => 'required|string|max:255',
            'address'      => 'required|string|max:500',
            'address_type' => 'required|in:home,office,other',
            'is_default'   => 'nullable|boolean',
        ], [
            'fullname.required'     => 'Vui lòng nhập họ và tên người nhận.',
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'province.required'     => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district.required'     => 'Vui lòng chọn Quận/Huyện.',
            'ward.required'         => 'Vui lòng chọn Phường/Xã.',
            'street.required'       => 'Vui lòng nhập tên đường, tòa nhà...',
            'address.required'      => 'Vui lòng nhập địa chỉ cụ thể.',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $validated['user_id'] = $user->id;

        \App\Models\UserAddress::create($validated);

        return redirect()->route('checkout')->with('success', 'Thêm địa chỉ giao hàng thành công!');
    }

    /**
     * Đặt hàng
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'fullname'    => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'email'       => 'required|email|max:255',
            'address'     => 'required|string|max:500',
            'payment_id'  => 'required|exists:payments,id',
            'shipping_type'=> 'required|in:standard,express',
            'note'        => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string',
        ]);

        $selectedItemIds = $request->query('items') ? explode(',', $request->query('items')) : [];
        $cart = Cart::with(['items' => function($q) use ($selectedItemIds) {
            if (!empty($selectedItemIds)) {
                $q->whereIn('id', $selectedItemIds);
            }
        }, 'items.product', 'items.variant'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Giỏ hàng trống.');
        }

        // Calculate totals
        $subtotal      = 0;
        $discountAmount = 0;
        $shippingFee   = $request->shipping_type === 'express' ? 50000 : 30000;
        $couponId      = null;

        foreach ($cart->items as $item) {
            $price     = $item->price_at_time ?? ($item->variant ? (float) $item->variant->price : (float) $item->product->price);
            $subtotal += $price * $item->quantity;
        }

        // Apply coupon
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', $request->coupon_code)->first();
            if ($coupon && $coupon->isValid()) {
                if ($coupon->discount_type === 'percent') {
                    $discountAmount = $subtotal * ($coupon->discount_value / 100);
                } else {
                    $discountAmount = min($coupon->discount_value, $subtotal);
                }
                $couponId = $coupon->id;
            }
        }

        DB::beginTransaction();
        try {
            // Create order
            $order = Order::create([
                'code'            => Order::generateCode(),
                'user_id'         => $user->id,
                'payment_id'      => $request->payment_id,
                'phone_number'    => $request->phone,
                'email'           => $request->email,
                'fullname'        => $request->fullname,
                'address'         => $request->address,
                'note'            => $request->note,
                'total_amount'    => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_type'   => $request->shipping_type,
                'shipping_fee'    => $shippingFee,
                'is_paid'         => false,
                'coupon_id'       => $couponId,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                $product = $item->product;
                $variant = $item->variant;
                $price   = $item->price_at_time ?? ($variant ? (float) $variant->price : (float) $product->price);

                OrderItem::create([
                    'order_id'            => $order->id,
                    'product_id'          => $product->id,
                    'product_variant_id'  => $variant?->id,
                    'name'                => $product->name,
                    'price'               => (float) $product->price,
                    'old_price'           => null,
                    'quantity'            => $item->quantity,
                    'name_variant'        => $variant ? ($variant->sku ?? null) : null,
                    'attributes_variant'  => $variant ? $this->getVariantAttributes($variant) : null,
                    'price_variant'       => $variant ? (float) $variant->price : null,
                    'quantity_variant'    => $variant ? $variant->stock : null,
                ]);

                // Trừ số lượng tồn kho
                if ($variant) {
                    $variant->decrement('stock', $item->quantity);
                } else {
                    $product->decrement('stock', $item->quantity);
                }
            }

            // Set initial status: Chờ xác nhận (1)
            DB::table('order_order_status')->insert([
                'order_id'        => $order->id,
                'order_status_id' => 1,
                'modified_by'     => $user->id,
                'note'            => 'Đơn hàng mới được đặt',
                'is_current'      => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
            OrderStatusHistory::create([
                'order_id'        => $order->id,
                'order_status_id' => 1,
                'modifier_id'     => $user->id,
                'note'            => 'Đơn hàng mới được đặt',
                'is_current'      => true,
            ]);

            // Increment coupon usage
            if ($couponId) {
                Coupon::where('id', $couponId)->increment('usage_count');
            }

            // Mark cart as ordered if all items are ordered, else delete only the ordered items
            $totalCartItemsCount = DB::table('cart_items')->where('cart_id', $cart->id)->count();
            $orderedItemsCount = $cart->items->count();

            if ($totalCartItemsCount === $orderedItemsCount) {
                $cart->update(['status' => 'ordered']);
            } else {
                DB::table('cart_items')
                    ->where('cart_id', $cart->id)
                    ->whereIn('id', $cart->items->pluck('id'))
                    ->delete();
            }

            DB::commit();

            $payment = Payment::find($request->payment_id);

            // Redirect based on payment type
            if ($payment && stripos($payment->name, 'vnpay') !== false) {
                return $this->redirectToVnpay($order);
            }

            return redirect()->route('checkout.success', ['code' => $order->code])
                ->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Đã xảy ra lỗi khi đặt hàng: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to VNPay (mock implementation)
     */
    private function redirectToVnpay(Order $order)
    {
        // Mock VNPay: redirect to a local return URL with simulated success
        $params = [
            'order_id'  => $order->id,
            'vnp_TxnRef'=> $order->code,
            'vnp_Amount'=> $order->grand_total * 100,
            'simulate'  => 'success',
        ];
        return redirect()->route('checkout.vnpay.return', $params);
    }

    /**
     * VNPay Return Handler
     */
    public function vnpayReturn(Request $request)
    {
        $orderId      = $request->input('order_id');
        $txnRef       = $request->input('vnp_TxnRef');
        $responseCode = $request->input('vnp_ResponseCode', '00'); // 00 = success in real VNPay
        $simulate     = $request->input('simulate', 'success');

        // For mock: simulate=success means payment ok
        if ($simulate === 'success') $responseCode = '00';

        $order = Order::findOrFail($orderId);

        // Log payment
        PaymentLog::create([
            'order_id'       => $order->id,
            'txn_ref'        => $txnRef,
            'response_code'  => $responseCode,
            'transaction_no' => $request->input('vnp_TransactionNo', 'MOCK-' . time()),
            'amount'         => $order->grand_total,
            'bank_code'      => $request->input('vnp_BankCode', 'VNPAY'),
            'response_data'  => json_encode($request->all()),
        ]);

        if ($responseCode === '00') {
            $order->update(['is_paid' => true]);
            return redirect()->route('checkout.success', ['code' => $order->code])
                ->with('success', 'Thanh toán VNPay thành công!');
        }

        return redirect()->route('my-orders')
            ->with('error', 'Thanh toán thất bại (Mã lỗi: ' . $responseCode . '). Vui lòng thử lại.');
    }

    /**
     * Trang thành công
     */
    public function success(Request $request)
    {
        $code  = $request->input('code');
        $order = Order::with(['items.product.images', 'payment'])
            ->where('code', $code)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('client.checkout.success', compact('order'));
    }

    private function getVariantAttributes($variant): ?string
    {
        if (!$variant) return null;
        // Try to get attribute values from the variant's pivot
        try {
            $attrs = $variant->attributeValues()->get()->map(fn($v) => $v->value)->implode(', ');
            return $attrs ?: null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * AJAX: Apply coupon code - returns JSON with discount amount
     */
    public function applyCoupon(Request $request)
    {
        $code   = $request->input('code');
        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá không tồn tại.']);
        }

        if (!$coupon->isValid()) {
            return response()->json(['success' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực.']);
        }

        // Calculate discount based on current cart
        $user    = auth()->user();
        $cart    = \App\Models\Cart::with(['items.product', 'items.variant'])
            ->where('user_id', $user->id)->where('status', 'active')->first();

        $subtotal = 0;
        if ($cart) {
            foreach ($cart->items as $item) {
                $price     = $item->price_at_time ?? ($item->variant ? (float) $item->variant->price : (float) $item->product->price);
                $subtotal += $price * $item->quantity;
            }
        }

        if ($coupon->discount_type === 'percent') {
            $discount = $subtotal * ($coupon->discount_value / 100);
            $label    = $coupon->discount_value . '%';
        } else {
            $discount = min((float) $coupon->discount_value, $subtotal);
            $label    = number_format($coupon->discount_value, 0, ',', '.') . 'đ';
        }

        return response()->json([
            'success'  => true,
            'discount' => $discount,
            'message'  => "Áp dụng thành công! Giảm {$label}",
        ]);
    }
}

