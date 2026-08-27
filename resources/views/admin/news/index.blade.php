@extends('layouts.admin')

@section('title', 'Quản lý Tin tức - Veloce Admin')
@section('page_title', 'Danh sách Bài viết Tin tức')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Quản lý Bài viết & Tin tức</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Đăng bài viết mới, quản lý bài viết, lượt xem và bình luận</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.news.trash') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500 shrink-0"></i> 
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.news.comments') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-amber-50 px-3 py-2.5 text-xs font-bold text-amber-700 border border-amber-200 transition hover:bg-amber-100 sm:px-4">
                <i data-lucide="message-circle" class="w-4 h-4 shrink-0"></i> 
                <span>Bình luận</span>
            </a>
            <a href="{{ route('admin.news.create') }}" class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4 col-span-2 sm:col-span-1">
                <i data-lucide="file-plus" class="w-4 h-4 shrink-0"></i> 
                <span>Tạo bài viết</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Category Quick Add & Filter -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full min-w-0">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề bài viết..." 
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition min-w-0">
                </div>
                <select name="category_id" class="w-full sm:w-48 py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition min-w-0">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition shrink-0">
                    Lọc bài viết
                </button>
            </form>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.news.category.store') }}" method="POST" class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                @csrf
                <div class="w-full relative">
                    <input type="text" name="name" placeholder="Tên danh mục tin mới..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition min-w-0">
                    @error('name') <p class="text-[10px] text-rose-500 mt-1 absolute">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shrink-0 transition mt-4 sm:mt-0">
                    + Thêm DM
                </button>
            </form>
        </div>
    </div>

    <!-- MOBILE CARDS -->
    <div class="space-y-3 md:hidden">
        @forelse($posts as $post)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <div class="flex min-w-0 items-start gap-3">
                    <a href="{{ route('admin.news.edit', $post->id) }}" class="block shrink-0">
                        @if($post->thumbnail)
                            <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="h-16 w-24 rounded-xl border border-slate-200 bg-slate-50 object-cover">
                        @else
                            <div class="flex h-16 w-24 items-center justify-center rounded-xl bg-slate-100 text-[10px] font-bold text-slate-400 border border-slate-200">No Img</div>
                        @endif
                    </a>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.news.edit', $post->id) }}" class="block text-sm font-bold text-slate-800 hover:text-indigo-600 transition leading-snug line-clamp-2">
                            {{ $post->title }}
                        </a>
                        <p class="mt-1 text-[11px] text-slate-400">Đăng bởi: {{ $post->author->fullname ?? 'Admin' }}</p>
                        <p class="text-[11px] text-slate-400">{{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Danh mục</p>
                        <p class="mt-1 truncate text-xs font-semibold text-slate-700 bg-slate-50 px-2 py-0.5 rounded inline-block">
                            {{ $post->category->name ?? 'Chưa phân loại' }}
                        </p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Lượt xem</p>
                        <p class="mt-1 text-xs font-bold text-slate-800 flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400"></i> {{ number_format($post->views) }}
                        </p>
                    </div>
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Trạng thái</p>
                        <div class="mt-1">
                            @if($post->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-semibold border border-slate-200">
                                    Bản nháp / Ẩn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.news.edit', $post->id) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Chỉnh sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.news.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển bài viết này vào thùng rác?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa mềm">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có bài viết tin tức nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[800px] text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Ảnh Thumbnail</th>
                        <th class="p-4">Tiêu đề bài viết</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4">Lượt xem</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($posts as $post)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                @if($post->thumbnail)
                                    <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="w-20 h-12 object-cover rounded-xl border border-slate-200 bg-slate-50">
                                @else
                                    <div class="w-20 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-bold">No Image</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <h4 class="font-bold text-slate-900 text-sm leading-snug line-clamp-1">{{ $post->title }}</h4>
                                <p class="text-slate-400 text-[11px] mt-0.5">Đăng bởi: {{ $post->author->fullname ?? 'Admin' }} | {{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }}</p>
                            </td>
                            <td class="p-4 font-bold text-slate-700">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-700">
                                    {{ $post->category->name ?? 'Chưa phân loại' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold text-slate-800">
                                <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400"></i> {{ number_format($post->views) }}</span>
                            </td>
                            <td class="p-4">
                                @if($post->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-bold border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-semibold border border-slate-200">
                                        Bản nháp / Ẩn
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.news.edit', $post->id) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition" title="Chỉnh sửa">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $post->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có muốn chuyển bài viết này vào thùng rác?')">
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
                            <td colspan="6" class="p-8 text-center text-slate-400">Chưa có bài viết tin tức nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($posts->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
