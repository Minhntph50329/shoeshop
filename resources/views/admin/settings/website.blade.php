@extends('layouts.admin')

@section('title', 'Cài đặt Website - Veloce')
@section('page_title', 'Cài đặt Website')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}" class="text-slate-400 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-bold text-slate-800">Thông tin & trạng thái Website</h2>
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

<form action="{{ route('admin.settings.website.save') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Thông tin website --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="info" class="w-4 h-4 text-slate-400"></i> Thông tin website
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Tên website <span class="text-red-500">*</span></label>
                        <input type="text" name="site_name" value="{{ old('site_name', \App\Models\Setting::get('site_name', 'Veloce')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('site_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Email liên hệ <span class="text-red-500">*</span></label>
                        <input type="email" name="site_email" value="{{ old('site_email', \App\Models\Setting::get('site_email', 'contact@veloce.vn')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('site_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Số điện thoại <span class="text-red-500">*</span></label>
                        <input type="text" name="site_phone" value="{{ old('site_phone', \App\Models\Setting::get('site_phone', '0123456789')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('site_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                        <input type="text" name="site_address" value="{{ old('site_address', \App\Models\Setting::get('site_address', 'Hà Nội')) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('site_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Mô tả website</label>
                        <textarea name="site_description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">{{ old('site_description', \App\Models\Setting::get('site_description')) }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="image" class="w-4 h-4 text-slate-400"></i> Nhận diện thương hiệu
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Logo</label>
                        @if(\App\Models\Setting::get('logo'))
                            <div class="mb-3 h-16 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center p-2">
                                <img src="{{ asset(\App\Models\Setting::get('logo')) }}" alt="Logo" class="max-h-full object-contain">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Favicon</label>
                        @if(\App\Models\Setting::get('favicon'))
                            <div class="mb-3 w-16 h-16 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center p-2">
                                <img src="{{ asset(\App\Models\Setting::get('favicon')) }}" alt="Favicon" class="max-h-full object-contain">
                            </div>
                        @endif
                        <input type="file" name="favicon" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>
                </div>
            </div>
        </div>

        {{-- Trạng thái website --}}
        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i data-lucide="power" class="w-4 h-4 text-slate-400"></i> Trạng thái website
                    </h3>
                </div>

                <div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" onchange="document.getElementById('maintenance_msg_div').style.display = this.checked ? 'block' : 'none'" {{ \App\Models\Setting::get('maintenance_mode', '0') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Chế độ bảo trì</span>
                    </label>
                    <p class="text-[10px] text-slate-500 mt-2">Bật chế độ này để tạm dừng khách truy cập website. Quản trị viên vẫn có thể đăng nhập.</p>
                </div>

                <div id="maintenance_msg_div" style="display: {{ \App\Models\Setting::get('maintenance_mode', '0') == '1' ? 'block' : 'none' }};">
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Nội dung thông báo bảo trì</label>
                    <textarea name="maintenance_message" rows="4" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition text-sm">{{ old('maintenance_message', \App\Models\Setting::get('maintenance_message', 'Website đang được bảo trì. Vui lòng quay lại sau.')) }}</textarea>
                </div>
            </div>
            
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition disabled:opacity-70" onclick="this.disabled=true; this.innerHTML='<i data-lucide=\'loader-2\' class=\'w-5 h-5 animate-spin\'></i> Đang lưu...'; this.form.submit();">
                <i data-lucide="save" class="w-5 h-5"></i>
                Lưu thay đổi
            </button>
        </div>

    </div>
</form>
@endsection
