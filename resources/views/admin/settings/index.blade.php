@extends('layouts.admin')

@section('title', 'Cấu hình hệ thống - Veloce')
@section('page_title', 'Cài đặt hệ thống')

@section('content')
<div class="mb-6">
    <p class="text-slate-500 text-sm">Quản lý và cấu hình các thiết lập của hệ thống Veloce.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Card 1: Website --}}
    <a href="{{ route('admin.settings.website') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-indigo-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition">
            <i data-lucide="globe" class="w-6 h-6 text-slate-400 group-hover:text-indigo-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-indigo-600 transition">Website</h3>
            <p class="text-sm text-slate-500 mt-1">Quản lý thông tin và trạng thái website.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-indigo-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Card 2: Bán hàng --}}
    <a href="{{ route('admin.settings.store') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-emerald-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-emerald-50 group-hover:border-emerald-100 transition">
            <i data-lucide="shopping-cart" class="w-6 h-6 text-slate-400 group-hover:text-emerald-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-emerald-600 transition">Bán hàng</h3>
            <p class="text-sm text-slate-500 mt-1">Cấu hình sản phẩm, đơn hàng và các quy tắc bán hàng.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-emerald-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Card 3: Thanh toán --}}
    <a href="{{ route('admin.settings.payment') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-amber-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-amber-50 group-hover:border-amber-100 transition">
            <i data-lucide="credit-card" class="w-6 h-6 text-slate-400 group-hover:text-amber-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-amber-600 transition">Thanh toán</h3>
            <p class="text-sm text-slate-500 mt-1">Quản lý các phương thức thanh toán của Veloce.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-amber-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Card 4: Vận chuyển --}}
    <a href="{{ route('admin.settings.shipping') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-sky-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-sky-50 group-hover:border-sky-100 transition">
            <i data-lucide="truck" class="w-6 h-6 text-slate-400 group-hover:text-sky-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-sky-600 transition">Vận chuyển</h3>
            <p class="text-sm text-slate-500 mt-1">Cấu hình phí vận chuyển và chính sách giao hàng.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-sky-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Card 5: Thông báo --}}
    <a href="{{ route('admin.settings.notification') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-rose-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-rose-50 group-hover:border-rose-100 transition">
            <i data-lucide="bell" class="w-6 h-6 text-slate-400 group-hover:text-rose-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-rose-600 transition">Thông báo</h3>
            <p class="text-sm text-slate-500 mt-1">Quản lý các loại thông báo dành cho Admin.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-rose-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

    {{-- Card 6: Tài khoản & bảo mật --}}
    <a href="{{ route('admin.settings.account') }}" class="flex items-start gap-4 p-6 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-purple-100 transition group cursor-pointer">
        <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center shrink-0 group-hover:bg-purple-50 group-hover:border-purple-100 transition">
            <i data-lucide="shield" class="w-6 h-6 text-slate-400 group-hover:text-purple-600 transition"></i>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-slate-800 group-hover:text-purple-600 transition">Tài khoản & bảo mật</h3>
            <p class="text-sm text-slate-500 mt-1">Quản lý thông tin tài khoản Admin và bảo mật.</p>
        </div>
        <div class="shrink-0 mt-2">
            <i data-lucide="arrow-right" class="w-5 h-5 text-slate-300 group-hover:text-purple-600 group-hover:translate-x-1 transition-transform"></i>
        </div>
    </a>

</div>
@endsection
