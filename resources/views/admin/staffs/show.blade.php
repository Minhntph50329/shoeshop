@extends('layouts.admin')

@section('title', 'Chi tiết nhân sự - Veloce Admin')
@section('page_title', 'Hồ sơ nhân sự')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    @php
        $currentUser = auth()->user();
        $isCurrentUser = ($currentUser->id === $staff->id);
        $targetRole = $staff->roles->first()->name ?? $staff->role ?? 'Staff';

        $canEditUser = false;
        if ($currentUser->hasRole('Super Admin')) {
            $canEditUser = true;
        } elseif ($currentUser->hasRole('Admin')) {
            if ($isCurrentUser || !in_array($targetRole, ['Super Admin', 'Admin'])) {
                $canEditUser = true;
            }
        }
    @endphp

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Hồ sơ nhân sự #{{ $staff->id }}</h2>
            <p class="text-sm text-slate-500">Xem đầy đủ thông tin cá nhân, phân quyền hệ thống và tài khoản ngân hàng</p>
        </div>
        <div class="flex items-center gap-3">
            @if($canEditUser)
                <a href="{{ route('admin.staffs.edit', $staff->id) }}" class="flex items-center gap-2 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                    <i data-lucide="edit-3" class="w-4 h-4"></i> Chỉnh sửa
                </a>
            @endif
            <a href="{{ route('admin.staffs.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Overview Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4 text-center md:text-left flex flex-col items-center md:items-start">
            @if($staff->avatar)
                <img src="{{ asset($staff->avatar) }}" alt="{{ $staff->fullname }}" class="w-24 h-24 rounded-2xl object-cover border-4 border-indigo-50 shadow-md">
            @else
                <div class="w-24 h-24 rounded-2xl bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-3xl uppercase border-4 border-indigo-50 shadow-md">
                    {{ substr($staff->fullname ?? $staff->email, 0, 1) }}
                </div>
            @endif

            <div>
                <h3 class="text-lg font-black text-slate-900">{{ $staff->fullname ?? 'Chưa cập nhật tên' }}</h3>
                <p class="text-xs text-slate-500">{{ $staff->email }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 pt-2">
                @if($targetRole === 'Super Admin')
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-100 inline-flex items-center gap-1">
                        <i data-lucide="shield-alert" class="w-3.5 h-3.5"></i> Super Admin
                    </span>
                @elseif($targetRole === 'Admin')
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-bold border border-purple-100 inline-flex items-center gap-1">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Admin (Quản trị)
                    </span>
                @else
                    <span class="px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-xs font-bold border border-sky-100 inline-flex items-center gap-1">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Nhân viên (Staff)
                    </span>
                @endif

                @if($staff->status === 'active')
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">
                        Hoạt động
                    </span>
                @else
                    <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-xs font-bold border border-rose-100">
                        Đã khóa
                    </span>
                @endif
            </div>

            @if($staff->status === 'locked' && $staff->reason_lock)
                <div class="w-full p-3 bg-rose-50 border border-rose-100 rounded-xl text-left space-y-1">
                    <span class="text-[11px] font-bold uppercase text-rose-700 block">Lý do khóa:</span>
                    <p class="text-xs text-rose-600 font-medium">{{ $staff->reason_lock }}</p>
                </div>
            @endif
        </div>

        <!-- Details Info -->
        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b pb-3 border-slate-100">Thông tin chi tiết</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Số điện thoại:</span>
                    <span class="font-bold text-slate-800">{{ $staff->phone_number ?? 'Chưa cập nhật' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Giới tính:</span>
                    <span class="font-bold text-slate-800">
                        @if($staff->gender === 'male') Nam
                        @elseif($staff->gender === 'female') Nữ
                        @elseif($staff->gender === 'other') Khác
                        @else Chưa cập nhật @endif
                    </span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Ngày sinh:</span>
                    <span class="font-bold text-slate-800">{{ $staff->birthday ? $staff->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl space-y-1">
                    <span class="text-slate-400 font-semibold block">Ngày tạo tài khoản:</span>
                    <span class="font-bold text-slate-800">{{ $staff->created_at ? $staff->created_at->format('d/m/Y H:i:s') : 'N/A' }}</span>
                </div>
            </div>

            <!-- Bank Information -->
            <div class="pt-2">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3">Tài khoản Ngân hàng (Nhận lương)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Ngân hàng:</span>
                        <span class="font-bold text-slate-800">{{ $staff->bank_name ?? 'N/A' }}</span>
                    </div>
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Chủ tài khoản:</span>
                        <span class="font-bold text-slate-800">{{ $staff->user_bank_name ?? 'N/A' }}</span>
                    </div>
                    <div class="p-3 border border-slate-100 rounded-xl">
                        <span class="text-slate-400 block text-[11px]">Số tài khoản:</span>
                        <span class="font-bold text-indigo-600">{{ $staff->bank_account ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Addresses (if any) -->
    @if($staff->addresses && $staff->addresses->count() > 0)
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center justify-between border-b pb-3 border-slate-100">
                <span>Danh sách Địa chỉ</span>
                <span class="px-2.5 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold">{{ $staff->addresses->count() }} địa chỉ</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($staff->addresses as $addr)
                    <div class="p-4 border rounded-xl relative space-y-2 {{ $addr->is_default ? 'border-indigo-500 bg-indigo-50/20' : 'border-slate-200' }}">
                        @if($addr->is_default)
                            <span class="absolute top-3 right-3 px-2 py-0.5 bg-indigo-600 text-white rounded text-[10px] font-bold">Mặc định</span>
                        @endif
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-indigo-600"></i>
                            <span class="font-bold text-xs text-slate-900">{{ $addr->fullname ?? $staff->fullname }}</span>
                            <span class="text-slate-400 text-xs">({{ $addr->phone_number ?? $staff->phone_number }})</span>
                        </div>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $addr->address }}</p>
                        <div class="text-[11px] text-slate-400 pt-1 border-t border-slate-100">
                            {{ $addr->ward ? $addr->ward . ', ' : '' }}{{ $addr->district ? $addr->district . ', ' : '' }}{{ $addr->province }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

