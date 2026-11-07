@extends('layouts.admin')

@section('title', 'Quản lý nhân viên - Veloce Admin')
@section('page_title', 'Danh sách nhân viên & Quản trị')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">

    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Quản lý nhân sự & Ban quản trị</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">
                Danh sách tài khoản Super Admin, Admin và Nhân viên. Phân quyền và quản lý tài khoản.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            @if(auth()->user()->hasAnyRole(['Super Admin', 'Admin']))
                <a href="{{ route('admin.staffs.trash') }}"
                   class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                    <i data-lucide="trash-2" class="h-4 w-4 shrink-0 text-slate-500"></i>
                    <span>Thùng rác</span>
                </a>

                <a href="{{ route('admin.staffs.create') }}"
                   class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                    <i data-lucide="user-plus" class="h-4 w-4 shrink-0"></i>
                    <span>Thêm nhân viên</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700">
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="w-full rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4">
        <form method="GET"
              action="{{ route('admin.staffs.index') }}"
              class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">

            <div class="relative min-w-0 sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="h-4 w-4"></i>
                </span>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Tìm theo tên, email, sđt..."
                       class="w-full min-w-0 rounded-xl border border-slate-200 py-2.5 pl-9 pr-4 text-xs transition focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
            </div>

            <div class="min-w-0">
                <select name="role"
                        class="w-full min-w-0 rounded-xl border border-slate-200 px-3 py-2.5 text-xs transition focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>
                        Super Admin
                    </option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>
                        Admin (Quản trị)
                    </option>
                    <option value="Staff" {{ request('role') == 'Staff' ? 'selected' : '' }}>
                        Staff (Nhân viên)
                    </option>
                </select>
            </div>

            <div class="flex min-w-0 gap-2">
                <select name="status"
                        class="min-w-0 flex-1 rounded-xl border border-slate-200 px-3 py-2.5 text-xs transition focus:border-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                        Hoạt động
                    </option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>
                        Đã khóa
                    </option>
                </select>

                <button type="submit"
                        class="shrink-0 rounded-xl bg-slate-900 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-slate-800">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- =========================
         MOBILE: USER CARDS
         ========================= -->
    <div class="space-y-3 md:hidden">
        @forelse($staffs as $user)
            @php
                $currentUser = auth()->user();
                $isCurrentUser = ($currentUser->id === $user->id);
                $currentRole = $user->roles->first()->name ?? $user->role ?? 'Staff';

                // Super admin chỉnh sửa tất cả vai trò của admin và nhân viên.
                // Admin thì chỉnh quyền của nhân viên và ko được chỉnh quyền của admin khác.
                // Nhân viên thì không được chỉnh gì hết.
                $canEditRole = false;
                if (!$isCurrentUser && $currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
                    if ($currentUser->hasRole('Super Admin')) {
                        $canEditRole = true;
                    } elseif ($currentUser->hasRole('Admin') && !in_array($currentRole, ['Super Admin', 'Admin'])) {
                        $canEditRole = true;
                    }
                }

                $canEditUser = false;
                if ($currentUser->hasRole('Super Admin')) {
                    $canEditUser = true;
                } elseif ($currentUser->hasRole('Admin')) {
                    if ($isCurrentUser || !in_array($currentRole, ['Super Admin', 'Admin'])) {
                        $canEditUser = true;
                    }
                }

                $canToggleUser = false;
                $canDeleteUser = false;
                if (!$isCurrentUser && $currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
                    if ($currentUser->hasRole('Super Admin')) {
                        $canToggleUser = true;
                        $canDeleteUser = true;
                    } elseif ($currentUser->hasRole('Admin') && !in_array($currentRole, ['Super Admin', 'Admin'])) {
                        $canToggleUser = true;
                        $canDeleteUser = true;
                    }
                }
            @endphp

            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">

                <!-- User -->
                <div class="flex min-w-0 items-start gap-3">
                    @if($user->avatar)
                        <img src="{{ asset($user->avatar) }}"
                             alt="{{ $user->fullname }}"
                             class="h-11 w-11 shrink-0 rounded-full border border-slate-200 object-cover">
                    @else
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-black uppercase text-indigo-600">
                            {{ substr($user->fullname ?? $user->email, 0, 1) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-1.5">
                            <h4 class="truncate text-sm font-bold text-slate-900">
                                {{ $user->fullname ?? 'Chưa cập nhật' }}
                            </h4>
                            @if($isCurrentUser)
                                <span class="rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-600">Bạn</span>
                            @endif
                        </div>
                        <p class="mt-0.5 break-all text-[11px] leading-4 text-slate-400">
                            {{ $user->email }}
                        </p>
                    </div>
                </div>

                <!-- Info -->
                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                            Số điện thoại
                        </p>
                        <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                            {{ $user->phone_number ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                            Ngày tạo
                        </p>
                        <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                            Vai trò
                        </p>

                        <div class="mt-1">
                            @if($canEditRole)
                                <form action="{{ route('admin.staffs.updateRole', $user->id) }}"
                                      method="POST"
                                      class="w-full">
                                    @csrf
                                    @method('PUT')

                                    <select name="role"
                                            onchange="this.form.submit()"
                                            class="w-full min-w-0 rounded-lg border border-slate-200 bg-slate-50 px-2 py-1.5 text-[11px] font-semibold focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ $currentRole == $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                @if($currentRole === 'Super Admin')
                                    <span class="inline-flex max-w-full items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700">
                                        <i data-lucide="shield-alert" class="h-3 w-3 shrink-0"></i>
                                        <span class="truncate">Super Admin</span>
                                    </span>
                                @elseif($currentRole === 'Admin')
                                    <span class="inline-flex max-w-full items-center gap-1 rounded-full border border-purple-200 bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">
                                        <i data-lucide="shield-check" class="h-3 w-3 shrink-0"></i>
                                        <span class="truncate">Admin</span>
                                    </span>
                                @else
                                    <span class="inline-flex max-w-full items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700">
                                        <i data-lucide="user-check" class="h-3 w-3 shrink-0"></i>
                                        <span class="truncate">Nhân viên</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">
                            Trạng thái
                        </p>

                        <div class="mt-1">
                            @if($user->status === 'active')
                                <span class="inline-flex items-center gap-1 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-emerald-500"></span>
                                    Hoạt động
                                </span>
                            @else
                                <span class="inline-flex max-w-full items-center gap-1 rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700"
                                      title="{{ $user->reason_lock }}">
                                    <span class="h-1.5 w-1.5 shrink-0 rounded-full bg-rose-500"></span>
                                    Đã khóa
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.staffs.show', $user->id) }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600"
                       title="Xem chi tiết">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </a>

                    @if($canEditUser)
                        <a href="{{ route('admin.staffs.edit', $user->id) }}"
                           class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600"
                           title="Chỉnh sửa">
                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                        </a>
                    @endif

                    @if($canToggleUser)
                        <form action="{{ route('admin.staffs.toggle', $user->id) }}"
                              method="POST"
                              class="inline-block">
                            @csrf
                            @method('PUT')

                            @if($user->status === 'active')
                                <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                                        title="Khóa tài khoản"
                                        onclick="return confirm('Bạn có muốn khóa tài khoản này?')">
                                    <i data-lucide="lock" class="h-4 w-4"></i>
                                </button>
                            @else
                                <button type="submit"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600"
                                        title="Mở khóa tài khoản">
                                    <i data-lucide="unlock" class="h-4 w-4"></i>
                                </button>
                            @endif
                        </form>
                    @endif

                    @if($canDeleteUser)
                        <form action="{{ route('admin.staffs.destroy', $user->id) }}"
                              method="POST"
                              class="inline-block"
                              onsubmit="return confirm('Bạn có chắc muốn chuyển tài khoản này vào thùng rác?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                                    title="Xóa mềm (vào thùng rác)">
                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">
                Không tìm thấy tài khoản nhân viên nào.
            </div>
        @endforelse
    </div>

    <!-- =========================
         DESKTOP/TABLET: DATA TABLE
         ========================= -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[900px] border-collapse text-left">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                        <th class="p-4">Nhân sự</th>
                        <th class="p-4">Số điện thoại</th>
                        <th class="p-4">Vai trò</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Ngày tạo</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($staffs as $user)
                        @php
                            $currentUser = auth()->user();
                            $isCurrentUser = ($currentUser->id === $user->id);
                            $currentRole = $user->roles->first()->name ?? $user->role ?? 'Staff';

                            // Super admin có quyền chỉnh sửa tất cả các vai trò của admin và nhân viên
                            // Admin thì chỉnh quyền của nhân viên và ko được chỉnh quyền của admin khác
                            // Nhân viên thì không được chỉnh gì hết
                            $canEditRole = false;
                            if (!$isCurrentUser && $currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
                                if ($currentUser->hasRole('Super Admin')) {
                                    $canEditRole = true;
                                } elseif ($currentUser->hasRole('Admin') && !in_array($currentRole, ['Super Admin', 'Admin'])) {
                                    $canEditRole = true;
                                }
                            }

                            $canEditUser = false;
                            if ($currentUser->hasRole('Super Admin')) {
                                $canEditUser = true;
                            } elseif ($currentUser->hasRole('Admin')) {
                                if ($isCurrentUser || !in_array($currentRole, ['Super Admin', 'Admin'])) {
                                    $canEditUser = true;
                                }
                            }

                            $canToggleUser = false;
                            $canDeleteUser = false;
                            if (!$isCurrentUser && $currentUser->hasAnyRole(['Super Admin', 'Admin'])) {
                                if ($currentUser->hasRole('Super Admin')) {
                                    $canToggleUser = true;
                                    $canDeleteUser = true;
                                } elseif ($currentUser->hasRole('Admin') && !in_array($currentRole, ['Super Admin', 'Admin'])) {
                                    $canToggleUser = true;
                                    $canDeleteUser = true;
                                }
                            }
                        @endphp

                        <tr class="transition hover:bg-slate-50/50">
                            <td class="p-4">
                                <div class="flex min-w-[230px] items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}"
                                             alt="{{ $user->fullname }}"
                                             class="h-10 w-10 shrink-0 rounded-full border border-slate-200 object-cover">
                                    @else
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-black uppercase text-indigo-600">
                                            {{ substr($user->fullname ?? $user->email, 0, 1) }}
                                        </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <h4 class="max-w-[220px] truncate font-bold text-slate-900">
                                                {{ $user->fullname ?? 'Chưa cập nhật' }}
                                            </h4>
                                            @if($isCurrentUser)
                                                <span class="rounded bg-indigo-50 px-1.5 py-0.5 text-[10px] font-bold text-indigo-600">Bạn</span>
                                            @endif
                                        </div>
                                        <p class="max-w-[240px] break-all text-[11px] text-slate-400">
                                            {{ $user->email }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="whitespace-nowrap p-4 font-medium text-slate-700">
                                {{ $user->phone_number ?? 'N/A' }}
                            </td>

                            <td class="p-4">
                                @if($canEditRole)
                                    <form action="{{ route('admin.staffs.updateRole', $user->id) }}"
                                          method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('PUT')

                                        <select name="role"
                                                onchange="this.form.submit()"
                                                class="rounded-lg border border-slate-200 bg-slate-50 px-2 py-1 text-[11px] font-semibold focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            @foreach($roles as $role)
                                                <option value="{{ $role->name }}"
                                                    {{ $currentRole == $role->name ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    @if($currentRole === 'Super Admin')
                                        <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700">
                                            <i data-lucide="shield-alert" class="h-3 w-3"></i>
                                            Super Admin
                                        </span>
                                    @elseif($currentRole === 'Admin')
                                        <span class="inline-flex items-center gap-1 rounded-full border border-purple-200 bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">
                                            <i data-lucide="shield-check" class="h-3 w-3"></i>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700">
                                            <i data-lucide="user-check" class="h-3 w-3"></i>
                                            Nhân viên
                                        </span>
                                    @endif
                                @endif
                            </td>

                            <td class="whitespace-nowrap p-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-emerald-100 bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-rose-100 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700"
                                          title="{{ $user->reason_lock }}">
                                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                        Đã khóa
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap p-4 text-[11px] text-slate-400">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.staffs.show', $user->id) }}"
                                       class="rounded-lg p-1.5 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600"
                                       title="Xem chi tiết">
                                        <i data-lucide="eye" class="h-4 w-4"></i>
                                    </a>

                                    @if($canEditUser)
                                        <a href="{{ route('admin.staffs.edit', $user->id) }}"
                                           class="rounded-lg p-1.5 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600"
                                           title="Chỉnh sửa">
                                            <i data-lucide="edit-3" class="h-4 w-4"></i>
                                        </a>
                                    @endif

                                    @if($canToggleUser)
                                        <form action="{{ route('admin.staffs.toggle', $user->id) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('PUT')

                                            @if($user->status === 'active')
                                                <button type="submit"
                                                        class="rounded-lg p-1.5 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                                                        title="Khóa tài khoản"
                                                        onclick="return confirm('Bạn có muốn khóa tài khoản này?')">
                                                    <i data-lucide="lock" class="h-4 w-4"></i>
                                                </button>
                                            @else
                                                <button type="submit"
                                                        class="rounded-lg p-1.5 text-slate-500 transition hover:bg-emerald-50 hover:text-emerald-600"
                                                        title="Mở khóa tài khoản">
                                                    <i data-lucide="unlock" class="h-4 w-4"></i>
                                                </button>
                                            @endif
                                        </form>
                                    @endif

                                    @if($canDeleteUser)
                                        <form action="{{ route('admin.staffs.destroy', $user->id) }}"
                                              method="POST"
                                              class="inline-block"
                                              onsubmit="return confirm('Bạn có chắc muốn chuyển tài khoản này vào thùng rác?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="rounded-lg p-1.5 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600"
                                                    title="Xóa mềm (vào thùng rác)">
                                                <i data-lucide="trash-2" class="h-4 w-4"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Không tìm thấy tài khoản nhân viên nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($staffs->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4">
            <div class="w-full overflow-x-auto">
                {{ $staffs->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
