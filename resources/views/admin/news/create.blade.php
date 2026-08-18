@extends('layouts.admin')

@section('title', 'Tạo bài viết mới - Veloce Admin')
@section('page_title', 'Tạo bài viết Tin tức mới')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tạo bài viết Tin tức mới</h2>
            <p class="text-sm text-slate-500">Soạn thảo bài viết, tải ảnh đại diện và phân loại tin tức</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        @csrf

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tiêu đề bài viết <span class="text-rose-500">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="VD: Mẫu giày Sneaker hot nhất mùa thu năm nay" 
                class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Danh mục tin tức</label>
                <select name="category_id" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ảnh Thumbnail bài viết</label>
                <input type="file" name="thumbnail" accept="image/*" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nội dung bài viết <span class="text-rose-500">*</span></label>
            <textarea name="content" rows="12" placeholder="Nhập nội dung bài viết tin tức tại đây..." 
                class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('content') }}</textarea>
            @error('content') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái bài viết</label>
                <select name="is_active" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Xuất bản (Active)</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                </select>
                @error('is_active') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="alow_comments" value="1" id="alow_comments" checked class="w-4 h-4 text-indigo-600 rounded">
                <label for="alow_comments" class="text-xs font-bold text-slate-700 cursor-pointer">Cho phép bình luận (allow_comments)</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Đăng bài viết
            </button>
        </div>
    </form>
</div>
@endsection
