@extends('layouts.admin')

@section('title', 'Thùng rác - Thương hiệu')
@section('page_title', 'Thùng rác - Thương hiệu')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thương hiệu đã xóa</h2>
            <p class="text-sm text-slate-500">Khôi phục hoặc xóa vĩnh viễn các thương hiệu trong thùng rác</p>
        </div>
        <a href="{{ route('admin.brand.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách thương hiệu
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-6">ID</th>
                        <th class="py-3 px-6">Tên thương hiệu</th>
                        <th class="py-3 px-6">Ngày xóa</th>
                        <th class="py-3 px-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($trashed as $brand)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-medium text-slate-500">#{{ $brand->id }}</td>
                            <td class="py-4 px-6 font-semibold text-slate-800">{{ $brand->name }}</td>
                            <td class="py-4 px-6 text-slate-500 text-xs">{{ $brand->deleted_at->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <form action="{{ route('admin.brand.restore', $brand->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Khôi phục
                                    </button>
                                </form>
                                <form action="{{ route('admin.brand.forceDelete', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa VĨNH VIỄN thương hiệu này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold transition inline-flex items-center gap-1">
                                        <i data-lucide="trash" class="w-3.5 h-3.5"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Thùng rác trống.</td>
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
