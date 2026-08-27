@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')
@section('page_title', 'Danh sách Danh mục')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Danh mục sản phẩm</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Quản lý các phân loại danh mục sản phẩm (Cha - Con)</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.category.trash') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500 shrink-0"></i>
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.category.create') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                <span>Thêm danh mục</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- MOBILE CARDS -->
    <div class="space-y-3 md:hidden">
        @forelse($categories as $cat)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex min-w-0 items-start gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500 border border-slate-200">
                        @if($cat->icon)
                            <i data-lucide="{{ $cat->icon }}" class="w-5 h-5 text-indigo-500"></i>
                        @else
                            <i data-lucide="folder" class="w-5 h-5"></i>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.category.show', $cat->id) }}" class="block truncate text-sm font-bold text-slate-800 hover:text-indigo-600 transition">
                            {{ $cat->name }}
                        </a>
                        <p class="mt-0.5 text-[11px] text-slate-400 font-mono">Slug: {{ $cat->slug }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Danh mục cha</p>
                        <p class="mt-1 truncate text-xs font-semibold text-slate-700">
                            @if($cat->parent)
                                {{ $cat->parent->name }}
                            @else
                                <span class="italic text-slate-400">(Gốc)</span>
                            @endif
                        </p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Sản phẩm</p>
                        <p class="mt-1 text-xs font-bold text-slate-800">{{ $cat->products_count ?? 0 }}</p>
                    </div>
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Trạng thái</p>
                        <div class="mt-1">
                            @if($cat->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hoạt động
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Tắt
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.category.show', $cat->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600" title="Xem chi tiết">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </a>
                    <a href="{{ route('admin.category.edit', $cat->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Chỉnh sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển danh mục này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa mềm">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có danh mục nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[700px] text-left border-collapse">
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
    </div>
    @if($categories->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $categories->links() }}
        </div>
    @endif
</div>
@endsection
