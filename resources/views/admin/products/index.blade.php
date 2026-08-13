@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')
@section('page_title', 'Danh sách Sản phẩm')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Sản phẩm</h2>
            <p class="text-sm text-slate-500">Quản lý kho hàng, giá cả và thuộc tính sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.trash') }}" class="flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                Thùng rác
            </a>
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Thêm sản phẩm mới
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tìm kiếm & Lọc -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4">
            <div class="relative flex-1 w-full">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm kiếm theo tên sản phẩm hoặc mã SKU..." class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <select name="status" class="w-full sm:w-48 px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn (Hidden)</option>
            </select>

            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-lg text-sm font-semibold transition">
                Lọc sản phẩm
            </button>
        </form>
    </div>

    <!-- Bảng danh sách sản phẩm -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4">Ảnh</th>
                        <th class="py-3 px-4">Tên sản phẩm</th>
                        <th class="py-3 px-4">SKU</th>
                        <th class="py-3 px-4">Giá bán</th>
                        <th class="py-3 px-4">Tồn kho</th>
                        <th class="py-3 px-4">Thương hiệu</th>
                        <th class="py-3 px-4">Lượt xem</th>
                        <th class="py-3 px-4">Trạng thái</th>
                        <th class="py-3 px-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.products.show', $product->id) }}">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded-lg border border-slate-200">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 text-xs font-medium border border-slate-200">No Img</div>
                                    @endif
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="font-bold text-slate-800 hover:text-indigo-600 transition block">
                                    {{ $product->name }}
                                </a>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach($product->categories as $cat)
                                        <a href="{{ route('admin.category.show', $cat->id) }}" class="text-[10px] bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 px-2 py-0.5 rounded transition">
                                            {{ $cat->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $product->sku ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-800">{{ number_format($product->final_price) }}đ</div>
                                @if($product->discount > 0)
                                    <div class="text-xs text-slate-400 line-through">{{ number_format($product->price) }}đ</div>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if($product->stock > 10)
                                    <span class="font-semibold text-emerald-600">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="font-semibold text-amber-600">{{ $product->stock }} (Sắp hết)</span>
                                @else
                                    <span class="font-semibold text-rose-600">Hết hàng</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-600 font-medium">
                                @if($product->brand)
                                    <a href="{{ route('admin.brand.show', $product->brand->id) }}" class="hover:text-indigo-600 underline transition">
                                        {{ $product->brand->name }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-3 px-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <i data-lucide="eye" class="w-3.5 h-3.5 text-slate-400"></i> {{ number_format($product->views) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                @if($product->status == 'active')
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Hiển thị</span>
                                @elseif($product->status == 'draft')
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">Nháp</span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">Ẩn</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right space-x-1">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 rounded-lg transition" title="Xem chi tiết">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-lg transition" title="Chỉnh sửa">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-lg transition" title="Xóa mềm">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">Chưa có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
