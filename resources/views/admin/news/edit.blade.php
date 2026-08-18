@extends('layouts.admin')

@section('title', 'Chỉnh sửa bài viết - Veloce Admin')
@section('page_title', 'Chỉnh sửa bài viết Tin tức')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa bài viết #{{ $post->id }}</h2>
            <p class="text-sm text-slate-500">Cập nhật nội dung bài viết và phân loại tin tức</p>
        </div>
        <a href="{{ route('admin.news.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.news.update', $post->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tiêu đề bài viết <span class="text-rose-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" 
                class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            @error('title') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Danh mục tin tức</label>
                <select name="category_id" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ảnh Thumbnail hiện tại</label>
                @if($post->thumbnail)
                    <div class="mb-2">
                        <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="w-24 h-14 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="w-full py-2 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nội dung bài viết <span class="text-rose-500">*</span></label>
            <textarea name="content" rows="12" 
                class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">{{ old('content', $post->content) }}</textarea>
            @error('content') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-slate-100 pt-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái bài viết</label>
                <select name="is_active" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="1" {{ old('is_active', $post->is_active) ? 'selected' : '' }}>Xuất bản (Active)</option>
                    <option value="0" {{ !old('is_active', $post->is_active) ? 'selected' : '' }}>Bản nháp (Draft)</option>
                </select>
                @error('is_active') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" name="alow_comments" value="1" id="alow_comments" {{ old('alow_comments', $post->alow_comments) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded">
                <label for="alow_comments" class="text-xs font-bold text-slate-700 cursor-pointer">Cho phép bình luận (allow_comments)</label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.news.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Cập nhật bài viết
            </button>
        </div>
    </form>
</div>
@endsection
