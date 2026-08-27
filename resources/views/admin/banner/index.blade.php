@extends('layouts.admin')

@section('title', 'Quản lý Banner - Veloce Admin')
@section('page_title', 'Danh sách Banner')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Quản lý Banner quảng cáo</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Tạo mới, sắp xếp vị trí và quản lý hiển thị các Banner trên website</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.banner.trash') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500 shrink-0"></i> 
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.banner.create') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i> 
                <span>Thêm Banner</span>
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
        @forelse($banners as $banner)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="mb-3">
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="w-full h-32 object-cover rounded-xl border border-slate-200 bg-slate-50">
                </div>
                
                <div class="flex flex-col min-w-0 gap-1 mb-4">
                    <h4 class="font-bold text-slate-900 text-sm">{{ $banner->title ?? 'Không có tiêu đề' }}</h4>
                    <p class="text-slate-500 text-[11px]">{{ $banner->subtitle ?? 'N/A' }}</p>
                    @if($banner->link)
                        <a href="{{ $banner->link }}" target="_blank" class="text-[11px] text-indigo-600 hover:underline flex items-center gap-1 mt-1 truncate">
                            <i data-lucide="external-link" class="w-3 h-3 shrink-0"></i> <span class="truncate">{{ $banner->link }}</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Vị trí (Position)</p>
                        <p class="mt-1 font-mono font-bold text-slate-700 text-xs">
                            <span class="px-2 py-0.5 bg-slate-100 rounded-lg">{{ $banner->poisition }}</span>
                        </p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Thứ tự</p>
                        <p class="mt-1 text-xs font-bold text-slate-800">{{ $banner->sort_order }}</p>
                    </div>
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Trạng thái</p>
                        <div class="mt-1">
                            @if($banner->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-semibold">
                                    Ẩn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.banner.edit', $banner->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Chỉnh sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển Banner này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa mềm">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có Banner nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[800px] text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Hình ảnh</th>
                        <th class="p-4">Tiêu đề / Phụ đề</th>
                        <th class="p-4">Vị trí (Position)</th>
                        <th class="p-4">Thứ tự</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($banners as $banner)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="w-24 h-14 object-cover rounded-xl border border-slate-200 bg-slate-50">
                            </td>
                            <td class="p-4">
                                <h4 class="font-bold text-slate-900 text-sm">{{ $banner->title ?? 'Không có tiêu đề' }}</h4>
                                <p class="text-slate-400 text-[11px]">{{ $banner->subtitle ?? 'N/A' }}</p>
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank" class="text-[11px] text-indigo-600 hover:underline flex items-center gap-1 mt-0.5">
                                        <i data-lucide="external-link" class="w-3 h-3"></i> {{ $banner->link }}
                                    </a>
                                @endif
                            </td>
                            <td class="p-4 font-mono font-bold text-slate-700">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg">{{ $banner->poisition }}</span>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                {{ $banner->sort_order }}
                            </td>
                            <td class="p-4">
                                @if($banner->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-semibold">
                                        Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.banner.edit', $banner->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển Banner này vào thùng rác?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Xóa mềm">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">Chưa có Banner nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($banners->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $banners->links() }}
        </div>
    @endif
</div>
@endsection
