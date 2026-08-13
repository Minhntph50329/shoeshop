@extends('layouts.admin')

@section('title', 'Chi tiết Danh mục - Admin')
@section('page_title', 'Chi tiết Danh mục')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                @if($category->icon)
                    <i data-lucide="{{ $category->icon }}" class="w-6 h-6 text-indigo-600"></i>
                @endif
                {{ $category->name }}
            </h2>
            <p class="text-xs text-slate-400 font-mono">Slug: {{ $category->slug }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.category.edit', $category->id) }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.category.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách
            </a>
        </div>
    </div>

    <!-- Category Children & Parent Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-2">
            <span class="text-xs text-slate-400 font-medium">Danh mục cha</span>
            <div class="font-bold text-slate-800 text-sm">
                @if($category->parent)
                    <a href="{{ route('admin.category.show', $category->parent->id) }}" class="hover:text-indigo-600 underline">
                        {{ $category->parent->name }}
                    </a>
                @else
                    <span class="text-slate-400 font-normal italic">(Danh mục gốc)</span>
                @endif
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-2">
            <span class="text-xs text-slate-400 font-medium">Danh mục con</span>
            <div class="flex flex-wrap gap-1">
                @forelse($category->children as $child)
                    <a href="{{ route('admin.category.show', $child->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 rounded-md text-xs font-medium transition">
                        {{ $child->name }}
                    </a>
                @empty
                    <span class="text-slate-400 font-normal italic text-xs">Không có danh mục con</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Product list of this category -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden space-y-4">
        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Sản phẩm thuộc danh mục {{ $category->name }} ({{ $category->products->count() }})</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-6">Ảnh</th>
                        <th class="py-3 px-6">Tên sản phẩm</th>
                        <th class="py-3 px-6">Giá</th>
                        <th class="py-3 px-6">Tồn kho</th>
                        <th class="py-3 px-6 text-right">Chi tiết</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($category->products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-6">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] text-slate-400">No img</div>
                                @endif
                            </td>
                            <td class="py-3 px-6 font-semibold text-slate-800">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="hover:text-indigo-600 transition">
                                    {{ $product->name }}
                                </a>
                            </td>
                            <td class="py-3 px-6 font-bold text-slate-800">{{ number_format($product->final_price) }}đ</td>
                            <td class="py-3 px-6 font-medium text-slate-600">{{ $product->stock }}</td>
                            <td class="py-3 px-6 text-right">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="text-xs text-indigo-600 hover:underline font-semibold">Xem &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Chưa có sản phẩm nào thuộc danh mục này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
