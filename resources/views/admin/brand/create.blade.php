@extends('layouts.admin')

@section('title', 'Thêm Thương hiệu mới')
@section('page_title', 'Thêm Thương hiệu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">Thêm thương hiệu mới</h2>
        <a href="{{ route('admin.brand.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tên thương hiệu <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="VD: Nike, Adidas, Puma...">
            @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Logo thương hiệu</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('logo') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-6 border-t border-slate-100 pt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Kích hoạt (Hoạt động)</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_visible" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Hiển thị trên giao diện</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                Lưu thương hiệu
            </button>
        </div>
    </form>
</div>
@endsection
