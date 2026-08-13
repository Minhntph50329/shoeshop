@extends('layouts.app')

@section('title', $product->name . ' - Veloce ShoeShop')

@section('content')
<div class="space-y-12">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-xs text-slate-400">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="{{ route('shop') }}" class="hover:text-indigo-600">Cửa hàng</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-700 font-semibold truncate max-w-xs">{{ $product->name }}</span>
    </div>

    <!-- Product Main Detail Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 md:p-10 rounded-3xl border border-slate-100 shadow-sm">
        
        <!-- Cột Ảnh sản phẩm (Gallery) -->
        <div class="space-y-4">
            <div class="relative h-[380px] md:h-[450px] bg-slate-50 rounded-2xl overflow-hidden border border-slate-100">
                @if($product->image)
                    <img id="main_detail_image" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 font-medium">Chưa có hình ảnh</div>
                @endif

                @if($product->discount > 0)
                    <span class="absolute top-4 left-4 bg-rose-500 text-white font-bold text-xs px-3 py-1 rounded-full uppercase tracking-wider shadow">
                        Giảm {{ number_format($product->discount) }}đ
                    </span>
                @endif
            </div>

            <!-- Thư viện ảnh nhỏ (Gallery thumbnails) -->
            @if(!empty($product->gallery) && is_array($product->gallery))
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @if($product->image)
                        <button type="button" onclick="document.getElementById('main_detail_image').src = '{{ asset($product->image) }}'" class="w-16 h-16 rounded-xl border border-indigo-500 bg-slate-50 p-1 shrink-0 focus:outline-none">
                            <img src="{{ asset($product->image) }}" alt="Main" class="w-full h-full object-cover rounded-lg">
                        </button>
                    @endif
                    @foreach($product->gallery as $gImg)
                        <button type="button" onclick="document.getElementById('main_detail_image').src = '{{ asset($gImg) }}'" class="w-16 h-16 rounded-xl border border-slate-200 hover:border-indigo-500 bg-slate-50 p-1 shrink-0 transition focus:outline-none">
                            <img src="{{ asset($gImg) }}" alt="Gallery" class="w-full h-full object-cover rounded-lg">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Cột Thông tin & Mua hàng -->
        <div class="space-y-6 flex flex-col justify-between">
            <div class="space-y-4">
                
                <!-- Brand & Categories Badges -->
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->brand)
                        <a href="{{ route('shop', ['brand' => $product->brand->slug]) }}" class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold hover:bg-indigo-100 transition">
                            {{ $product->brand->name }}
                        </a>
                    @endif
                    @foreach($product->categories as $cat)
                        <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-medium hover:bg-slate-200 transition">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                <!-- Product Title -->
                <h1 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight">
                    {{ $product->name }}
                </h1>

                <!-- SKU & Views & Stock -->
                <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 border-b border-slate-100 pb-4">
                    <span>Mã SKU: <strong class="text-slate-700 font-mono">{{ $product->sku ?? 'N/A' }}</strong></span>
                    <span>•</span>
                    <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3.5 h-3.5"></i> {{ number_format($product->views) }} lượt xem</span>
                    <span>•</span>
                    <span class="flex items-center gap-1">
                        <span class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-3.5 h-3.5 {{ $i <= round($product->average_rating) ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </span>
                        <strong class="text-slate-700 font-semibold ml-1">{{ $product->average_rating }}</strong>
                        <span class="text-slate-400">({{ $product->reviews_count }} đánh giá)</span>
                    </span>
                    <span>•</span>
                    <span>Tình trạng: 
                        @if($product->stock > 0)
                            <strong class="text-emerald-600 font-bold">Còn hàng ({{ $product->stock }})</strong>
                        @else
                            <strong class="text-rose-600 font-bold">Hết hàng</strong>
                        @endif
                    </span>
                </div>

                <!-- Price Box -->
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-black text-indigo-600">{{ number_format($product->final_price) }}đ</span>
                    @if($product->discount > 0)
                        <span class="text-base text-slate-400 line-through font-semibold">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>

                <!-- Short Description -->
                @if($product->short_description)
                    <p class="text-sm text-slate-600 leading-relaxed bg-slate-50/70 p-4 rounded-2xl border border-slate-100">
                        {{ $product->short_description }}
                    </p>
                @endif
            </div>

            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4 pt-2">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <!-- Product Variants (Biến thể) -->
                    @if($product->variants && $product->variants->count() > 0)
                        <div class="space-y-3">
                            <label class="block text-xs font-bold uppercase text-slate-800 tracking-wider">Tùy chọn sản phẩm (Biến thể)</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($product->variants as $index => $variant)
                                    <label class="flex flex-col p-3 rounded-xl border border-slate-200 hover:border-indigo-500 cursor-pointer transition">
                                        <div class="flex items-center justify-between">
                                            <input type="radio" name="product_variant_id" value="{{ $variant->id }}" {{ $index === 0 ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                                            <span class="text-[10px] text-slate-400 font-mono">SKU: {{ $variant->sku }}</span>
                                        </div>
                                        <div class="mt-2 text-xs font-bold text-slate-800">
                                            @foreach($variant->attributeValues as $val)
                                                <span class="inline-flex items-center gap-1 mr-1">
                                                    @if($val->color_code)
                                                        <span class="w-2.5 h-2.5 rounded-full inline-block border border-slate-300" style="background-color: {{ $val->color_code }}"></span>
                                                    @endif
                                                    {{ $val->value }}
                                                </span>
                                            @endforeach
                                        </div>
                                        <div class="text-xs font-bold text-indigo-600 mt-1">
                                            {{ number_format($variant->price ?? $product->final_price) }}đ
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-slate-200 rounded-2xl overflow-hidden bg-slate-50 shrink-0">
                            <button type="button" onclick="const input = document.getElementById('prod_qty'); input.value = Math.max(1, parseInt(input.value || 1) - 1);" class="w-10 h-11 flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white transition font-black text-base active:scale-95">
                                -
                            </button>
                            <input type="number" id="prod_qty" name="quantity" value="1" min="1" max="{{ $product->stock > 0 ? $product->stock : 1 }}" 
                                class="w-12 text-center py-2 text-xs font-black text-slate-900 bg-white border-x border-slate-200 focus:outline-none">
                            <button type="button" onclick="const input = document.getElementById('prod_qty'); input.value = parseInt(input.value || 1) + 1;" class="w-10 h-11 flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white transition font-black text-base active:scale-95">
                                +
                            </button>
                        </div>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-2xl transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i> Thêm vào giỏ hàng
                        </button>
                    </div>
                </form>

                {{-- Wishlist Heart Button --}}
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3 px-6 rounded-2xl border-2 font-bold text-sm transition
                            {{ $isWishlisted
                                ? 'border-rose-400 bg-rose-50 text-rose-600 hover:bg-rose-100'
                                : 'border-slate-200 bg-white text-slate-500 hover:border-rose-400 hover:text-rose-500 hover:bg-rose-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                            fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        {{ $isWishlisted ? 'Đã yêu thích — Xóa khỏi danh sách' : 'Thêm vào yêu thích' }}
                    </button>
                </form>

        </div>

    </div>

    <!-- Description Details Tab/Box -->
    @if($product->description)
        <div class="bg-white p-6 md:p-10 rounded-3xl border border-slate-100 shadow-sm space-y-4">
            <h3 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5 text-indigo-600"></i> Mô tả chi tiết sản phẩm
            </h3>
            <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed whitespace-pre-line">
                {!! nl2br(e($product->description)) !!}
            </div>
    @endif

    <!-- Two-column bottom section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Reviews (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Reviews Section -->
            <div class="bg-white p-6 md:p-10 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                <h3 class="text-xl font-bold text-slate-900 border-b border-slate-100 pb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Đánh giá từ khách hàng
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                    {{-- Average Score --}}
                    <div class="text-center space-y-2 md:border-r border-slate-200/60 py-2">
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Đánh giá trung bình</p>
                        <div class="text-5xl font-black text-indigo-600">{{ $product->average_rating }}</div>
                        <div class="flex justify-center text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                            @endfor
                        </div>
                        <p class="text-xs text-slate-400 font-medium">Có tất cả {{ $product->reviews_count }} lượt đánh giá</p>
                    </div>

                    {{-- Rating Breakdown --}}
                    <div class="md:col-span-2 space-y-2.5 px-0 md:px-6">
                        @php
                            $breakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            foreach($product->activeReviews as $rev) {
                                if (isset($breakdown[$rev->rating])) {
                                    $breakdown[$rev->rating]++;
                                }
                            }
                            $totalRevs = $product->reviews_count ?: 1;
                        @endphp
                        @foreach([5, 4, 3, 2, 1] as $star)
                            @php
                                $count = $breakdown[$star];
                                $percent = ($count / $totalRevs) * 100;
                            @endphp
                            <div class="flex items-center gap-3 text-xs">
                                <span class="font-bold text-slate-600 w-10 text-right flex items-center justify-end gap-1">
                                    {{ $star }} <svg class="w-3.5 h-3.5 fill-current text-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                </span>
                                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-slate-400 w-12 font-medium">{{ $count }} ({{ round($percent) }}%)</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Reviews List --}}
                @if($product->activeReviews->count() > 0)
                    <div class="divide-y divide-slate-100 space-y-6 pt-4">
                        @foreach($product->activeReviews->sortByDesc('created_at') as $review)
                            @php
                                $fullname = $review->user->fullname ?? 'Khách hàng';
                                $maskedName = mb_strlen($fullname) > 2 
                                    ? mb_substr($fullname, 0, 1) . str_repeat('*', mb_strlen($fullname) - 2) . mb_substr($fullname, -1)
                                    : mb_substr($fullname, 0, 1) . '*';
                            @endphp
                            <div class="pt-6 first:pt-0 space-y-3">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-700 flex items-center justify-center font-black text-sm border border-indigo-100">
                                            {{ mb_strtoupper(mb_substr($fullname, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h5 class="font-bold text-sm text-slate-800">{{ $maskedName }}</h5>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                <div class="flex text-amber-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                    @endfor
                                                </div>
                                                <span class="text-[10px] text-slate-400">{{ $review->created_at->format('H:i d/m/Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Review Text --}}
                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                    {{ $review->review_text }}
                                </p>

                                {{-- Review Images --}}
                                @if($review->images->count() > 0)
                                    <div class="flex flex-wrap gap-2 pt-1">
                                        @foreach($review->images as $img)
                                            <a href="{{ asset($img->image_path) }}" target="_blank" class="w-16 h-16 rounded-xl overflow-hidden border border-slate-100 hover:border-indigo-500 transition bg-slate-50 shrink-0">
                                                <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Store Replies --}}
                                @if($review->replies->count() > 0)
                                    <div class="mt-3 pl-4 border-l-2 border-indigo-500 space-y-3">
                                        @foreach($review->replies as $reply)
                                            <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50/70 space-y-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs font-black text-indigo-700 flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                                                        Phản hồi từ Cửa hàng
                                                    </span>
                                                    <span class="text-[10px] text-slate-400">{{ $reply->created_at->format('H:i d/m/Y') }}</span>
                                                </div>
                                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                                    {{ $reply->review_text }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-400 text-sm font-medium">
                        Sản phẩm này chưa có đánh giá nào. Hãy mua sản phẩm và viết đánh giá đầu tiên nhé!
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Related Products (1/3 width) -->
        <div class="space-y-6">
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                    <h3 class="text-lg font-bold text-slate-900 border-b border-slate-100 pb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        Sản phẩm liên quan
                    </h3>
                    <div class="space-y-4">
                        @foreach($relatedProducts as $rel)
                            <div class="group flex items-center gap-4 p-2 rounded-2xl hover:bg-slate-50 transition border border-transparent hover:border-slate-100">
                                <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-50 border border-slate-100 shrink-0 relative">
                                    @if($rel->image)
                                        <img src="{{ asset($rel->image) }}" alt="{{ $rel->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 text-[10px]">No Image</div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600 transition truncate">
                                        <a href="{{ route('products.show', $rel->slug) }}">{{ $rel->name }}</a>
                                    </h4>
                                    <div class="font-bold text-indigo-600 text-xs mt-1">{{ number_format($rel->final_price) }}đ</div>
                                </div>
                                
                                {{-- Wishlist Button on related items --}}
                                <form action="{{ route('wishlist.toggle', $rel->id) }}" method="POST" class="shrink-0">
                                    @csrf
                                    <button type="submit"
                                        title="{{ in_array($rel->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                        class="w-7 h-7 rounded-full flex items-center justify-center border shadow-sm transition
                                            {{ in_array($rel->id, $wishlistIds ?? []) 
                                                ? 'bg-rose-500 border-rose-500 text-white hover:bg-rose-600' 
                                                : 'bg-white border-slate-200 text-slate-400 hover:text-rose-500 hover:bg-slate-50' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24"
                                            fill="{{ in_array($rel->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        
    </div>

</div>
@endsection
