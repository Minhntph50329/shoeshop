@extends('layouts.app')

@section('title', 'Cửa hàng - Veloce ShoeShop')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-700 font-semibold">Cửa hàng</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- SIDEBAR BỘ LỌC (BÊN TRÁI) -->
        <aside class="space-y-6">
            <form action="{{ route('shop') }}" method="GET" class="space-y-6">
                
                <!-- Tìm kiếm -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                    <h4 class="text-xs font-bold uppercase text-slate-800 tracking-wider">Tìm kiếm sản phẩm</h4>
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nhập tên sản phẩm..." class="w-full pl-9 pr-3 py-2 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Lọc theo Danh mục -->
                @if(isset($categories) && $categories->count() > 0)
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                        <h4 class="text-xs font-bold uppercase text-slate-800 tracking-wider">Danh mục sản phẩm</h4>
                        <ul class="space-y-1 text-xs font-medium text-slate-600 max-h-60 overflow-y-auto pr-1">
                            <li>
                                <a href="{{ route('shop', array_merge(request()->query(), ['category' => null])) }}" class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 {{ !request('category') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                                    <span>Tất cả danh mục</span>
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->query(), ['category' => $cat->slug])) }}" class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 {{ request('category') == $cat->slug ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $cat->products_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Lọc theo Thương hiệu -->
                @if(isset($brands) && $brands->count() > 0)
                    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                        <h4 class="text-xs font-bold uppercase text-slate-800 tracking-wider">Thương hiệu</h4>
                        <ul class="space-y-1 text-xs font-medium text-slate-600 max-h-60 overflow-y-auto pr-1">
                            <li>
                                <a href="{{ route('shop', array_merge(request()->query(), ['brand' => null])) }}" class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 {{ !request('brand') ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                                    <span>Tất cả thương hiệu</span>
                                </a>
                            </li>
                            @foreach($brands as $brand)
                                <li>
                                    <a href="{{ route('shop', array_merge(request()->query(), ['brand' => $brand->slug])) }}" class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 {{ request('brand') == $brand->slug ? 'text-indigo-600 font-bold bg-indigo-50/50' : '' }}">
                                        <span>{{ $brand->name }}</span>
                                        <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $brand->products_count }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Khoảng giá & Nút Áp dụng -->
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-3">
                    <h4 class="text-xs font-bold uppercase text-slate-800 tracking-wider">Khoảng giá (VNĐ)</h4>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Từ" class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Đến" class="w-full px-2.5 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-sm">
                        Lọc sản phẩm
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'brand', 'min_price', 'max_price', 'sort']))
                        <a href="{{ route('shop') }}" class="block text-center text-xs text-rose-500 hover:underline pt-1">Xóa bộ lọc</a>
                    @endif
                </div>
            </form>
        </aside>

        <!-- DANH SÁCH SẢN PHẨM (BÊN PHẢI) -->
        <div class="lg:col-span-3 space-y-6">
            
            <!-- Header bộ lọc & sắp xếp -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <span class="text-xs font-semibold text-slate-500">
                    Hiển thị <span class="font-bold text-slate-800">{{ $products->total() }}</span> sản phẩm
                </span>

                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400 font-medium">Sắp xếp:</span>
                    <form action="{{ route('shop') }}" method="GET" id="sort_form">
                        @foreach(request()->except('sort') as $key => $val)
                            <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sort_form').submit()" class="px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Lượt xem nhiều nhất</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Grid sản phẩm -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($products as $prod)
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

                                {{-- Wishlist Heart Button (top-right overlay) --}}
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

                            <!-- Details -->
                            <div class="p-5 space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-400">
                                    <span class="font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded text-[10px]">{{ $prod->brand ? $prod->brand->name : 'Veloce' }}</span>
                                    <span class="flex items-center gap-1 text-[11px]"><i data-lucide="eye" class="w-3 h-3"></i> {{ $prod->views }}</span>
                                </div>
                                <h3 class="font-bold text-slate-800 group-hover:text-indigo-600 transition line-clamp-2">
                                    <a href="{{ route('products.show', $prod->slug) }}">{{ $prod->name }}</a>
                                </h3>
                            </div>
                        </div>

                        <!-- Price & Action -->
                        <div class="p-5 pt-0 flex items-center justify-between border-t border-slate-50 mt-2">
                            <div>
                                <div class="font-black text-indigo-600 text-base">{{ number_format($prod->final_price) }}đ</div>
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
                    <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-100 space-y-3">
                        <i data-lucide="search-x" class="w-10 h-10 mx-auto text-slate-300"></i>
                        <p class="text-sm font-semibold text-slate-500">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
                        <a href="{{ route('shop') }}" class="inline-block px-4 py-2 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-100 transition">
                            Xóa bộ lọc tìm kiếm
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            @if($products->hasPages())
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
