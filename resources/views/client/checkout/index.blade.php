@extends('layouts.app')

@section('title', 'Thanh toán - Veloce')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('cart') }}" class="hover:text-indigo-600 transition">Giỏ hàng</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Thanh toán</span>
    </nav>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0 text-emerald-500"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-4 h-4"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('checkout.store', ['items' => request()->query('items')]) }}" method="POST" id="checkoutForm">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

            {{-- LEFT: Form --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Delivery Info --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">1</div>
                            <h3 class="font-bold text-sm text-slate-800">Thông tin nhận hàng</h3>
                        </div>
                        <a href="{{ route('checkout.address.create') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm địa chỉ mới
                        </a>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($allAddresses->isNotEmpty())
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Chọn từ địa chỉ đã lưu</label>
                            <select id="savedAddressSelect" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                                <option value="" data-fullname="{{ $user->fullname }}" data-phone="{{ $user->phone_number }}" data-address="">-- Chọn địa chỉ khác hoặc tự nhập --</option>
                                @foreach($allAddresses as $addr)
                                    <option value="{{ $addr->id }}" 
                                        data-fullname="{{ $addr->fullname }}" 
                                        data-phone="{{ $addr->phone_number }}" 
                                        data-address="{{ $addr->full_address }}"
                                        {{ $addresses && $addresses->id == $addr->id ? 'selected' : '' }}>
                                        {{ $addr->fullname }} ({{ $addr->phone_number }}) - {{ $addr->full_address }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Họ và tên <span class="text-red-500">*</span></label>
                                <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" required
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                    placeholder="Nguyễn Văn A">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1.5">Số điện thoại <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone_number) }}" required
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                    placeholder="0901 234 567">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                placeholder="example@email.com">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Địa chỉ giao hàng <span class="text-red-500">*</span></label>
                            <input type="text" name="address" value="{{ old('address', $addresses?->full_address) }}" required
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ghi chú đơn hàng</label>
                            <textarea name="note" rows="2" placeholder="Ghi chú cho người giao hàng (nếu có)..."
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">2</div>
                        <h3 class="font-bold text-sm text-slate-800">Phương thức vận chuyển</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <label class="flex items-center gap-4 p-4 border-2 border-indigo-500 rounded-xl cursor-pointer bg-indigo-50/50 transition hover:border-indigo-600 shipping-option" data-fee="30000">
                            <input type="radio" name="shipping_type" value="standard" checked class="text-indigo-600" onchange="updateShipping(this)">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-sm text-slate-800">Tiêu chuẩn</p>
                                        <p class="text-xs text-slate-500">3-5 ngày làm việc</p>
                                    </div>
                                    <span class="font-bold text-sm text-indigo-600">30.000đ</span>
                                </div>
                            </div>
                        </label>
                        <label class="flex items-center gap-4 p-4 border-2 border-slate-200 rounded-xl cursor-pointer transition hover:border-indigo-400 shipping-option" data-fee="50000">
                            <input type="radio" name="shipping_type" value="express" class="text-indigo-600" onchange="updateShipping(this)">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-bold text-sm text-slate-800">Nhanh</p>
                                        <p class="text-xs text-slate-500">1-2 ngày làm việc</p>
                                    </div>
                                    <span class="font-bold text-sm text-indigo-600">50.000đ</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Payment Methods --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center">3</div>
                        <h3 class="font-bold text-sm text-slate-800">Phương thức thanh toán</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($payments as $payment)
                        <label class="flex items-center gap-4 p-4 border-2 {{ $loop->first ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-200' }} rounded-xl cursor-pointer transition hover:border-indigo-400 payment-option">
                            <input type="radio" name="payment_id" value="{{ $payment->id }}" {{ $loop->first ? 'checked' : '' }}
                                class="text-indigo-600" onchange="selectPayment(this)">
                            <div class="flex items-center gap-3 flex-1">
                                @if($payment->logo && str_contains(strtolower($payment->name), 'vnpay'))
                                    <div class="w-10 h-7 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-[10px] font-black">VN</span>
                                    </div>
                                @else
                                    <div class="w-10 h-7 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="truck" class="w-4 h-4 text-emerald-600"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-sm text-slate-800">{{ $payment->name }}</p>
                                    @if(str_contains(strtolower($payment->name), 'vnpay'))
                                        <p class="text-xs text-slate-400">Thanh toán an toàn qua VNPay</p>
                                    @else
                                        <p class="text-xs text-slate-400">Trả tiền khi nhận hàng</p>
                                    @endif
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- RIGHT: Order Summary --}}
            <div class="lg:col-span-2">
                <div class="sticky top-24 space-y-4">

                    {{-- Order Summary --}}
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100">
                            <h3 class="font-bold text-sm text-slate-800">Đơn hàng của bạn</h3>
                        </div>

                        {{-- Items --}}
                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-50">
                            @foreach($cart->items as $item)
                            @php
                                $img = null;
                                if ($item->variant && $item->variant->image) {
                                    $img = asset($item->variant->image);
                                } elseif ($item->product && $item->product->image) {
                                    $img = asset($item->product->image);
                                } elseif ($item->product && $item->product->images->first()) {
                                    $img = asset('storage/' . $item->product->images->first()->url);
                                }
                                $price = $item->price_at_time ?? ($item->variant ? $item->variant->price : $item->product->price);
                            @endphp
                            <div class="flex items-center gap-3 px-5 py-3">
                                <div class="relative flex-shrink-0">
                                    <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                        <img src="{{ $img ?? 'https://placehold.co/48x48/f1f5f9/94a3b8?text=?' }}"
                                             alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-indigo-600 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $item->quantity }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 truncate">{{ $item->product->name }}</p>
                                    @if($item->variant)
                                        <div class="flex flex-wrap gap-1 mt-0.5">
                                            <span class="text-[10px] text-slate-400 font-semibold mr-1">SKU: {{ $item->variant->sku }}</span>
                                            @foreach($item->variant->attributeValues as $val)
                                                <span class="text-[10px] text-indigo-600 font-medium bg-indigo-50/50 px-1.5 py-0.5 rounded-md">{{ $val->attribute->name }}: {{ $val->value }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <p class="text-xs font-bold text-slate-800 flex-shrink-0">{{ number_format($price * $item->quantity, 0, ',', '.') }}đ</p>
                            </div>
                            @endforeach
                        </div>

                        {{-- Coupon --}}
                        <div class="px-5 py-3 border-t border-slate-100">
                            <div class="flex gap-2">
                                <input type="text" name="coupon_code" id="couponInput" placeholder="Mã giảm giá..."
                                    class="flex-1 px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                                <button type="button" onclick="applyCoupon()"
                                    class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                                    Áp dụng
                                </button>
                            </div>
                            <p id="couponMsg" class="text-[11px] mt-1 hidden"></p>
                        </div>

                        {{-- Totals --}}
                        @php
                            $subtotal = $cart->items->sum(function($item) {
                                $price = $item->price_at_time ?? ($item->variant ? $item->variant->price : $item->product->price);
                                return $price * $item->quantity;
                            });
                        @endphp
                        <div class="px-5 py-4 bg-slate-50 border-t border-slate-100 space-y-2">
                            <div class="flex justify-between text-xs text-slate-600">
                                <span>Tạm tính</span>
                                <span id="subtotalEl">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="flex justify-between text-xs text-emerald-600" id="discountRow" style="display:none!important">
                                <span>Giảm giá</span>
                                <span id="discountEl">-0đ</span>
                            </div>
                            <div class="flex justify-between text-xs text-slate-600">
                                <span>Phí vận chuyển</span>
                                <span id="shippingEl">30.000đ</span>
                            </div>
                            <div class="flex justify-between font-black text-sm text-slate-800 pt-2 border-t border-slate-200">
                                <span>Tổng cộng</span>
                                <span id="grandTotalEl" class="text-indigo-600">{{ number_format($subtotal + 30000, 0, ',', '.') }}đ</span>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="p-5">
                            <button type="submit"
                                class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black text-sm rounded-xl shadow-lg shadow-indigo-200 transition flex items-center justify-center gap-2">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                                Đặt hàng ngay
                            </button>
                            <p class="text-center text-[11px] text-slate-400 mt-2">
                                <i data-lucide="shield-check" class="w-3 h-3 inline mr-0.5"></i>
                                Giao dịch được mã hóa và bảo mật
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<script>
const SUBTOTAL = {{ $subtotal }};
let shippingFee = 30000;
let discountAmt  = 0;

function formatVND(n) {
    return n.toLocaleString('vi-VN') + 'đ';
}

function recalcTotal() {
    const grand = SUBTOTAL + shippingFee - discountAmt;
    document.getElementById('shippingEl').textContent   = formatVND(shippingFee);
    document.getElementById('grandTotalEl').textContent = formatVND(grand);
}

function updateShipping(radio) {
    shippingFee = parseInt(radio.closest('label').dataset.fee);
    document.querySelectorAll('.shipping-option').forEach(el => {
        el.classList.remove('border-indigo-500', 'bg-indigo-50/50');
        el.classList.add('border-slate-200');
    });
    radio.closest('label').classList.add('border-indigo-500', 'bg-indigo-50/50');
    radio.closest('label').classList.remove('border-slate-200');
    recalcTotal();
}

function selectPayment(radio) {
    document.querySelectorAll('.payment-option').forEach(el => {
        el.classList.remove('border-indigo-500', 'bg-indigo-50/50');
        el.classList.add('border-slate-200');
    });
    radio.closest('label').classList.add('border-indigo-500', 'bg-indigo-50/50');
    radio.closest('label').classList.remove('border-slate-200');
}

async function applyCoupon() {
    const code = document.getElementById('couponInput').value.trim();
    const msg  = document.getElementById('couponMsg');
    if (!code) return;

    msg.className = 'text-[11px] mt-1 text-slate-400';
    msg.textContent = 'Đang kiểm tra...';
    msg.style.display = 'block';

    try {
        const res  = await fetch('/checkout/apply-coupon?code=' + encodeURIComponent(code));
        const data = await res.json();
        if (data.success) {
            discountAmt = data.discount;
            msg.className = 'text-[11px] mt-1 text-emerald-600 font-semibold';
            msg.textContent = '✓ ' + data.message;
            document.getElementById('discountEl').textContent = '-' + formatVND(discountAmt);
            document.getElementById('discountRow').style.removeProperty('display');
        } else {
            discountAmt = 0;
            msg.className = 'text-[11px] mt-1 text-red-500';
            msg.textContent = '✗ ' + data.message;
            document.getElementById('discountRow').style.setProperty('display', 'none', 'important');
        }
        recalcTotal();
    } catch(e) {
        msg.className = 'text-[11px] mt-1 text-red-500';
        msg.textContent = 'Lỗi kết nối. Thử lại sau.';
    }
}

// Auto-fill address on selection change
const addressSelect = document.getElementById('savedAddressSelect');
if (addressSelect) {
    addressSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const fullname = option.getAttribute('data-fullname') || '';
        const phone = option.getAttribute('data-phone') || '';
        const address = option.getAttribute('data-address') || '';

        document.querySelector('input[name="fullname"]').value = fullname;
        document.querySelector('input[name="phone"]').value = phone;
        document.querySelector('input[name="address"]').value = address;
    });
}
</script>
@endsection
