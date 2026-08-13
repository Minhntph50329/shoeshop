@extends('layouts.admin')

@section('title', 'Quản lý Banner - Veloce Admin')
@section('page_title', 'Danh sách Banner')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Quản lý Banner quảng cáo</h2>
            <p class="text-sm text-slate-500">Tạo mới, sắp xếp vị trí và quản lý hiển thị các Banner trên website</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.banner.trash') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500"></i> Thùng rác
            </a>
            <a href="{{ route('admin.banner.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Thêm Banner mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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

        @if($banners->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $banners->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
