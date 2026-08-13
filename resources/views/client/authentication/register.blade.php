@extends('layouts.app')

@section('title', 'Đăng ký tài khoản - Veloce')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 mb-2">
                <i data-lucide="user-plus" class="w-6 h-6"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Tạo tài khoản mới</h2>
            <p class="text-xs text-slate-500">Đăng ký để trải nghiệm dịch vụ tuyệt vời tại Veloce</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ và tên</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required placeholder="Nguyễn Văn A" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                @error('fullname')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

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
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Số điện thoại</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" placeholder="0912345678" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                @error('phone_number')
                    <p class="mt-1.5 text-xs text-rose-500 font-medium flex items-center gap-1">
                        <i data-lucide="info" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Mật khẩu</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                    </span>
                    <input type="password" name="password" required placeholder="Tối thiểu 6 ký tự" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
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
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                    </span>
                    <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu" 
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>

            <button type="submit" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition active:scale-[0.99]">
                Đăng ký tài khoản
            </button>
        </form>

        <div class="pt-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:underline">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>
@endsection
