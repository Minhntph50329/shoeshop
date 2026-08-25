@extends('layouts.admin')

@section('title', 'Cài đặt Thông báo - Veloce')
@section('page_title', 'Cài đặt Thông báo')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}" class="text-slate-400 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-bold text-slate-800">Cấu hình Thông báo</h2>
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

<form action="{{ route('admin.settings.notification.save') }}" method="POST" class="max-w-3xl space-y-6">
    @csrf

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i> Email nhận thông báo
            </h3>
        </div>
        
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-2">Địa chỉ Email</label>
            <input type="email" name="notification_email" value="{{ old('notification_email', \App\Models\Setting::get('notification_email', 'admin@veloce.vn')) }}" class="w-full md:w-1/2 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
            <p class="text-[10px] text-slate-500 mt-1">Hệ thống sẽ gửi các thông báo quan trọng đến địa chỉ email này.</p>
            @error('notification_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="bell" class="w-4 h-4 text-slate-400"></i> Các sự kiện thông báo
            </h3>
        </div>
        
        <div class="divide-y divide-slate-100">
            {{-- Item 1 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Đơn hàng mới</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Nhận thông báo khi có đơn hàng mới.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_new_order" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_new_order', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            {{-- Item 2 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Yêu cầu trả hàng</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Nhận thông báo khi khách hàng yêu cầu hoàn trả.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_return" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_return', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600"></div>
                </label>
            </div>

            {{-- Item 3 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <i data-lucide="star" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Đánh giá mới</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Nhận thông báo khi có đánh giá sản phẩm mới.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_review" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_review', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                </label>
            </div>

            {{-- Item 4 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Sản phẩm sắp hết hàng</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Cảnh báo khi tồn kho sản phẩm xuống dưới mức tối thiểu.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_low_stock" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_low_stock', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                </label>
            </div>

            {{-- Item 5 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <i data-lucide="users" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Khách hàng mới</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Nhận thông báo khi có người dùng đăng ký tài khoản.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_new_customer" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_new_customer', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                </label>
            </div>

            {{-- Item 6 --}}
            <div class="flex items-center justify-between p-6 hover:bg-slate-50 transition">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                        <i data-lucide="message-square" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-slate-800">Liên hệ mới</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Nhận thông báo khi có khách hàng gửi liên hệ.</p>
                    </div>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="notify_contact" value="1" class="sr-only peer" {{ \App\Models\Setting::get('notify_contact', '1') == '1' ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-500"></div>
                </label>
            </div>
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
