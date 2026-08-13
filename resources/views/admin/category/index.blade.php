@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')
@section('page_title', 'Danh sách Danh mục')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Danh mục sản phẩm</h2>
            <p class="text-sm text-slate-500">Quản lý các phân loại danh mục sản phẩm (Cha - Con)</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.category.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                Thùng rác
            </a>
            <a href="{{ route('admin.category.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Thêm danh mục
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
                        <th class="py-3 px-6">Tên danh mục</th>
                        <th class="py-3 px-6">Danh mục cha</th>
                        <th class="py-3 px-6">Số sản phẩm</th>
                        <th class="py-3 px-6">Trạng thái</th>
                        <th class="py-3 px-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-medium text-slate-500">#{{ $cat->id }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.category.show', $cat->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition flex items-center gap-2">
                                    @if($cat->icon)
                                        <i data-lucide="{{ $cat->icon }}" class="w-4 h-4 text-indigo-500"></i>
                                    @else
                                        <i data-lucide="folder" class="w-4 h-4 text-slate-400"></i>
                                    @endif
                                    {{ $cat->name }}
                                </a>
                                <span class="text-xs text-slate-400 font-mono">{{ $cat->slug }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($cat->parent)
                                    <a href="{{ route('admin.category.show', $cat->parent->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-md text-xs font-medium transition">
                                        {{ $cat->parent->name }}
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 font-italic">(Gốc)</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-semibold text-slate-700">
                                {{ $cat->products_count ?? 0 }} sản phẩm
                            </td>
                            <td class="py-4 px-6">
                                @if($cat->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tắt
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-1">
                                <a href="{{ route('admin.category.show', $cat->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 rounded-lg transition" title="Xem chi tiết">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.category.edit', $cat->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-lg transition" title="Chỉnh sửa">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển danh mục này vào thùng rác?')">
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
                            <td colspan="6" class="py-8 text-center text-slate-400">Chưa có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
