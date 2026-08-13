@extends('layouts.admin')

@section('title', 'Chi tiết Thương hiệu - Admin')
@section('page_title', 'Chi tiết Thương hiệu')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            @if($brand->logo)
                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="w-14 h-14 object-contain bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
            @endif
            <div>
                <h2 class="text-xl font-bold text-slate-800">{{ $brand->name }}</h2>
                <p class="text-xs text-slate-400 font-mono">Slug: {{ $brand->slug }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.brand.edit', $brand->id) }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.brand.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách
            </a>
        </div>
    </div>

    <!-- Product list of this brand -->
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden space-y-4">
        <div class="p-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Sản phẩm thuộc thương hiệu {{ $brand->name }} ({{ $brand->products->count() }})</h3>
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
                    @forelse($brand->products as $product)
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
                            <td colspan="5" class="py-8 text-center text-slate-400">Chưa có sản phẩm nào thuộc thương hiệu này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
