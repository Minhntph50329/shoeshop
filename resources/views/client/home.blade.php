@extends('layouts.app')

@section('title', 'Trang chủ - Veloce ShoeShop')

@section('content')
<div class="space-y-10 sm:space-y-12 lg:space-y-16">

    {{-- HERO BANNER --}}
    @if(isset($banners) && $banners->count() > 0)
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

        <div class="swiper bannerSwiper overflow-hidden rounded-2xl border border-slate-200 bg-slate-900 shadow-sm sm:rounded-3xl">
            <div class="swiper-wrapper">
                @foreach($banners as $banner)
                    <div class="swiper-slide">
                        <div class="relative flex min-h-[360px] items-center overflow-hidden px-5 py-10 sm:min-h-[420px] sm:px-10 md:min-h-[480px] md:px-14 lg:px-16">
                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="absolute inset-0 h-full w-full object-cover object-center transition duration-700">
                            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-slate-900/10"></div>

                            <div class="relative z-10 max-w-xl text-white">
                                @if($banner->poisition)
                                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-indigo-200 backdrop-blur-md">
                                        <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                                        {{ strtoupper($banner->poisition) }}
                                    </span>
                                @endif

                                @if($banner->title)
                                    <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight sm:text-4xl md:text-5xl lg:text-6xl">
                                        {{ $banner->title }}
                                    </h1>
                                @endif

                                @if($banner->subtitle)
                                    <p class="mt-4 max-w-lg text-xs leading-relaxed text-slate-200 sm:text-sm md:text-base">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                <div class="mt-6">
                                    <a href="{{ $banner->link ?? route('shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-900/30 transition hover:bg-indigo-700 active:scale-[0.98] sm:px-6 sm:text-sm">
                                        Khám phá ngay
                                        <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swiper-pagination"></div>
            <div class="swiper-button-next !hidden !h-10 !w-10 !rounded-full !bg-white/10 !text-white !backdrop-blur-md transition hover:!bg-white/20 md:!flex after:!text-sm"></div>
            <div class="swiper-button-prev !hidden !h-10 !w-10 !rounded-full !bg-white/10 !text-white !backdrop-blur-md transition hover:!bg-white/20 md:!flex after:!text-sm"></div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper(".bannerSwiper", {
                    loop: true,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    }
                });
            });
        </script>

    @else
        {{-- FALLBACK HERO --}}
        <div class="relative flex min-h-[360px] items-center overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 px-5 py-10 shadow-sm sm:min-h-[420px] sm:rounded-3xl sm:px-10 md:px-14">
            <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950"></div>

            <div class="relative z-10 max-w-xl text-white">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-indigo-200">
                    <i data-lucide="sparkles" class="h-3.5 w-3.5"></i>
                    Bộ sưu tập Giày 2026
                </span>

                <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight sm:text-4xl md:text-5xl">
                    Đẳng cấp phong cách & bứt phá giới hạn
                </h1>

                <p class="mt-4 max-w-lg text-xs leading-relaxed text-slate-300 sm:text-sm">
                    Khám phá hàng trăm mẫu giày thể thao và thời trang từ những thương hiệu được yêu thích.
                </p>

                <a href="{{ route('shop') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-lg shadow-indigo-950/30 transition hover:bg-indigo-700 active:scale-[0.98]">
                    Mua ngay
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </div>
        </div>
    @endif

    {{-- CATEGORIES --}}
    @if(isset($categories) && $categories->count() > 0)
        <section class="space-y-5 sm:space-y-6">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">Khám phá</p>
                    <h2 class="mt-1 text-xl font-black tracking-tight text-slate-900 sm:text-2xl">Danh mục sản phẩm</h2>
                    <p class="mt-1 text-xs text-slate-500">Khám phá sản phẩm theo từng nhóm phân loại</p>
                </div>

                <a href="{{ route('shop') }}" class="hidden items-center gap-1 text-xs font-bold text-indigo-600 transition hover:text-indigo-700 sm:flex">
                    Xem tất cả
                    <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 md:grid-cols-4 lg:grid-cols-6">
                @foreach($categories as $cat)
                    <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group flex min-h-[130px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-md">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white">
                            @if($cat->icon)
                                <i data-lucide="{{ $cat->icon }}" class="h-5 w-5"></i>
                            @else
                                <i data-lucide="folder" class="h-5 w-5"></i>
                            @endif
                        </div>

                        <h4 class="mt-3 line-clamp-2 text-xs font-bold text-slate-700 transition group-hover:text-indigo-600 sm:text-sm">
                            {{ $cat->name }}
                        </h4>
                    </a>
                @endforeach
            </div>

            <a href="{{ route('shop') }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-xs font-bold text-indigo-600 sm:hidden">
                Xem tất cả danh mục
                <i data-lucide="arrow-right" class="h-4 w-4"></i>
            </a>
        </section>
    @endif

    {{-- BRANDS --}}
    @if(isset($brands) && $brands->count() > 0)
        <section class="space-y-5 sm:space-y-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">Đối tác</p>
                <h2 class="mt-1 text-xl font-black tracking-tight text-slate-900 sm:text-2xl">Thương hiệu đồng hành</h2>
                <p class="mt-1 text-xs text-slate-500">Những thương hiệu được phân phối tại Veloce</p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 md:grid-cols-6 lg:grid-cols-8">
                @foreach($brands as $brand)
                    <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="group flex min-h-[110px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white p-3 text-center shadow-sm transition hover:border-indigo-200 hover:shadow-md">
                        @if($brand->logo)
                            <div class="flex h-12 items-center justify-center">
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="max-h-10 max-w-[90px] object-contain grayscale transition duration-300 group-hover:grayscale-0">
                            </div>
                        @else
                            <div class="flex h-10 items-center justify-center text-xs font-black uppercase tracking-wider text-slate-700 transition group-hover:text-indigo-600">
                                {{ $brand->name }}
                            </div>
                        @endif

                        <span class="mt-2 line-clamp-1 text-[10px] font-semibold text-slate-500 transition group-hover:text-indigo-600">
                            {{ $brand->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- LATEST PRODUCTS --}}
    <section class="space-y-5 sm:space-y-6">
        <div class="flex items-end justify-between gap-4">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">Mới nhất</p>
                <h2 class="mt-1 text-xl font-black tracking-tight text-slate-900 sm:text-2xl">Sản phẩm mới về</h2>
                <p class="mt-1 text-xs text-slate-500">Các mẫu thiết kế mới nhất vừa có mặt tại cửa hàng</p>
            </div>

            <a href="{{ route('shop') }}" class="hidden items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-700 sm:flex">
                Xem tất cả
                <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-6">
            @forelse($latestProducts as $prod)
                <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                    <div>
                        <div class="relative aspect-[4/4.2] overflow-hidden bg-slate-50">
                            @if($prod->image)
                                <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-xs font-medium text-slate-400">No Image</div>
                            @endif

                            @if($prod->discount > 0)
                                <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-white shadow-sm">
                                    Giảm {{ format_price($prod->discount) }}
                                </span>
                            @endif

                            <form action="{{ route('wishlist.toggle', $prod->id) }}" method="POST" class="absolute right-3 top-3">
                                @csrf
                                <button type="submit"
                                    title="{{ in_array($prod->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                    class="flex h-9 w-9 items-center justify-center rounded-full border border-white/40 shadow-md backdrop-blur-sm transition hover:scale-105 {{ in_array($prod->id, $wishlistIds ?? []) ? 'bg-rose-500 text-white hover:bg-rose-600' : 'bg-white/90 text-slate-500 hover:bg-white hover:text-rose-500' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                        fill="{{ in_array($prod->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="space-y-2 p-4 sm:p-5">
                            <div class="flex items-center justify-between gap-2 text-[10px] text-slate-400">
                                <span class="truncate">{{ $prod->brand ? $prod->brand->name : 'N/A' }}</span>
                                <span class="flex shrink-0 items-center gap-1">
                                    <i data-lucide="eye" class="h-3 w-3"></i>
                                    {{ $prod->views }}
                                </span>
                            </div>

                            <h3 class="line-clamp-2 min-h-[40px] text-sm font-bold leading-snug text-slate-800 transition group-hover:text-indigo-600">
                                <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->name }}</a>
                            </h3>
                        </div>
                    </div>

                    <div class="mx-4 flex items-center justify-between border-t border-slate-100 py-4 sm:mx-5">
                        <div class="min-w-0">
                            <div class="text-base font-black text-indigo-600">{{ format_price($prod->final_price) }}</div>
                            @if($prod->discount > 0)
                                <div class="mt-0.5 text-[10px] text-slate-400 line-through">{{ format_price($prod->price) }}</div>
                            @endif
                        </div>

                        <a href="{{ route('products.show', $prod->slug) }}" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-indigo-600 hover:text-white">
                            <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                        </a>
                    </div>
                </div>

            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 bg-white py-12 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-slate-400">
                        <i data-lucide="package-open" class="h-5 w-5"></i>
                    </div>
                    <p class="mt-3 text-xs text-slate-400">Chưa có sản phẩm mới nào.</p>
                </div>
            @endforelse
        </div>

        <a href="{{ route('shop') }}" class="flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-xs font-bold text-indigo-600 sm:hidden">
            Xem tất cả sản phẩm
            <i data-lucide="arrow-right" class="h-4 w-4"></i>
        </a>
    </section>

    {{-- DISCOUNT PRODUCTS --}}
    @if(isset($discountProducts) && $discountProducts->count() > 0)
        <section class="space-y-5 sm:space-y-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-rose-500">Ưu đãi</p>
                <h2 class="mt-1 text-xl font-black tracking-tight text-slate-900 sm:text-2xl">Khuyến mãi đang diễn ra</h2>
                <p class="mt-1 text-xs text-slate-500">Sở hữu những sản phẩm yêu thích với mức giá tốt hơn</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 lg:gap-6">
                @foreach($discountProducts as $prod)
                    <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">
                        <div>
                            <div class="relative aspect-[4/4.2] overflow-hidden bg-slate-50">
                                @if($prod->image)
                                    <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">No Image</div>
                                @endif

                                <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-white shadow-sm">
                                    Sale Off
                                </span>

                                <form action="{{ route('wishlist.toggle', $prod->id) }}" method="POST" class="absolute right-3 top-3">
                                    @csrf
                                    <button type="submit"
                                        title="{{ in_array($prod->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                        class="flex h-9 w-9 items-center justify-center rounded-full border border-white/40 shadow-md backdrop-blur-sm transition hover:scale-105 {{ in_array($prod->id, $wishlistIds ?? []) ? 'bg-rose-500 text-white hover:bg-rose-600' : 'bg-white/90 text-slate-500 hover:bg-white hover:text-rose-500' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                                            fill="{{ in_array($prod->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="space-y-2 p-4 sm:p-5">
                                <div class="flex items-center justify-between gap-2 text-[10px] text-slate-400">
                                    <span class="truncate">{{ $prod->brand ? $prod->brand->name : 'N/A' }}</span>
                                    <span class="flex shrink-0 items-center gap-1">
                                        <i data-lucide="eye" class="h-3 w-3"></i>
                                        {{ $prod->views }}
                                    </span>
                                </div>

                                <h3 class="line-clamp-2 min-h-[40px] text-sm font-bold leading-snug text-slate-800 transition group-hover:text-indigo-600">
                                    <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->name }}</a>
                                </h3>
                            </div>
                        </div>

                        <div class="mx-4 flex items-center justify-between border-t border-slate-100 py-4 sm:mx-5">
                            <div>
                                <div class="text-base font-black text-rose-600">{{ format_price($prod->final_price) }}</div>
                                <div class="mt-0.5 text-[10px] text-slate-400 line-through">{{ format_price($prod->price) }}</div>
                            </div>

                            <a href="{{ route('products.show', $prod->slug) }}" class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600 transition hover:bg-indigo-600 hover:text-white">
                                <i data-lucide="arrow-right" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

</div>
@endsection