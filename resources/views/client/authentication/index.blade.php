@extends('layouts.app')

@section('title', 'Đăng nhập - Veloce')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-2xl border border-slate-100 shadow-sm space-y-6">
    <h2 class="text-2xl font-extrabold text-center">Đăng nhập tài khoản</h2>
    <form class="space-y-4">
        <input type="email" placeholder="Email đăng nhập" class="border p-3 rounded-xl text-xs w-full">
        <input type="password" placeholder="Mật khẩu" class="border p-3 rounded-xl text-xs w-full">
        <button class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs">Đăng nhập</button>
    </form>
</div>
@endsection
