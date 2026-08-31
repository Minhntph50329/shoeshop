@extends('layouts.app')

@section('title', 'Cửa hàng - Veloce ShoeShop')

@section('content')
<div class="space-y-5 sm:space-y-7">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs sm:text-sm text-slate-400">
        <a href="{{ route('home') }}" class="transition hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="font-semibold text-slate-700">Cửa hàng</span>
    </div>

    <!-- Shop Hero -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-slate-900 px-5 py-7 sm:px-8 sm:py-9 shadow-xl shadow-indigo-200/50">
        <div class="absolute -top-20 -right-16 w-56 h-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute -bottom-24 left-1/3 w-72 h-72 rounded-full bg-cyan-300/10 blur-3xl"></div>

        <div class="relative flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[10px] sm:text-xs font-semibold uppercase tracking-[0.18em] text-indigo-100 backdrop-blur">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                    Veloce Collection
                </div>

                <h1 class="mt-4 text-2xl font-black tracking-tight text-white sm:text-4xl">
                    Khám phá phong cách của bạn
                </h1>

                <p class="mt-2 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">
                    Những mẫu giày được tuyển chọn dành cho phong cách năng động và hiện đại.
                </p>
            </div>

            <div class="inline-flex w-fit items-center gap-3 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/15 text-white">
                    <i data-lucide="package-open" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-indigo-200">Sản phẩm</p>
                    <p class="text-lg font-black text-white">{{ $products->total() }} sản phẩm</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Filter Button -->
    <button type="button"
        onclick="document.getElementById('shopFilters').classList.toggle('hidden')"
        class="lg:hidden w-full flex items-center justify-between rounded-2xl border border-indigo-100 bg-white px-4 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-indigo-200 hover:shadow-md">
        <span class="flex items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
            </span>
            Bộ lọc sản phẩm
        </span>
        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400"></i>
    </button>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4 lg:gap-8">

        <!-- SIDEBAR BỘ LỌC -->
        <aside id="shopFilters" class="hidden lg:block lg:sticky lg:top-24 lg:self-start">
            <form action="{{ route('shop') }}" method="GET" class="space-y-4">

                <!-- Search -->
                <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-slate-200/40">
                    <div class="border-b border-slate-100 px-5 py-4">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </span>
                            <h4 class="text-sm font-black text-slate-800">Tìm kiếm</h4>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="relative">
                            <i data-lucide="search" class="absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Nhập tên sản phẩm..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-3 text-xs text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                        </div>
                    </div>
                </div>

                <!-- Category -->
                @if(isset($categories) && $categories->count() > 0)
                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-lg shadow-slate-200/40">
                        <div class="mb-4 flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
                                <i data-lucide="layout-grid" class="w-4 h-4"></i>
                            </span>
                            <h4 class="text-sm font-black text-slate-800">Danh mục</h4>
                        </div>

                        <ul class="max-h-60 space-y-1 overflow-y-auto pr-1 text-xs font-medium">
                            <li>
                                <a href="{{ route('shop', array_merge(request()->query(), ['category' => null])) }}"
                                    class="flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ !request('category') ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                                    <span>Tất cả danh mục</span>
                                    @if(!request('category'))
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </li>

                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->query(), ['category' => $cat->slug])) }}"
                                        class="flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ request('category') == $cat->slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                                        <span class="truncate pr-2">{{ $cat->name }}</span>
                                        <span class="{{ request('category') == $cat->slug ? 'bg-white text-indigo-600' : 'bg-slate-100 text-slate-500' }} min-w-6 rounded-full px-2 py-1 text-center text-[10px]">
                                            {{ $cat->products_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Brand -->
                @if(isset($brands) && $brands->count() > 0)
                    <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-lg shadow-slate-200/40">
                        <div class="mb-4 flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                                <i data-lucide="badge-check" class="w-4 h-4"></i>
                            </span>
                            <h4 class="text-sm font-black text-slate-800">Thương hiệu</h4>
                        </div>

                        <ul class="max-h-60 space-y-1 overflow-y-auto pr-1 text-xs font-medium">
                            <li>
                                <a href="{{ route('shop', array_merge(request()->query(), ['brand' => null])) }}"
                                    class="flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ !request('brand') ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                                    <span>Tất cả thương hiệu</span>
                                    @if(!request('brand'))
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    @endif
                                </a>
                            </li>

                            @foreach($brands as $brand)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->query(), ['brand' => $brand->slug])) }}"
                                        class="flex items-center justify-between rounded-xl px-3 py-2.5 transition {{ request('brand') == $brand->slug ? 'bg-indigo-50 text-indigo-700 font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">
                                        <span class="truncate pr-2">{{ $brand->name }}</span>
                                        <span class="{{ request('brand') == $brand->slug ? 'bg-white text-indigo-600' : 'bg-slate-100 text-slate-500' }} min-w-6 rounded-full px-2 py-1 text-center text-[10px]">
                                            {{ $brand->products_count }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Price -->
                <div class="rounded-3xl border border-slate-100 bg-white p-5 shadow-lg shadow-slate-200/40">
                    <div class="mb-4 flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                            <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                        </span>
                        <h4 class="text-sm font-black text-slate-800">Khoảng giá</h4>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ"
                            class="min-w-0 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến"
                            class="min-w-0 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-xs outline-none transition placeholder:text-slate-400 focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100">
                    </div>

                    <button type="submit"
                        class="mt-3 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-violet-600 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5 hover:shadow-xl active:translate-y-0">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4"></i>
                        Áp dụng bộ lọc
                    </button>

                    @if(request()->anyFilled(['q', 'category', 'brand', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('shop') }}" class="mt-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-rose-500 transition hover:text-rose-600">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                            Xóa tất cả bộ lọc
                        </a>
                    @endif
                </div>
            </form>
        </aside>

        <!-- PRODUCT AREA -->
        <div class="space-y-5 lg:col-span-3">

            <!-- Toolbar -->
            <div class="rounded-3xl border border-slate-100 bg-white p-4 shadow-lg shadow-slate-200/40 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-indigo-500">Danh sách sản phẩm</p>
                        <div class="mt-1 flex items-baseline gap-2">
                            <span class="text-lg font-black text-slate-800">{{ $products->total() }}</span>
                            <span class="text-sm font-medium text-slate-400">sản phẩm đang có</span>
                        </div>
                    </div>

                    <div class="flex w-full items-center gap-2 sm:w-auto">
                        <span class="hidden text-xs font-semibold text-slate-400 sm:inline">Sắp xếp theo</span>
                        <form action="{{ route('shop') }}" method="GET" id="sort_form" class="w-full sm:w-auto">
                            @foreach(request()->except('sort') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach

                            <div class="relative">
                                <i data-lucide="arrow-up-down" class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-indigo-500"></i>
                                <select name="sort" onchange="document.getElementById('sort_form').submit()"
                                    class="w-full appearance-none rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-10 text-xs font-bold text-slate-700 outline-none transition focus:border-indigo-400 focus:bg-white focus:ring-4 focus:ring-indigo-100 sm:w-52">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Lượt xem nhiều nhất</option>
                                </select>
                                <i data-lucide="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                @forelse($products as $prod)
                    <article class="group relative flex min-w-0 flex-col overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg shadow-slate-200/40 transition duration-300 hover:-translate-y-1.5 hover:border-indigo-100 hover:shadow-2xl hover:shadow-indigo-100/70">

                        <!-- Image -->
                        <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-slate-50 to-slate-100">
                            @if($prod->image)
                                <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110">
                            @else
                                <div class="flex h-full w-full flex-col items-center justify-center gap-2 text-slate-400">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                        <i data-lucide="image-off" class="w-6 h-6"></i>
                                    </span>
                                    <span class="text-xs font-semibold">Chưa có hình ảnh</span>
                                </div>
                            @endif

                            <div class="absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-slate-900/25 to-transparent"></div>

                            @if($prod->discount > 0)
                                <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-3 py-1.5 text-[10px] font-black uppercase tracking-wide text-white shadow-lg shadow-rose-200">
                                    Giảm {{ format_price($prod->discount) }}
                                </span>
                            @endif

                            <!-- Wishlist -->
                            <form action="{{ route('wishlist.toggle', $prod->id) }}" method="POST" class="absolute right-3 top-3">
                                                            @csrf

                                                            <button 
                                                                type="submit"
                                                                title="{{ in_array($prod->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                                                class="flex h-9 w-9 items-center justify-center rounded-full shadow-md transition duration-200 hover:scale-105
                                                                {{ in_array($prod->id, $wishlistIds ?? [])
                                                                    ? 'bg-rose-500 text-white hover:bg-rose-600'
                                        : 'bg-white/90 text-slate-400 hover:bg-white hover:text-rose-500' }}">

                                    <svg 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        class="w-4 h-4"
                                        viewBox="0 0 24 24"
                                        fill="{{ in_array($prod->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>

                                </button>
                            </form>

                            <!-- Quick action -->
                            <a href="{{ route('products.show', $prod->slug) }}"
                                class="absolute bottom-3 right-3 flex h-10 w-10 translate-y-2 items-center justify-center rounded-full bg-white text-indigo-600 opacity-0 shadow-lg transition duration-300 group-hover:translate-y-0 group-hover:opacity-100 hover:scale-110">
                                <i data-lucide="arrow-up-right" class="w-4.5 h-4.5"></i>
                            </a>
                        </div>

                        <!-- Details -->
                        <div class="flex flex-1 flex-col p-4 sm:p-5">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <span class="max-w-[70%] truncate rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-indigo-600">
                                    {{ $prod->brand ? $prod->brand->name : 'Veloce' }}
                                </span>

                                <span class="flex shrink-0 items-center gap-1 text-[11px] font-medium text-slate-400">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                    {{ $prod->views }}
                                </span>
                            </div>

                            <h3 class="min-h-[48px] text-sm font-black leading-6 text-slate-800 transition group-hover:text-indigo-600 sm:text-base">
                                <a href="{{ route('products.show', $prod->slug) }}" class="line-clamp-2">
                                    {{ $prod->name }}
                                </a>
                            </h3>

                            <!-- Price -->
                            <div class="mt-5 flex items-end justify-between gap-3 border-t border-slate-100 pt-4">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Giá bán</p>
                                    <div class="mt-1 text-base font-black text-indigo-600 sm:text-lg">
                                        {{ format_price($prod->final_price) }}
                                    </div>

                                    @if($prod->discount > 0)
                                        <div class="mt-0.5 text-[11px] text-slate-400 line-through">
                                            {{ format_price($prod->price) }}
                                        </div>
                                    @endif
                                </div>

                                <a href="{{ route('products.show', $prod->slug) }}"
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-200 transition hover:-translate-y-0.5 hover:bg-indigo-600 hover:shadow-indigo-200"
                                    title="Xem sản phẩm">
                                    <i data-lucide="shopping-bag" class="w-4.5 h-4.5"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-3xl border border-dashed border-slate-200 bg-white px-5 py-16 text-center shadow-sm">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                            <i data-lucide="search-x" class="w-7 h-7"></i>
                        </div>
                        <h3 class="mt-4 text-base font-black text-slate-700">Không tìm thấy sản phẩm</h3>
                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-400">
                            Hãy thử thay đổi từ khóa hoặc xóa một vài điều kiện lọc để xem thêm sản phẩm.
                        </p>
                        <a href="{{ route('shop') }}"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2.5 text-xs font-bold text-indigo-600 transition hover:bg-indigo-100">
                            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                            Xóa bộ lọc tìm kiếm
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="pt-3">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection