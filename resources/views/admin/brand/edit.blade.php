@extends('layouts.admin')

@section('title', 'Chỉnh sửa Thương hiệu')
@section('page_title', 'Chỉnh sửa Thương hiệu')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa thương hiệu: {{ $brand->name }}</h2>
        <a href="{{ route('admin.brand.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tên thương hiệu <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            @error('name')
                <div class="auto-hide-error text-rose-500 text-xs mt-1 animate-pulse">
                    <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Logo thương hiệu</label>
            @if($brand->logo)
                <div class="mb-3">
                    <img src="{{ asset($brand->logo) }}" alt="Logo hiện tại" class="w-20 h-20 object-contain bg-slate-50 p-2 rounded-lg border border-slate-200">
                </div>
            @endif
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('logo')
                <div class="auto-hide-error text-rose-500 text-xs mt-1 animate-pulse">
                    <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="flex items-center gap-6 border-t border-slate-100 pt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $brand->is_active ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Kích hoạt (Hoạt động)</span>
            </label>

            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_visible" value="1" {{ $brand->is_visible ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Hiển thị trên giao diện</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                Cập nhật thương hiệu
            </button>
        </div>
    </form>
</div>
@endsection
