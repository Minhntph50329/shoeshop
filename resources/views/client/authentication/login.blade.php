@extends('layouts.app')

@section('title', 'Đăng nhập - Veloce')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 mb-2">
                <i data-lucide="log-in" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Đăng nhập tài khoản</h2>
            <p class="text-xs text-slate-500">Chào mừng bạn quay trở lại với Veloce Store</p>
        </div>

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-xs flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-rose-500 mt-0.5"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500 mt-0.5"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="your@email.com" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mật khẩu</label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="text-xs font-medium text-slate-600">Ghi nhớ đăng nhập</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition active:scale-[0.99]">
                Đăng nhập
            </button>
        </form>

        <div class="pt-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Chưa có tài khoản? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:underline">Tạo tài khoản mới</a>
            </p>
        </div>
    </div>
</div>
@endsection
