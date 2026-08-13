@extends('layouts.admin')

@section('title', 'Chỉnh sửa Danh mục')
@section('page_title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa danh mục: {{ $category->name }}</h2>
        <a href="{{ route('admin.category.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.category.update', $category->id) }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tên danh mục <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Danh mục cha</label>
            <select name="parent_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">-- Không có (Danh mục gốc) --</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon Lucide</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
            @error('icon') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-6 border-t border-slate-100 pt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Kích hoạt (Hoạt động)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                Cập nhật danh mục
            </button>
        </div>
    </form>
</div>
@endsection
