@extends('layouts.app')

@section('title', 'Đặt hàng thành công - Veloce')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 py-8">

    {{-- Success Banner --}}
    <div class="bg-white rounded-2xl border border-emerald-200 shadow-sm overflow-hidden">
        {{-- Green top bar --}}
        <div class="h-2 bg-gradient-to-r from-emerald-400 to-teal-500"></div>

        <div class="px-8 py-10 text-center">
            {{-- Animated check --}}
            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-5 relative">
                <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <div class="absolute inset-0 rounded-full border-4 border-emerald-300 animate-ping opacity-30"></div>
            </div>

            @if(session('success'))
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold mb-3">
                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                    {{ session('success') }}
                </div>
            @endif

            <h1 class="text-2xl font-black text-slate-800 mb-2">Đặt hàng thành công!</h1>
            <p class="text-sm text-slate-500 mb-1">Cảm ơn bạn đã tin tưởng mua sắm tại <span class="font-black text-indigo-600">VELOCE</span></p>
            <p class="text-xs text-slate-400">Chúng tôi sẽ xác nhận và xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
        </div>
    </div>

    {{-- Order Info --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-800">Thông tin đơn hàng</h3>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4 text-xs">
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Mã đơn hàng</p>
                <p class="font-black text-indigo-600 text-base">#{{ $order->code }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Trạng thái</p>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full text-[11px] font-bold">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Chờ xác nhận
                </span>
            </div>
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Ngày đặt</p>
                <p class="font-semibold text-slate-800">{{ $order->created_at->format('H:i, d/m/Y') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Thanh toán</p>
                <p class="font-semibold text-slate-800">{{ $order->payment->name ?? 'N/A' }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Giao tới</p>
                <p class="font-semibold text-slate-800 col-span-2">{{ $order->address }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-slate-400 font-medium">Người nhận</p>
                <p class="font-semibold text-slate-800">{{ $order->fullname }} · {{ $order->phone_number }}</p>
            </div>
        </div>
    </div>

    {{-- Products --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-sm text-slate-800">Sản phẩm</h3>
        </div>
        <div class="divide-y divide-slate-50">
            @foreach($order->items as $item)
            @php
                $img = null;
                if ($item->productVariant && $item->productVariant->image) {
                    $img = asset($item->productVariant->image);
                } elseif ($item->product && $item->product->image) {
                    $img = asset($item->product->image);
                } elseif ($item->product && $item->product->images->first()) {
                    $img = asset($item->product->images->first()->url);
                }
            @endphp
            <div class="flex items-center gap-4 px-6 py-3">
                <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 flex-shrink-0">
                    <img src="{{ $img ?? 'https://placehold.co/48x48/f1f5f9/94a3b8?text=?' }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-slate-800 truncate">{{ $item->name }}</p>
                    @if($item->name_variant)
                        <p class="text-[11px] text-slate-400">{{ $item->name_variant }}: {{ $item->attributes_variant }}</p>
                    @endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-bold text-slate-800">x{{ $item->quantity }}</p>
                    <p class="text-xs text-indigo-600 font-semibold">{{ number_format($item->effective_price * $item->quantity, 0, ',', '.') }}đ</p>
                </div>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 space-y-1.5">
            @if($order->discount_amount > 0)
            <div class="flex justify-between text-xs text-emerald-600">
                <span>Giảm giá</span>
                <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
            </div>
            @endif
            <div class="flex justify-between text-xs text-slate-600">
                <span>Phí vận chuyển</span>
                <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
            </div>
            <div class="flex justify-between font-black text-sm text-slate-800 pt-2 border-t border-slate-200">
                <span>Tổng cộng</span>
                <span class="text-indigo-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
            </div>
        </div>
    </div>

    {{-- CTA Buttons --}}
    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('my-orders') }}"
           class="flex items-center justify-center gap-2 py-3 border-2 border-indigo-600 text-indigo-600 font-bold text-sm rounded-xl hover:bg-indigo-50 transition">
            <i data-lucide="package" class="w-4 h-4"></i>
            Xem đơn hàng
        </a>
        <a href="{{ route('shop') }}"
           class="flex items-center justify-center gap-2 py-3 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
            Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
