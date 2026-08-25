@extends('layouts.admin')

@section('title', 'Cài đặt Bán hàng - Veloce')
@section('page_title', 'Cài đặt Bán hàng')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}" class="text-slate-400 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-bold text-slate-800">Cấu hình Bán hàng</h2>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center gap-3 auto-hide-error">
    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
        <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600"></i>
    </div>
    <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
</div>
@endif

<form action="{{ route('admin.settings.store.save') }}" method="POST" class="max-w-3xl space-y-6">
    @csrf

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-4 h-4 text-slate-400"></i> Cấu hình chung
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Đơn vị tiền tệ</label>
                <input type="text" name="currency" value="{{ old('currency', \App\Models\Setting::get('currency', 'VND')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm bg-slate-50">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2">Số sản phẩm hiển thị mỗi trang</label>
                <input type="number" min="1" name="products_per_page" value="{{ old('products_per_page', \App\Models\Setting::get('products_per_page', '20')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
            </div>
            
            <div class="md:col-span-2">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="allow_out_of_stock" value="1" class="sr-only peer" {{ \App\Models\Setting::get('allow_out_of_stock', '0') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Cho phép đặt hàng khi sản phẩm hết hàng</span>
                </label>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="x-circle" class="w-4 h-4 text-slate-400"></i> Hủy đơn hàng
            </h3>
        </div>

        <div class="space-y-4">
            <div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="allow_cancel_order" value="1" class="sr-only peer" onchange="document.getElementById('cancel_hours_div').style.opacity = this.checked ? '1' : '0.5'; document.getElementById('cancel_hours_input').disabled = !this.checked;" {{ \App\Models\Setting::get('allow_cancel_order', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-bold text-slate-700">Cho phép khách hàng tự hủy đơn</span>
                </label>
            </div>

            <div id="cancel_hours_div" class="pl-14 transition-opacity" style="opacity: {{ \App\Models\Setting::get('allow_cancel_order', '1') == '1' ? '1' : '0.5' }}">
                <label class="block text-xs font-semibold text-slate-700 mb-2">Thời gian cho phép hủy đơn (giờ)</label>
                <input type="number" min="0" id="cancel_hours_input" name="cancel_order_hours" value="{{ old('cancel_order_hours', \App\Models\Setting::get('cancel_order_hours', '2')) }}" class="w-32 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm" {{ \App\Models\Setting::get('allow_cancel_order', '1') == '1' ? '' : 'disabled' }}>
                <p class="text-[10px] text-slate-500 mt-1">Tính từ lúc đặt hàng thành công. Ví dụ: 2 giờ.</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="gift" class="w-4 h-4 text-slate-400"></i> Khuyến mãi & Vận chuyển
            </h3>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Giá trị đơn hàng tối thiểu để được miễn phí vận chuyển (VNĐ)</label>
            <input type="number" min="0" name="free_shipping_min" value="{{ old('free_shipping_min', \App\Models\Setting::get('free_shipping_min', '500000')) }}" class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
            @error('free_shipping_min') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <button type="submit" class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition disabled:opacity-70" onclick="this.disabled=true; this.innerHTML='<i data-lucide=\'loader-2\' class=\'w-5 h-5 animate-spin\'></i> Đang lưu...'; this.form.submit();">
            <i data-lucide="save" class="w-5 h-5"></i>
            Lưu thay đổi
        </button>
    </div>
</form>
@endsection
