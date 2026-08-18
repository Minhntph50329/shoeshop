@extends('layouts.admin')

@section('title', 'Quản lý Tin tức - Veloce Admin')
@section('page_title', 'Danh sách Bài viết Tin tức')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Quản lý Bài viết & Tin tức</h2>
            <p class="text-sm text-slate-500">Đăng bài viết mới, quản lý bài viết, lượt xem và bình luận</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.news.trash') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-slate-500"></i> Thùng rác
            </a>
            <a href="{{ route('admin.news.comments') }}" class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-xl text-xs font-bold border border-amber-200 transition">
                <i data-lucide="message-circle" class="w-4 h-4"></i> Quản lý Bình luận
            </a>
            <a href="{{ route('admin.news.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                <i data-lucide="file-plus" class="w-4 h-4"></i> Tạo bài viết mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Category Quick Add & Filter -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative flex-1 w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề bài viết..." 
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                <select name="category_id" class="w-full sm:w-48 py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition shrink-0">
                    Lọc bài viết
                </button>
            </form>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
            <form action="{{ route('admin.news.category.store') }}" method="POST" class="flex items-center gap-2">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Tên danh mục tin mới..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    @error('name') <p class="text-[10px] text-rose-500 mt-1 absolute">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shrink-0 transition">
                    + Thêm DM
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-[11px] font-semibold">
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

        @if($posts->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
