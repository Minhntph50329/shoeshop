@extends('layouts.admin')

@section('title', 'Chỉnh sửa Banner - Veloce Admin')
@section('page_title', 'Chỉnh sửa Banner')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa Banner #{{ $banner->id }}</h2>
            <p class="text-sm text-slate-500">Cập nhật thông tin hình ảnh và cấu hình liên kết</p>
        </div>
        <a href="{{ route('admin.banner.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Ảnh Banner hiện tại</label>
            <div class="mb-3">
                <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="w-48 h-28 object-cover rounded-xl border border-slate-200 bg-slate-50">
            </div>
            <input type="file" name="image" accept="image/*" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('image') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tiêu đề (Title)</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phụ đề (Subtitle)</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Liên kết khi click (Link URL)</label>
                <input type="text" name="link" value="{{ old('link', $banner->link) }}" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Vị trí (Position)</label>
                <select name="poisition" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="home_hero" {{ old('poisition', $banner->poisition) == 'home_hero' ? 'selected' : '' }}>Trang chủ - Hero Slider</option>
                    <option value="home_sidebar" {{ old('poisition', $banner->poisition) == 'home_sidebar' ? 'selected' : '' }}>Trang chủ - Sidebar Banner</option>
                    <option value="shop_top" {{ old('poisition', $banner->poisition) == 'shop_top' ? 'selected' : '' }}>Trang Cửa hàng - Top Banner</option>
                </select>
                @error('poisition') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Thứ tự sắp xếp (Sort Order)</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order) }}" min="0" 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                @error('sort_order') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Trạng thái hiển thị</label>
                <select name="is_active" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="1" {{ old('is_active', $banner->is_active) ? 'selected' : '' }}>Hiển thị (Active)</option>
                    <option value="0" {{ !old('is_active', $banner->is_active) ? 'selected' : '' }}>Ẩn (Inactive)</option>
                </select>
                @error('is_active') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.banner.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">Hủy bỏ</a>
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition">
                Cập nhật Banner
            </button>
        </div>
    </form>
</div>
@endsection
