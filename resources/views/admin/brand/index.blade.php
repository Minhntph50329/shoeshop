@extends('layouts.admin')

@section('title', 'Quản lý Thương hiệu')
@section('page_title', 'Danh sách Thương hiệu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Thương hiệu sản phẩm</h2>
            <p class="text-sm text-slate-500">Quản lý các thương hiệu và nhãn hàng sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.brand.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                Thùng rác
            </a>
            <a href="{{ route('admin.brand.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Thêm thương hiệu
            </a>
        </div>
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
                        <th class="py-3 px-6">Logo</th>
                        <th class="py-3 px-6">Tên thương hiệu</th>
                        <th class="py-3 px-6">Số sản phẩm</th>
                        <th class="py-3 px-6">Trạng thái</th>
                        <th class="py-3 px-6">Hiển thị</th>
                        <th class="py-3 px-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($brands as $brand)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-medium text-slate-500">#{{ $brand->id }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.brand.show', $brand->id) }}">
                                    @if($brand->logo)
                                        <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-12 object-contain bg-slate-50 p-1 rounded-lg border border-slate-200">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 font-semibold text-xs border border-slate-200">No Logo</div>
                                    @endif
                                </a>
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.brand.show', $brand->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition block">
                                    {{ $brand->name }}
                                </a>
                                <span class="text-xs text-slate-400 font-mono">{{ $brand->slug }}</span>
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ $brand->products_count ?? 0 }} sản phẩm
                            </td>
                            <td class="py-4 px-6">
                                @if($brand->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tắt
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($brand->is_visible)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                                        <i data-lucide="eye" class="w-3.5 h-3.5"></i> Hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md">
                                        <i data-lucide="eye-off" class="w-3.5 h-3.5"></i> Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.brand.show', $brand->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 rounded-lg transition" title="Xem chi tiết">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.brand.edit', $brand->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-lg transition" title="Chỉnh sửa">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.brand.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển thương hiệu này vào thùng rác?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-lg transition" title="Xóa mềm">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">Chưa có thương hiệu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($brands->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
