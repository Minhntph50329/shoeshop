@extends('layouts.app')

@section('title', 'Trang chủ - Veloce ShoeShop')

@section('content')
<div class="space-y-12">

    <!-- Hero Banner (Dynamic from Admin Banners) -->
    @if(isset($banners) && $banners->count() > 0)
        <div class="space-y-6">
            @foreach($banners as $banner)
                <div class="relative bg-slate-900 rounded-3xl overflow-hidden min-h-[380px] flex items-center p-8 md:p-12 shadow-xl group">
                    <!-- Banner Background Image -->
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950/90 via-slate-900/70 to-transparent z-10"></div>
                    
                    <div class="max-w-xl space-y-5 text-white z-20 relative">
                        @if($banner->poisition)
                            <span class="inline-flex items-center gap-2 bg-indigo-500/30 text-indigo-200 border border-indigo-500/40 font-bold px-3.5 py-1 rounded-full text-xs uppercase tracking-wider backdrop-blur-md">
                                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> {{ strtoupper($banner->poisition) }}
                            </span>
                        @endif

                        @if($banner->title)
                            <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight drop-shadow-md">
                                {{ $banner->title }}
                            </h1>
                        @endif

                        @if($banner->subtitle)
                            <p class="text-sm text-slate-200 leading-relaxed font-medium drop-shadow-sm">
                                {{ $banner->subtitle }}
                            </p>
                        @endif

                        <div class="flex items-center gap-4 pt-2">
                            <a href="{{ $banner->link ?? route('shop') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30 active:scale-95">
                                Khám phá ngay <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Fallback Default Hero Banner -->
        <div class="relative bg-slate-900 rounded-3xl overflow-hidden min-h-[380px] flex items-center p-8 md:p-12 shadow-xl">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-transparent z-10"></div>
            <div class="max-w-xl space-y-5 text-white z-20">
                <span class="inline-flex items-center gap-2 bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-bold px-3.5 py-1 rounded-full text-xs uppercase tracking-wider">
                    <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Bộ sưu tập Giày 2026
                </span>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight">
                    Đẳng cấp Phong cách & Bứt phá Giới hạn
                </h1>
                <p class="text-sm text-slate-300 leading-relaxed">
                    Khám phá hàng trăm mẫu giày thể thao, giày thời trang cao cấp chính hãng từ các thương hiệu hàng đầu thế giới.
                </p>
                <div class="flex items-center gap-4 pt-2">
                    <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition shadow-lg shadow-indigo-600/30">
                        Mua ngay <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Danh mục sản phẩm (Categories Grid) -->
    @if(isset($categories) && $categories->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Danh mục sản phẩm</h2>
                    <p class="text-xs text-slate-500">Khám phá sản phẩm theo từng nhóm phân loại</p>
                </div>
                <a href="{{ route('shop') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                    Xem tất cả <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @foreach($categories as $cat)
                    <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="group bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition text-center space-y-3">
                        <div class="w-12 h-12 mx-auto rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-110 transition">
                            @if($cat->icon)
                                <i data-lucide="{{ $cat->icon }}" class="w-6 h-6"></i>
                            @else
                                <i data-lucide="folder" class="w-6 h-6"></i>
                            @endif
                        </div>
                        <h4 class="font-bold text-sm text-slate-800 group-hover:text-indigo-600 transition">{{ $cat->name }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Thương hiệu nổi bật (Brands Grid) -->
    @if(isset($brands) && $brands->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Thương hiệu đồng hành</h2>
                    <p class="text-xs text-slate-500">Thương hiệu uy tín được phân phối chính hãng</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-4">
                @foreach($brands as $brand)
                    <a href="{{ route('shop', ['brand' => $brand->slug]) }}" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:border-indigo-200 transition flex flex-col items-center justify-center space-y-2">
                        @if($brand->logo)
                            <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" class="h-10 object-contain grayscale group-hover:grayscale-0 transition">
                        @else
                            <div class="h-10 flex items-center justify-center text-slate-700 font-black text-sm uppercase tracking-wider group-hover:text-indigo-600">
                                {{ $brand->name }}
                            </div>
                        @endif
                        <span class="text-[11px] font-semibold text-slate-500 group-hover:text-indigo-600 transition">{{ $brand->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Sản phẩm mới nhất (Latest Products Grid) -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-900">Sản phẩm mới về</h2>
                <p class="text-xs text-slate-500">Các mẫu thiết kế mới nhất vừa có mặt tại cửa hàng</p>
            </div>
            <a href="{{ route('shop') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                Xem tất cả <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($latestProducts as $prod)
                <div class="group bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition flex flex-col justify-between">
                    <div>
                        <!-- Image & Badge -->
                        <div class="relative h-56 bg-slate-50 overflow-hidden">
                            @if($prod->image)
                                <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-semibold">No Image</div>
                            @endif

                            @if($prod->discount > 0)
                                <span class="absolute top-3 left-3 bg-rose-500 text-white font-bold text-[10px] px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                    Giảm {{ number_format($prod->discount) }}đ
                                </span>
                            @endif

                            {{-- Wishlist Heart --}}
                            <form action="{{ route('wishlist.toggle', $prod->id) }}" method="POST" class="absolute top-3 right-3">
                                @csrf
                                <button type="submit"
                                    title="{{ in_array($prod->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                    class="w-9 h-9 rounded-full flex items-center justify-center shadow-md transition backdrop-blur-sm
                                        {{ in_array($prod->id, $wishlistIds ?? [])
                                            ? 'bg-rose-500 text-white hover:bg-rose-600'
                                            : 'bg-white/90 text-slate-400 hover:text-rose-500 hover:bg-white' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                        fill="{{ in_array($prod->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <!-- Info -->
                        <div class="p-5 space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $prod->brand ? $prod->brand->name : 'N/A' }}</span>
                                <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3 h-3"></i> {{ $prod->views }}</span>
                            </div>
                            <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition line-clamp-1">
                                <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->name }}</a>
                            </h3>
                        </div>
                    </div>

                    <!-- Price & Action -->
                    <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-50 mt-2">
                        <div>
                            <div class="font-black text-indigo-600 text-lg">{{ number_format($prod->final_price) }}đ</div>
                            @if($prod->discount > 0)
                                <div class="text-xs text-slate-400 line-through">{{ number_format($prod->price) }}đ</div>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $prod->slug) }}" class="p-2.5 bg-slate-100 hover:bg-indigo-600 text-slate-700 hover:text-white rounded-xl transition">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-12 bg-white rounded-2xl border border-slate-100 text-slate-400">
                    Chưa có sản phẩm mới nào.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Ưu đãi đặc biệt (Discount Products Grid) -->
    @if(isset($discountProducts) && $discountProducts->count() > 0)
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Khuyến mãi đang diễn ra</h2>
                    <p class="text-xs text-slate-500">Sở hữu những món đồ yêu thích với giá tốt nhất</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($discountProducts as $prod)
                    <div class="group bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm hover:shadow-xl transition flex flex-col justify-between">
                        <div>
                            <div class="relative h-56 bg-slate-50 overflow-hidden">
                                @if($prod->image)
                                    <img src="{{ asset($prod->image) }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs font-semibold">No Image</div>
                                @endif
                                <span class="absolute top-3 left-3 bg-amber-500 text-white font-bold text-[10px] px-2.5 py-1 rounded-full uppercase tracking-wider shadow">
                                    Sale Off
                                </span>

                                {{-- Wishlist Heart --}}
                                <form action="{{ route('wishlist.toggle', $prod->id) }}" method="POST" class="absolute top-3 right-3">
                                    @csrf
                                    <button type="submit"
                                        title="{{ in_array($prod->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                        class="w-9 h-9 rounded-full flex items-center justify-center shadow-md transition backdrop-blur-sm
                                            {{ in_array($prod->id, $wishlistIds ?? [])
                                                ? 'bg-rose-500 text-white hover:bg-rose-600'
                                                : 'bg-white/90 text-slate-400 hover:text-rose-500 hover:bg-white' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="{{ in_array($prod->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>

                            <div class="p-5 space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span>{{ $prod->brand ? $prod->brand->name : 'N/A' }}</span>
                                    <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3 h-3"></i> {{ $prod->views }}</span>
                                </div>
                                <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition line-clamp-1">
                                    <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->name }}</a>
                                </h3>
                            </div>
                        </div>

                        <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-50 mt-2">
                            <div>
                                <div class="font-black text-rose-600 text-lg">{{ number_format($prod->final_price) }}đ</div>
                                <div class="text-xs text-slate-400 line-through">{{ number_format($prod->price) }}đ</div>
                            </div>
                            <a href="{{ route('products.show', $prod->slug) }}" class="p-2.5 bg-slate-100 hover:bg-indigo-600 text-slate-700 hover:text-white rounded-xl transition">
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
