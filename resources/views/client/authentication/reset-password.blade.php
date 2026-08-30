@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - Veloce')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 mb-2">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Đặt lại mật khẩu</h2>
            <p class="text-xs text-slate-500">Vui lòng nhập mật khẩu mới cho tài khoản của bạn.</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-xs flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-rose-500 mt-0.5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email', $email) }}" readonly
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-xs font-medium text-slate-500 focus:outline-none transition">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mật khẩu mới</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" id="password" name="password" placeholder="Tối thiểu 6 ký tự" 
                        class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <i data-lucide="eye" id="toggleIcon1" class="w-4 h-4"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Xác nhận mật khẩu</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới" 
                        class="w-full pl-10 pr-10 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <button type="button" onclick="togglePassword('password_confirmation', 'toggleIcon2')" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 focus:outline-none">
                        <i data-lucide="eye" id="toggleIcon2" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition active:scale-[0.99]">
                Cập nhật mật khẩu
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            passwordInput.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>
@endsection
