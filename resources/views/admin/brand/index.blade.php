@extends('layouts.admin')

@section('title', 'Quản lý Thương hiệu')
@section('page_title', 'Danh sách Thương hiệu')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Thương hiệu sản phẩm</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Quản lý các thương hiệu và nhãn hàng sản phẩm</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.brand.trash') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500 shrink-0"></i>
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.brand.create') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                <span>Thêm thương hiệu</span>
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
        @forelse($brands as $brand)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex min-w-0 items-start gap-3">
                    <a href="{{ route('admin.brand.show', $brand->id) }}" class="block shrink-0">
                        @if($brand->logo)
                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-14 w-14 rounded-xl border border-slate-200 bg-slate-50 object-contain p-1">
                        @else
                            <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-100 text-[10px] font-bold text-slate-400 border border-slate-200">No Logo</div>
                        @endif
                    </a>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.brand.show', $brand->id) }}" class="block truncate text-sm font-bold text-slate-800 hover:text-indigo-600 transition">
                            {{ $brand->name }}
                        </a>
                        <p class="mt-0.5 text-[11px] text-slate-400 font-mono">Slug: {{ $brand->slug }}</p>
                        <p class="mt-1 text-xs font-semibold text-slate-700">{{ $brand->products_count ?? 0 }} sản phẩm</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Trạng thái</p>
                        <div class="mt-1">
                            @if($brand->is_active)
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
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Hiển thị</p>
                        <div class="mt-1">
                            @if($brand->is_visible)
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i> Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-md">
                                    <i data-lucide="eye-off" class="w-3.5 h-3.5"></i> Ẩn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.brand.show', $brand->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600" title="Xem">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </a>
                    <a href="{{ route('admin.brand.edit', $brand->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.brand.destroy', $brand->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển thương hiệu này vào thùng rác?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có thương hiệu nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[700px] text-left border-collapse">
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
    </div>
    @if($brands->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection
