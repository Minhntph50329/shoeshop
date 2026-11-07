@extends('layouts.admin')

@section('title', 'Thùng rác - Quản lý nhân viên')
@section('page_title', 'Thùng rác - Nhân sự đã xóa')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thùng rác nhân sự & Quản trị</h2>
            <p class="text-sm text-slate-500">Khôi phục lại tài khoản nhân sự hoặc xóa vĩnh viễn khỏi hệ thống</p>
        </div>
        <a href="{{ route('admin.staffs.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách nhân viên
        </a>
    </div>

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

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">ID</th>
                        <th class="p-4">Nhân sự</th>
                        <th class="p-4">Vai trò</th>
                        <th class="p-4">Email / SĐT</th>
                        <th class="p-4">Ngày xóa</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($trashed as $user)
                        @php
                            $currentUser = auth()->user();
                            $targetRole = $user->roles->first()->name ?? $user->role ?? 'Staff';

                            $canAction = false;
                            if ($currentUser->hasRole('Super Admin')) {
                                $canAction = true;
                            } elseif ($currentUser->hasRole('Admin') && !in_array($targetRole, ['Super Admin', 'Admin'])) {
                                $canAction = true;
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4 font-mono text-slate-400">#{{ $user->id }}</td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($user->avatar)
                                        <img src="{{ asset($user->avatar) }}" alt="{{ $user->fullname }}" class="w-9 h-9 rounded-full object-cover border border-slate-200">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-slate-200 text-slate-600 font-black flex items-center justify-center text-xs uppercase">
                                            {{ substr($user->fullname ?? $user->email, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="font-bold text-slate-900">{{ $user->fullname ?? 'Chưa đặt tên' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                @if($targetRole === 'Super Admin')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700">
                                        Super Admin
                                    </span>
                                @elseif($targetRole === 'Admin')
                                    <span class="inline-flex items-center gap-1 rounded-full border border-purple-200 bg-purple-50 px-2.5 py-1 text-[11px] font-bold text-purple-700">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[11px] font-bold text-sky-700">
                                        Nhân viên
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <p class="font-medium text-slate-800">{{ $user->email }}</p>
                                <p class="text-[11px] text-slate-400">{{ $user->phone_number ?? 'N/A' }}</p>
                            </td>
                            <td class="p-4 text-slate-400 text-[11px]">
                                {{ $user->deleted_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="p-4 text-right space-x-2">
                                @if($canAction)
                                    <form action="{{ route('admin.staffs.restore', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold transition inline-flex items-center gap-1">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Khôi phục
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.staffs.forceDelete', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có CHẮC CHẮN muốn XÓA VĨNH VIỄN tài khoản này? Hành động này KHÔNG THỂ khôi phục!')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition inline-flex items-center gap-1">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Xóa vĩnh viễn
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">Không có quyền</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Thùng rác trống. Không có tài khoản nhân sự nào bị xóa.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($trashed->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $trashed->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

