@extends('layouts.app')

@section('title', 'Cổng thanh toán VNPAY - Veloce ShoeShop')

@section('content')
<div class="max-w-4xl mx-auto my-8 px-4">
    <!-- VNPAY Header Mock -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-6">
        <div class="flex items-center gap-2">
            <!-- VNPAY Logo SVG Replica -->
            <div class="flex items-center font-black text-xl tracking-tighter">
                <span class="text-[#005baa]">VN</span><span class="text-[#e02020]">PAY</span>
                <span class="text-xs font-semibold text-slate-400 ml-2 border-l border-slate-200 pl-2">Cổng thanh toán</span>
            </div>
        </div>
        <div class="text-xs text-slate-500 font-medium hidden sm:block">
            Hotline: <span class="text-[#005baa] font-bold">1900 55 55 77</span>
        </div>
    </div>

    <!-- Main Payment Layout (Split Screen giống VNPAY thật) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Cột trái: Thông tin đơn hàng & sản phẩm -->
        <div class="md:col-span-1 bg-slate-50 p-5 rounded-2xl border border-slate-200/60 space-y-4 h-fit">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-200 pb-3 uppercase tracking-wider">Thông tin đơn hàng</h3>
            
            <div class="space-y-3 text-xs">
                <div>
                    <span class="text-slate-400 block mb-0.5 font-medium">Nhà cung cấp</span>
                    <strong class="text-slate-800 text-sm font-bold">Veloce ShoeShop</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5 font-medium">Thời gian tạo đơn</span>
                    <strong class="text-slate-700 font-semibold">{{ $order->created_at->format('H:i - d/m/Y') }}</strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5 font-medium">Số tiền thanh toán</span>
                    <strong class="text-[#005baa] text-base font-black">
                        {{ number_format($order->total_amount - $order->discount_amount + $order->shipping_fee) }} đ
                    </strong>
                </div>
                <div>
                    <span class="text-slate-400 block mb-0.5 font-medium">Mã giao dịch (TxnRef)</span>
                    <strong class="text-slate-800 font-mono font-bold">{{ $order->code }}</strong>
                </div>
            </div>

            <!-- Danh sách sản phẩm mua -->
            <div class="border-t border-slate-200 pt-4 space-y-3">
                <span class="text-slate-400 text-xs block font-semibold uppercase tracking-wider mb-2">Chi tiết sản phẩm</span>
                @foreach($order->items as $item)
                    <div class="flex items-start gap-2.5 text-xs bg-white p-2 rounded-xl border border-slate-100 shadow-2xs">
                        <div class="w-10 h-10 rounded bg-slate-50 overflow-hidden shrink-0 border border-slate-100">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[8px] text-slate-400">No Image</div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-slate-800 truncate" title="{{ $item->name }}">{{ $item->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-mono">
                                SKU: {{ $item->productVariant ? $item->productVariant->sku : ($item->product->sku ?? 'N/A') }}
                            </p>
                            @if($item->attributes_variant)
                                <p class="text-[9px] text-indigo-600 font-semibold mt-0.5">{{ $item->attributes_variant }}</p>
                            @endif
                            <div class="flex justify-between items-center mt-1 text-[10px] font-bold">
                                <span class="text-slate-500">x{{ $item->quantity }}</span>
                                <span class="text-[#005baa]">{{ number_format($item->price_variant ?? $item->price) }}đ</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="pt-4 border-t border-slate-200 text-[10px] text-slate-400 leading-relaxed">
                Quý khách đang thực hiện giao dịch thanh toán trực tuyến qua Cổng thanh toán VNPAY. Vui lòng kiểm tra lại thông tin đơn hàng cẩn thận.
            </div>
        </div>

        <!-- Cột phải: Form nhập thông tin thẻ & điều khoản -->
        <div class="md:col-span-2 bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            
            <!-- VNPAY Steps bar -->
            <div class="flex items-center gap-4 text-xs font-semibold border-b border-slate-100 pb-4">
                <div class="flex items-center gap-1.5 text-[#005baa]">
                    <span class="w-5 h-5 rounded-full bg-[#005baa] text-white flex items-center justify-center font-bold text-[10px]">1</span>
                    <span>Thông tin thẻ</span>
                </div>
                <div class="h-px bg-slate-200 flex-1"></div>
                <div class="flex items-center gap-1.5 text-slate-400">
                    <span class="w-5 h-5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-[10px]">2</span>
                    <span>Xác thực OTP</span>
                </div>
            </div>

            @if(session('error'))
                <div class="p-3.5 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-xs font-semibold text-center">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Form Thanh toán -->
            <form action="{{ route('checkout.payment.online.submit', $order->id) }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Số thẻ ATM / Tài khoản ngân hàng</label>
                    <div class="relative">
                        <input type="text" name="card_number" required placeholder="Nhập số thẻ ATM thanh toán..." 
                            class="w-full pl-11 pr-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#005baa] font-mono text-slate-800 font-bold">
                        <i data-lucide="credit-card" class="w-4 h-4 text-slate-400 absolute left-4 top-4"></i>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Tên chủ thẻ (Không dấu)</label>
                        <div class="relative">
                            <input type="text" name="cardholder_name" required placeholder="VD: NGUYEN VAN A" 
                                class="w-full pl-11 pr-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#005baa] uppercase font-bold text-slate-800">
                            <i data-lucide="user" class="w-4 h-4 text-slate-400 absolute left-4 top-4"></i>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Mật khẩu / Mã OTP xác thực</label>
                        <div class="relative">
                            <input type="password" name="password" required placeholder="Nhập mã xác thực..." 
                                class="w-full pl-11 pr-4 py-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#005baa] font-mono font-bold text-slate-800">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-400 absolute left-4 top-4"></i>
                        </div>
                    </div>
                </div>

                <!-- Điều khoản sử dụng checkbox -->
                <div class="flex items-start gap-2.5 text-xs text-slate-500 font-medium py-2">
                    <input type="checkbox" id="accept_terms" required class="w-4 h-4 mt-0.5 border-slate-300 text-[#005baa] rounded focus:ring-[#005baa] cursor-pointer">
                    <label for="accept_terms" class="cursor-pointer select-none leading-relaxed">
                        Tôi đồng ý với các <a href="#" class="text-[#005baa] hover:underline font-bold">Điều khoản dịch vụ</a>, <a href="#" class="text-[#005baa] hover:underline font-bold">Chính sách bảo mật thông tin</a> của VNPAY và cam kết các thông tin thanh toán trên là chính xác.
                    </label>
                </div>

                <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                    <button type="submit" class="w-full sm:flex-1 bg-[#005baa] hover:bg-[#004b8c] text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-indigo-600/10 flex items-center justify-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i> Xác thực & Thanh toán
                    </button>
                    <a href="{{ route('my-orders') }}" class="w-full sm:w-auto text-center border border-slate-200 hover:bg-slate-50 text-slate-500 font-semibold py-3.5 px-6 rounded-xl transition text-xs">
                        Hủy giao dịch
                    </a>
                </div>
            </form>

            <!-- VNPAY Footer logos mock -->
            <div class="border-t border-slate-100 pt-6 flex flex-wrap items-center justify-between gap-4 text-[10px] text-slate-400">
                <span>© 2026 VNPAY - Công ty Cổ phần Giải pháp Thanh toán Việt Nam</span>
                <div class="flex items-center gap-3 font-bold text-slate-500">
                    <span>PCI DSS compliant</span>
                    <span>•</span>
                    <span>Verified by Visa</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
