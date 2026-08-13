@extends('layouts.admin')

@section('title', 'Chi tiết Sản phẩm - Admin')
@section('page_title', 'Chi tiết Sản phẩm')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <h2 class="text-xl font-bold text-slate-800">{{ $product->name }}</h2>
            @if($product->status == 'active')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Hiển thị</span>
            @elseif($product->status == 'draft')
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Bản nháp</span>
            @else
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">Ẩn</span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Chỉnh sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách
            </a>
        </div>
    </div>

    <!-- Product Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-1">
            <span class="text-xs text-slate-400 font-medium">Mã SKU</span>
            <div class="font-bold font-mono text-slate-800 text-base">{{ $product->sku ?? 'N/A' }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-1">
            <span class="text-xs text-slate-400 font-medium">Giá bán / Giá giảm</span>
            <div class="font-bold text-indigo-600 text-base">
                {{ number_format($product->final_price) }}đ
                @if($product->discount > 0)
                    <span class="text-xs text-slate-400 line-through ml-1">{{ number_format($product->price) }}đ</span>
                @endif
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-1">
            <span class="text-xs text-slate-400 font-medium">Số lượng tồn kho</span>
            <div class="font-bold text-slate-800 text-base">{{ $product->stock }}</div>
        </div>
        <div class="bg-white p-5 rounded-xl border border-slate-100 shadow-sm space-y-1">
            <span class="text-xs text-slate-400 font-medium">Lượt xem</span>
            <div class="font-bold text-slate-800 text-base flex items-center gap-1">
                <i data-lucide="eye" class="w-4 h-4 text-slate-400"></i> {{ number_format($product->views) }}
            </div>
        </div>
    </div>

    <!-- Details Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Left 2 Cols: Images & Description -->
        <div class="md:col-span-2 space-y-6">
            <!-- Main Featured Image & Gallery -->
            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-indigo-600"></i> Hình ảnh sản phẩm
                </h3>
                <div class="h-64 bg-slate-50 rounded-xl overflow-hidden flex items-center justify-center border border-slate-100">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-2">
                    @else
                        <span class="text-slate-400 text-xs">Chưa có ảnh chính</span>
                    @endif
                </div>

                @if(!empty($product->gallery) && is_array($product->gallery))
                    <div class="grid grid-cols-6 gap-2">
                        @foreach($product->gallery as $gImg)
                            <img src="{{ asset($gImg) }}" alt="Gallery" class="w-full h-16 object-cover rounded-lg border border-slate-200">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Description -->
            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Mô tả sản phẩm</h3>
                @if($product->short_description)
                    <div class="p-3 bg-slate-50 rounded-lg text-xs text-slate-600 font-medium">
                        <strong>Mô tả ngắn:</strong> {{ $product->short_description }}
                    </div>
                @endif
                <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                    {{ $product->description ?? 'Chưa có mô tả chi tiết.' }}
                </div>
            </div>

            <!-- Variants List -->
            @if($product->variants->count() > 0)
                <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-indigo-600"></i> Biến thể sản phẩm ({{ $product->variants->count() }})
                    </h3>
                    <div class="divide-y divide-slate-100">
                        @foreach($product->variants as $variant)
                            <div class="py-3 flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-mono font-semibold text-slate-600 mr-2">#{{ $variant->sku }}</span>
                                    <span class="font-bold text-slate-800">
                                        @foreach($variant->attributeValues as $val)
                                            {{ $val->attribute ? $val->attribute->name : '' }}: {{ $val->value }} 
                                        @endforeach
                                    </span>
                                </div>
                                <div class="space-x-3">
                                    <span class="font-bold text-indigo-600">{{ number_format($variant->price ?? $product->price) }}đ</span>
                                    <span class="text-slate-500">Kho: {{ $variant->stock }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Col: Brand & Categories -->
        <div class="space-y-6">
            <!-- Brand Info Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-indigo-600"></i> Thương hiệu
                </h3>
                @if($product->brand)
                    <div class="flex items-center justify-between">
                        <div>
                            <a href="{{ route('admin.brand.show', $product->brand->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition">
                                {{ $product->brand->name }}
                            </a>
                            <p class="text-xs text-slate-400">Slug: {{ $product->brand->slug }}</p>
                        </div>
                        <a href="{{ route('admin.brand.show', $product->brand->id) }}" class="text-xs text-indigo-600 hover:underline font-semibold">Xem chi tiết &rarr;</a>
                    </div>
                @else
                    <p class="text-xs text-slate-400">Chưa chọn thương hiệu.</p>
                @endif
            </div>

            <!-- Categories Info Card -->
            <div class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm space-y-3">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="folder" class="w-4 h-4 text-indigo-600"></i> Danh mục liên kết
                </h3>
                <div class="space-y-2">
                    @forelse($product->categories as $cat)
                        <div class="flex items-center justify-between py-1 border-b border-slate-50 last:border-0">
                            <a href="{{ route('admin.category.show', $cat->id) }}" class="text-xs font-semibold text-slate-700 hover:text-indigo-600 transition">
                                {{ $cat->name }}
                            </a>
                            <a href="{{ route('admin.category.show', $cat->id) }}" class="text-[11px] text-indigo-600 hover:underline">Chi tiết &rarr;</a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Chưa gắn danh mục nào.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
