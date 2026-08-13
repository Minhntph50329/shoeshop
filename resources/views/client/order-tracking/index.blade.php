@extends('layouts.app')

@section('title', 'Theo dõi đơn hàng - Veloce')

@section('content')
<div class="max-w-md mx-auto space-y-6">
    <h2 class="text-2xl font-extrabold tracking-tight text-center">Theo dõi đơn hàng</h2>
    <form class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <input type="text" name="order_number" placeholder="Nhập mã đơn hàng của bạn (Ví dụ: VELOCE-XXXXX)" class="border p-3 rounded-xl text-xs w-full">
        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-xl text-xs">Kiểm tra ngay</button>
    </form>
</div>
@endsection
