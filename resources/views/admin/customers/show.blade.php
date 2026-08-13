@extends('layouts.admin')

@section('title', 'Chi tiết người dùng - Veloce Admin')
@section('page_title', 'Chi tiết người dùng')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Chi tiết tài khoản #{{ $customer->id }}</h2>
            <p class="text-sm text-slate-500">Xem đầy đủ thông tin cá nhân, danh sách địa chỉ và giỏ hàng hiện tại</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.edit', $customer->id) }}" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Overview Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4 text-center md:text-left flex flex-col items-center md:items-start">
            @if($customer->avatar)
                <img src="{{ asset('storage/' . $customer->avatar) }}" alt="{{ $customer->fullname }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-indigo-50 shadow-md">
            @else
                <div class="w-24 h-24 rounded-2xl bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-3xl uppercase border-4 border-indigo-50 shadow-md">
                    {{ substr($customer->fullname ?? $customer->email, 0, 1) }}
                </div>
            @endif

            <div>
                <h3 class="text-lg font-black text-slate-900">{{ $customer->fullname ?? 'Chưa cập nhật tên' }}</h3>
                <p class="text-xs text-slate-500">{{ $customer->email }}</p>
            </div>

            <div class="flex items-center gap-2 pt-2">
                @if($customer->role === 'admin')
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-bold border border-purple-100">
                        Admin (Quản trị)
                    </span>
                @else
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                        Khách hàng
                    </span>
                @endif

                @if($customer->status === 'active')
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">
                        Hoạt động
                    </span>
                @else
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-100">
                        Đã khóa
                    </span>
                @endif
            </div>

            @if($customer->status === 'locked' && $customer->reason_lock)
                <div class="w-full p-3 bg-rose-50 border border-rose-100 rounded-xl text-left space-y-1">
                    <span class="text-[11px] font-bold uppercase text-rose-700 block">Lý do khóa:</span>
                    <p class="text-xs text-rose-600 font-medium">{{ $customer->reason_lock }}</p>
                </div>
            @endif
        </div>

        <!-- Details Info -->
        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin chi tiết</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Số điện thoại:</span>
                    <span class="font-bold text-slate-800">{{ $customer->phone_number ?? 'Chưa cập nhật' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Giới tính:</span>
                    <span class="font-bold text-slate-800">
                        @if($customer->gender === 'male') Nam
                        @elseif($customer->gender === 'female') Nữ
                        @elseif($customer->gender === 'other') Khác
                        @else Chưa cập nhật @endif
                    </span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Ngày sinh:</span>
                    <span class="font-bold text-slate-800">{{ $customer->birthday ? $customer->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Ngày tạo tài khoản:</span>
                    <span class="font-bold text-slate-800">{{ $customer->created_at ? $customer->created_at->format('d/m/Y H:i:s') : 'N/A' }}</span>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="pt-2">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tài khoản Ngân hàng</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Ngân hàng:</span>
                        <span class="font-bold text-slate-800">{{ $customer->bank_name ?? 'N/A' }}</span>
                    </div>
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Chủ tài khoản:</span>
                        <span class="font-bold text-slate-800">{{ $customer->user_bank_name ?? 'N/A' }}</span>
                    </div>
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Số tài khoản:</span>
                        <span class="font-bold text-indigo-600">{{ $customer->bank_account ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved User Addresses (user_addresses) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center justify-between border-b pb-3 border-slate-100">
            <span>Danh sách Địa chỉ đã lưu (user_addresses)</span>
            <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold">{{ $customer->addresses->count() }} địa chỉ</span>
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($customer->addresses as $addr)
                <div class="p-4 border rounded-xl relative space-y-2 {{ $addr->is_default ? 'border-indigo-500 bg-indigo-50/20' : 'border-slate-200' }}">
                    @if($addr->is_default)
                        <span class="absolute top-3 right-3 px-2 py-0.5 bg-indigo-600 text-white rounded text-[10px] font-bold">Mặc định</span>
                    @endif
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                        <span class="font-bold text-xs text-slate-900">{{ $addr->fullname ?? $customer->fullname }}</span>
                        <span class="text-slate-400 text-xs">({{ $addr->phone_number ?? $customer->phone_number }})</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $addr->address }}</p>
                    <div class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                        {{ $addr->ward ? $addr->ward . ', ' : '' }}{{ $addr->district ? $addr->district . ', ' : '' }}{{ $addr->province }}
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-6 text-slate-400 text-xs">Người dùng chưa lưu địa chỉ nào.</div>
            @endforelse
        </div>
    </div>

    <!-- Active Cart Preview -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Giỏ hàng hiện tại</h3>
        @php
            $activeCart = $customer->carts->where('status', 'active')->first();
        @endphp
        @if($activeCart && $activeCart->items->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                            <th class="p-3">Sản phẩm</th>
                            <th class="p-3">Biến thể SKU</th>
                            <th class="p-3">Đơn giá</th>
                            <th class="p-3">Số lượng</th>
                            <th class="p-3 text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($activeCart->items as $item)
                            <tr>
                                <td class="p-3 font-semibold text-slate-900">{{ $item->product->name ?? 'N/A' }}</td>
                                <td class="p-3 font-mono text-slate-500">{{ $item->variant->sku ?? 'Mặc định' }}</td>
                                <td class="p-3 font-bold text-slate-800">{{ number_format($item->price_at_time, 0, ',', '.') }} đ</td>
                                <td class="p-3 font-bold">{{ $item->quantity }}</td>
                                <td class="p-3 text-right font-black text-indigo-600">{{ number_format($item->subtotal, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center py-4 text-xs text-slate-400">Giỏ hàng trống.</p>
        @endif
    </div>
</div>
@endsection
