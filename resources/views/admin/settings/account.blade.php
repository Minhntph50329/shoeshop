@extends('layouts.admin')

@section('title', 'Tài khoản & Bảo mật - Veloce')
@section('page_title', 'Tài khoản & Bảo mật')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.settings.index') }}" class="text-slate-400 hover:text-indigo-600 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-bold text-slate-800">Quản lý Tài khoản Admin</h2>
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

<form action="{{ route('admin.settings.account.save') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Ảnh đại diện --}}
        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col items-center text-center">
                <div class="w-32 h-32 rounded-full border-4 border-slate-50 bg-slate-100 overflow-hidden mb-4 relative group">
                    <img id="avatar_preview" src="{{ auth()->user()->avatar ? asset(auth()->user()->avatar) : '' }}" alt="Avatar" class="w-full h-full object-cover {{ auth()->user()->avatar ? '' : 'hidden' }}">
                    <div id="avatar_placeholder" class="w-full h-full flex items-center justify-center text-slate-300 {{ auth()->user()->avatar ? 'hidden' : '' }}">
                        <i data-lucide="user" class="w-12 h-12"></i>
                    </div>
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                        <label for="avatar_upload" class="cursor-pointer text-white font-semibold text-xs">
                            <i data-lucide="camera" class="w-6 h-6 mx-auto mb-1"></i>
                            Đổi ảnh
                        </label>
                        <input type="file" id="avatar_upload" name="avatar" class="hidden" accept="image/*">
                    </div>
                </div>
                <h3 class="font-bold text-slate-800">{{ auth()->user()->fullname }}</h3>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase tracking-wider">
                    Administrator
                </span>
                
                @error('avatar') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Form Thông tin & Mật khẩu --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Thông tin cá nhân --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-800">Thông tin cá nhân</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Họ tên</label>
                        <input type="text" name="fullname" value="{{ old('fullname', auth()->user()->fullname) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('fullname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Email đăng nhập</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Đổi mật khẩu --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="font-bold text-slate-800">Đổi mật khẩu</h3>
                    <p class="text-[10px] text-slate-500 mt-1">Để trống nếu không muốn đổi mật khẩu.</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" class="w-full md:w-2/3 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Mật khẩu mới</label>
                        <input type="password" name="new_password" class="w-full md:w-2/3 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                        @error('new_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Xác nhận mật khẩu mới</label>
                        <input type="password" name="new_password_confirmation" class="w-full md:w-2/3 px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex items-center justify-center gap-2 px-8 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-sm shadow-indigo-200 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition disabled:opacity-70" onclick="this.disabled=true; this.innerHTML='<i data-lucide=\'loader-2\' class=\'w-5 h-5 animate-spin\'></i> Đang lưu...'; this.form.submit();">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Cập nhật tài khoản
                </button>

                <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="flex items-center justify-center gap-2 px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    Đăng xuất
                </button>
            </div>
            
        </div>
    </div>
</form>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
@endsection

@section('scripts')
<script>
    document.getElementById('avatar_upload').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar_preview').src = e.target.result;
                document.getElementById('avatar_preview').classList.remove('hidden');
                document.getElementById('avatar_placeholder').classList.add('hidden');
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection
