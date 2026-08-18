@extends('layouts.admin')

@section('title', 'Thêm Danh mục mới')
@section('page_title', 'Thêm Danh mục')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">Thêm danh mục sản phẩm</h2>
        <a href="{{ route('admin.category.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    <form action="{{ route('admin.category.store') }}" method="POST" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Tên danh mục <span class="text-rose-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="VD: Giày Nam, Giày Thể Thao...">
            @error('name')
                <div class="auto-hide-error text-rose-500 text-xs mt-1 animate-pulse">
                    <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Danh mục cha (nếu có)</label>
            <select name="parent_id" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">-- Không có (Danh mục gốc) --</option>
                @foreach($parentCategories as $parent)
                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                @endforeach
            </select>
            @error('parent_id')
                <div class="auto-hide-error text-rose-500 text-xs mt-1 animate-pulse">
                    <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Icon Lucide (Tùy chọn)</label>
            <input type="text" name="icon" value="{{ old('icon') }}" class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm" placeholder="VD: foot-prints, tag, flame...">
            <p class="text-xs text-slate-400 mt-1">Tên icon từ thư viện Lucide (ví dụ: `footprints`, `tag`, `zap`)</p>
            @error('icon')
                <div class="auto-hide-error text-rose-500 text-xs mt-1 animate-pulse">
                    <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>{{ $message }}
                </div>
            @enderror
        </div>

        <div class="flex items-center gap-6 border-t border-slate-100 pt-6">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-slate-700">Kích hoạt (Hoạt động)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                Lưu danh mục
            </button>
        </div>
    </form>
</div>
@endsection
