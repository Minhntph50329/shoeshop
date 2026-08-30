@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')
@section('page_title', 'Danh sách Sản phẩm')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">

    <!-- Header Actions -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0">
            <h2 class="text-xl font-bold text-slate-800">Sản phẩm</h2>
            <p class="mt-1 text-sm leading-5 text-slate-500">Quản lý kho hàng, giá cả và thuộc tính sản phẩm</p>
        </div>
        <div class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('admin.products.trash') }}"
               class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-slate-100 px-3 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-200 sm:px-4">
                <i data-lucide="trash-2" class="h-4 w-4 shrink-0 text-red-500"></i>
                <span>Thùng rác</span>
            </a>
            <a href="{{ route('admin.products.create') }}"
               class="flex min-w-0 items-center justify-center gap-2 rounded-xl bg-indigo-600 px-3 py-2.5 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:bg-indigo-700 sm:px-4">
                <i data-lucide="plus" class="h-4 w-4 shrink-0"></i>
                <span>Thêm sản phẩm</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="flex items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700">
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filter -->
    <div class="w-full rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4">
        <form action="{{ route('admin.products.index') }}" method="GET"
              class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative min-w-0 sm:col-span-2">
                <i data-lucide="search" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                       placeholder="Tìm kiếm theo tên sản phẩm hoặc mã SKU..."
                       class="w-full min-w-0 pl-9 pr-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div class="min-w-0">
                <select name="status" class="w-full min-w-0 px-3 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hiển thị (Active)</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                    <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn (Hidden)</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                    Lọc sản phẩm
                </button>
            </div>
        </form>
    </div>

    <!-- MOBILE: PRODUCT CARDS -->
    <div class="space-y-3 md:hidden">
        @forelse($products as $product)
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <!-- Product Header -->
                <div class="flex min-w-0 items-start gap-3">
                    <a href="{{ route('admin.products.show', $product->id) }}">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                 class="h-14 w-14 shrink-0 rounded-xl border border-slate-200 object-cover">
                        @else
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-[10px] font-bold text-slate-400 border border-slate-200">No Img</div>
                        @endif
                    </a>
                    <div class="min-w-0 flex-1">
                        <a href="{{ route('admin.products.show', $product->id) }}" class="block truncate text-sm font-bold text-slate-800 hover:text-indigo-600 transition">
                            {{ $product->name }}
                        </a>
                        <p class="mt-0.5 text-[11px] text-slate-400 font-mono">SKU: {{ $product->sku ?? 'N/A' }}</p>
                        <div class="flex flex-wrap gap-1 mt-1">
                            @foreach($product->categories as $cat)
                                <span class="text-[10px] bg-slate-100 text-slate-600 px-2 py-0.5 rounded">{{ $cat->name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Info Grid -->
                <div class="mt-4 grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Giá bán</p>
                        <p class="mt-1 text-xs font-bold text-slate-800">{{ format_price($product->final_price) }}</p>
                        @if($product->discount > 0)
                            <p class="text-[11px] text-slate-400 line-through">{{ format_price($product->price) }}</p>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Tồn kho</p>
                        <p class="mt-1 text-xs font-semibold">
                            @if($product->stock > 10)
                                <span class="text-emerald-600">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="text-amber-600">{{ $product->stock }} (Sắp hết)</span>
                            @else
                                <span class="text-rose-600">Hết hàng</span>
                            @endif
                        </p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Thương hiệu</p>
                        <p class="mt-1 text-xs font-medium text-slate-700">{{ $product->brand->name ?? '-' }}</p>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Trạng thái</p>
                        <div class="mt-1">
                            @if($product->status == 'active')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                </span>
                            @elseif($product->status == 'draft')
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Nháp
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                    <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Ẩn
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.products.show', $product->id) }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600" title="Xem">
                        <i data-lucide="eye" class="h-4 w-4"></i>
                    </a>
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600" title="Sửa">
                        <i data-lucide="edit-3" class="h-4 w-4"></i>
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block"
                          onsubmit="return confirm('Bạn có chắc muốn chuyển sản phẩm này vào thùng rác?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600" title="Xóa">
                            <i data-lucide="trash-2" class="h-4 w-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Chưa có sản phẩm nào.</div>
        @endforelse
    </div>

    <!-- DESKTOP: TABLE -->
    <div class="hidden overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[900px] text-left border-collapse">
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
                                <div class="font-bold text-slate-800">{{ format_price($product->final_price) }}</div>
                                @if($product->discount > 0)
                                    <div class="text-xs text-slate-400 line-through">{{ format_price($product->price) }}</div>
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
                                    @csrf @method('DELETE')
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
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4">
            <div class="w-full overflow-x-auto">
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
