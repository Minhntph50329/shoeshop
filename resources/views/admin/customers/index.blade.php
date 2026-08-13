@extends('layouts.admin')

@section('title', 'Quản lý người dùng - Veloce Admin')
@section('page_title', 'Danh sách người dùng')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Quản lý người dùng</h2>
            <p class="text-sm text-slate-500">Quản lý tài khoản, phân quyền, trạng thái và xem chi tiết khách hàng</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.trash') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500"></i> Thùng rác
            </a>
            <a href="{{ route('admin.customers.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Thêm người dùng
            </a>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            <div class="sm:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên, email, sđt..." 
                    class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <select name="role" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Tất cả vai trò --</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin (Quản trị)</option>
                    <option value="client" {{ request('role') == 'client' ? 'selected' : '' }}>Client (Khách hàng)</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <select name="status" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>Đã khóa</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                    Lọc
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Người dùng</th>
                        <th class="p-4">Số điện thoại</th>
                        <th class="p-4">Vai trò</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Ngày tạo</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($customers as $user)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->fullname }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-sm uppercase">
                                            {{ substr($user->fullname ?? $user->email, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-slate-900">{{ $user->fullname ?? 'Chưa cập nhật' }}</h4>
                                        <p class="text-slate-400 text-[11px]">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 font-medium text-slate-700">
                                {{ $user->phone_number ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full text-[11px] font-bold border border-purple-100">
                                        <i data-lucide="shield-check" class="w-3 h-3"></i> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full text-[11px] font-semibold">
                                        <i data-lucide="user" class="w-3 h-3"></i> Khách hàng
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($user->status === 'active')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 rounded-full text-[11px] font-bold border border-rose-100" title="{{ $user->reason_lock }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đã khóa
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-slate-400 text-[11px]">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.customers.show', $user->id) }}" class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Xem chi tiết">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.customers.edit', $user->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    
                                    <!-- Toggle Lock Form -->
                                    <form action="{{ route('admin.customers.toggle', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        @if($user->status === 'active')
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Khóa tài khoản" onclick="return confirm('Bạn có muốn khóa tài khoản này?')">
                                                <i data-lucide="lock" class="w-4 h-4"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition" title="Mở khóa tài khoản">
                                                <i data-lucide="unlock" class="w-4 h-4"></i>
                                            </button>
                                        @endif
                                    </form>

                                    <!-- Soft Delete Form -->
                                    <form action="{{ route('admin.customers.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển tài khoản này vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa mềm (vào thùng rác)">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Không tìm thấy người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
