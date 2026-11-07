@extends('layouts.app')

@section('title', $product->name . ' - Veloce ShoeShop')

@section('content')
<div class="space-y-8 sm:space-y-10 lg:space-y-12">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 overflow-hidden text-xs text-slate-400">
        <a href="{{ route('home') }}" class="shrink-0 transition hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="h-3 w-3 shrink-0"></i>
        <a href="{{ route('shop') }}" class="shrink-0 transition hover:text-indigo-600">Cửa hàng</a>
        <i data-lucide="chevron-right" class="h-3 w-3 shrink-0"></i>
        <span class="truncate font-semibold text-slate-700">{{ $product->name }}</span>
    </div>

    {{-- PRODUCT DETAIL --}}
    <div class="grid grid-cols-1 gap-6 rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6 lg:grid-cols-2 lg:gap-10 lg:p-8">

        {{-- Gallery --}}
        <div class="space-y-3 sm:space-y-4">
            <div class="relative aspect-square overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                @if($product->image)
                    <img id="main_detail_image" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-contain p-3 transition duration-300 sm:p-5">
                @else
                    <div class="flex h-full w-full items-center justify-center text-sm font-medium text-slate-400">
                        Chưa có hình ảnh
                    </div>
                @endif

                @if($product->discount > 0)
                    <span class="absolute left-3 top-3 rounded-full bg-rose-500 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-white shadow-sm">
                        Giảm {{ format_price($product->discount) }}
                    </span>
                @endif
            </div>

            @if(!empty($product->gallery) && is_array($product->gallery))
                <div class="flex gap-2 overflow-x-auto pb-1 sm:gap-3">
                    @if($product->image)
                        <button type="button"
                            onclick="document.getElementById('main_detail_image').src='{{ asset($product->image) }}'"
                            class="h-14 w-14 shrink-0 rounded-xl border-2 border-indigo-500 bg-slate-50 p-1 sm:h-16 sm:w-16">
                            <img src="{{ asset($product->image) }}" alt="Main" class="h-full w-full rounded-lg object-cover">
                        </button>
                    @endif

                    @foreach($product->gallery as $gImg)
                        <button type="button"
                            onclick="document.getElementById('main_detail_image').src='{{ asset($gImg) }}'"
                            class="h-14 w-14 shrink-0 rounded-xl border border-slate-200 bg-slate-50 p-1 transition hover:border-indigo-500 sm:h-16 sm:w-16">
                            <img src="{{ asset($gImg) }}" alt="Gallery" class="h-full w-full rounded-lg object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Product Information --}}
        <div class="flex flex-col justify-between gap-6">
            <div class="space-y-4">

                {{-- Brand + Categories --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if($product->brand)
                        <a href="{{ route('shop', ['brand' => $product->brand->slug]) }}"
                            class="rounded-full bg-indigo-50 px-3 py-1.5 text-[10px] font-bold text-indigo-700 transition hover:bg-indigo-100">
                            {{ $product->brand->name }}
                        </a>
                    @endif

                    @foreach($product->categories as $cat)
                        <a href="{{ route('shop', ['category' => $cat->slug]) }}"
                            class="rounded-full bg-slate-100 px-3 py-1.5 text-[10px] font-semibold text-slate-600 transition hover:bg-slate-200">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-black leading-tight tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">
                    {{ $product->name }}
                </h1>

                {{-- Stats --}}
                <div class="flex flex-wrap gap-x-4 gap-y-2 border-y border-slate-100 py-3 text-[10px] text-slate-500 sm:text-xs">
                    <span>SKU: <strong class="font-mono text-slate-700">{{ $product->sku ?? 'N/A' }}</strong></span>

                    <span class="flex items-center gap-1">
                        <i data-lucide="eye" class="h-3.5 w-3.5"></i>
                        {{ number_format($product->views) }} lượt xem
                    </span>

                    <span class="flex items-center gap-1">
                        <span class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-3.5 w-3.5 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-slate-200' }}" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            @endfor
                        </span>
                        <strong class="text-slate-700">{{ $product->average_rating }}</strong>
                        <span>({{ $product->reviews_count }})</span>
                    </span>

                    <span>
                        @if($product->stock > 0)
                            <strong class="text-emerald-600">● Còn hàng ({{ $product->stock }})</strong>
                        @else
                            <strong class="text-rose-600">● Hết hàng</strong>
                        @endif
                    </span>
                </div>

                {{-- Price --}}
                <div class="flex flex-wrap items-baseline gap-3">
                    <span class="text-3xl font-black text-indigo-600 sm:text-4xl">
                        {{ format_price($product->final_price) }}
                    </span>

                    @if($product->discount > 0)
                        <span class="text-sm font-semibold text-slate-400 line-through sm:text-base">
                            {{ format_price($product->price) }}
                        </span>
                    @endif
                </div>

                {{-- Short Description --}}
                @if($product->short_description)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 text-xs leading-relaxed text-slate-600 sm:text-sm">
                        {{ $product->short_description }}
                    </div>
                @endif

                {{-- Information Attributes --}}
                @if($product->infoAttributeValues && $product->infoAttributeValues->count() > 0)
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 text-xs sm:text-sm space-y-2">
                        <div class="font-bold text-slate-800 mb-2">Thông tin thêm:</div>
                        @foreach($product->infoAttributeValues->groupBy('attribute_id') as $attrId => $values)
                            @php
                                $attr = $values->first()->attribute;
                            @endphp
                            @if($attr)
                                <div class="flex items-start gap-2">
                                    <span class="font-semibold text-slate-600 w-24 shrink-0">{{ $attr->name }}:</span>
                                    <span class="text-slate-700 font-medium">
                                        {{ $values->pluck('value')->implode(', ') }}
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Add To Cart --}}
            <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                {{-- Variants: Grouped by Attribute --}}
                @if($product->variants && $product->variants->count() > 0)
                    @php
                        // Collect all unique attribute values across variants, grouped by attribute
                        $groupedAttrs = [];
                        foreach ($product->variants as $variant) {
                            foreach ($variant->attributeValues as $val) {
                                $attrName = $val->attribute->name ?? 'Unknown';
                                $attrId   = $val->attribute_id;
                                if (!isset($groupedAttrs[$attrId])) {
                                    $groupedAttrs[$attrId] = [
                                        'name'   => $attrName,
                                        'values' => [],
                                    ];
                                }
                                // Add unique values only
                                $groupedAttrs[$attrId]['values'][$val->id] = $val;
                            }
                        }
                        // Build variant map: combination of selected value IDs -> variant
                        $variantMap = [];
                        foreach ($product->variants as $variant) {
                            $key = $variant->attributeValues->pluck('id')->sort()->implode('-');
                            $variantMap[$key] = $variant;
                        }
                    @endphp

                    <div id="variant_selector" class="space-y-4">
                        @foreach($groupedAttrs as $attrId => $group)
                            @php
                                $isColor = in_array(strtolower($group['name']), ['màu sắc', 'mau sac', 'color', 'colour', 'màu']);
                            @endphp

                            <div class="variant-group">
                                <div class="mb-2 flex items-center gap-2">
                                    <span class="text-xs font-black uppercase tracking-wide text-slate-800">{{ $group['name'] }}</span>
                                    <span id="selected_label_{{ $attrId }}" class="text-xs font-semibold text-indigo-600"></span>
                                </div>

                                <div class="flex flex-wrap items-center gap-2" data-attr-id="{{ $attrId }}">
                                    @foreach($group['values'] as $val)
                                        @if($isColor)
                                            <button type="button"
                                                data-attr-id="{{ $attrId }}"
                                                data-value-id="{{ $val->id }}"
                                                data-label="{{ $val->value }}"
                                                title="{{ $val->value }}"
                                                aria-label="{{ $val->value }}"
                                                class="variant-opt variant-color-opt relative h-7 w-7 shrink-0 rounded-full border-2 border-white shadow-sm ring-1 ring-slate-200 transition duration-150 hover:scale-105 hover:ring-2 hover:ring-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                                @if($val->color_code)
                                                    style="background-color: {{ $val->color_code }}"
                                                @else
                                                    style="background: linear-gradient(135deg, #e2e8f0 50%, #cbd5e1 50%)"
                                                @endif>
                                                <span class="pointer-events-none absolute inset-0 rounded-full opacity-0 ring-1 ring-white"></span>
                                                <span class="variant-color-check pointer-events-none absolute -right-1 -top-1 hidden h-3.5 w-3.5 items-center justify-center rounded-full bg-indigo-600 text-white shadow-sm">
                                                    <i data-lucide="check" class="h-2 w-2"></i>
                                                </span>
                                            </button>
                                        @else
                                            <button type="button"
                                                data-attr-id="{{ $attrId }}"
                                                data-value-id="{{ $val->id }}"
                                                data-label="{{ $val->value }}"
                                                class="variant-opt min-w-[58px] rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 transition duration-150 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                                {{ $val->value }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        {{-- Hidden input that stores the resolved variant ID --}}
                        <input type="hidden" name="product_variant_id" id="selected_variant_id" value="">

                        {{-- Variant info display --}}
                        <div id="variant_info" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/50 px-3.5 py-2.5">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px]">
                                    <span class="font-semibold text-slate-400">SKU <span id="variant_sku" class="ml-1 font-mono font-bold text-slate-700"></span></span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-semibold text-slate-400">Tồn kho <span id="variant_stock" class="ml-1 font-bold text-emerald-600"></span></span>
                                </div>
                                <div id="variant_price" class="shrink-0 text-base font-black text-indigo-600 sm:text-lg"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Variant Data + Selection Logic --}}
                    @php
                        $variantData = $product->variants->load('attributeValues')->mapWithKeys(function($v) use ($product) {
                            return [$v->id => [
                                'id'    => $v->id,
                                'sku'   => $v->sku,
                                'price' => $v->price ?? $product->final_price,
                                'stock' => $v->stock,
                                'image' => $v->image ? asset($v->image) : null,
                                'key'   => $v->attributeValues->pluck('id')->sort()->values()->implode('-'),
                            ]];
                        })->toArray();
                    @endphp
                    <style>
                        #variant_selector .variant-opt.border-indigo-500 {
                            border-color: rgb(99 102 241);
                            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.12);
                        }

                        #variant_selector .variant-opt.border-indigo-500.variant-color-opt {
                            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.18);
                        }

                        #variant_selector .variant-opt.border-indigo-500 .variant-color-check {
                            display: flex;
                        }

                        #variant_selector .variant-color-opt {
                            width: 28px;
                            height: 28px;
                        }
                    </style>

                    <script>
                    (function() {
                        const allVariants = @json($variantData);
                        const selected = {};

                        function formatPrice(p) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(p);
                        }

                        function resolveVariant() {
                            const sortedIds = Object.values(selected).map(Number).sort((a,b) => a-b).join('-');
                            let found = null;
                            for (const v of Object.values(allVariants)) {
                                if (v.key === sortedIds) { found = v; break; }
                            }

                            const infoBox         = document.getElementById('variant_info');
                            const varInput        = document.getElementById('selected_variant_id');
                            const compareVarInput = document.getElementById('compare_variant_id');
                            const mainImg         = document.getElementById('main_detail_image');
                            const addToCartBtn    = document.getElementById('add_to_cart_btn');

                            if (found) {
                                varInput.value = found.id;
                                if (compareVarInput) compareVarInput.value = found.id;
                                document.getElementById('variant_sku').textContent   = found.sku || '—';
                                document.getElementById('variant_stock').textContent = found.stock + ' đôi';
                                document.getElementById('variant_price').textContent = formatPrice(found.price);
                                infoBox.classList.remove('hidden');
                                if (found.image && mainImg) mainImg.src = found.image;
                                
                                if (addToCartBtn) {
                                    if (found.stock > 0) {
                                        addToCartBtn.disabled = false;
                                        addToCartBtn.querySelector('span').textContent = 'Thêm vào giỏ hàng';
                                    } else {
                                        addToCartBtn.disabled = true;
                                        addToCartBtn.querySelector('span').textContent = 'Phân loại này đã hết hàng';
                                    }
                                }
                            } else {
                                varInput.value = '';
                                if (compareVarInput) compareVarInput.value = '';
                                infoBox.classList.add('hidden');
                                if (addToCartBtn) {
                                    addToCartBtn.disabled = true;
                                    addToCartBtn.querySelector('span').textContent = 'Phân loại không tồn tại';
                                }
                            }
                        }

                        document.querySelectorAll('.variant-opt').forEach(btn => {
                            btn.addEventListener('click', function() {
                                const attrId  = this.dataset.attrId;
                                const valueId = this.dataset.valueId;
                                const label   = this.dataset.label;

                                // Deselect siblings
                                document.querySelectorAll(`.variant-opt[data-attr-id="${attrId}"]`).forEach(b => {
                                    b.classList.remove('border-indigo-500', 'bg-indigo-50', 'ring-2', 'ring-indigo-300', 'scale-110');
                                });

                                // Select this
                                this.classList.add('border-indigo-500', 'ring-2', 'ring-indigo-300');

                                // Update label
                                const labelEl = document.getElementById('selected_label_' + attrId);
                                if (labelEl) labelEl.textContent = label;

                                selected[attrId] = parseInt(valueId);
                                resolveVariant();
                            });
                        });

                        // Auto-select first option of each attribute group
                        const attrGroups = {};
                        document.querySelectorAll('.variant-opt').forEach(btn => {
                            const aid = btn.dataset.attrId;
                            if (!attrGroups[aid]) {
                                attrGroups[aid] = btn;
                            }
                        });
                        Object.values(attrGroups).forEach(btn => btn.click());
                    })();
                    </script>
                @endif
{{-- Quantity + Cart --}}
<div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">

    {{-- Quantity --}}
    <div class="flex h-14 w-full items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 sm:w-[140px] sm:shrink-0">

        <button type="button"
            onclick="const input=document.getElementById('prod_qty');input.value=Math.max(1,parseInt(input.value||1)-1);"
            class="flex h-full w-14 items-center justify-center text-lg font-black text-slate-700 transition hover:bg-indigo-600 hover:text-white active:scale-95">
            -
        </button>

        <input type="number"
            id="prod_qty"
            name="quantity"
            value="1"
            min="1"
            max="{{ $product->stock > 0 ? $product->stock : 1 }}"
            class="h-full flex-1 border-x border-slate-200 bg-white text-center text-sm font-black text-slate-900 outline-none">

        <button type="button"
            onclick="const input=document.getElementById('prod_qty');input.value=Math.min({{ $product->stock > 0 ? $product->stock : 1 }},parseInt(input.value||1)+1);"
            class="flex h-full w-14 items-center justify-center text-lg font-black text-slate-700 transition hover:bg-indigo-600 hover:text-white active:scale-95">
            +
        </button>

    </div>

    {{-- Add To Cart --}}
    @if($product->stock > 0)

        <button type="submit"
            id="add_to_cart_btn"
            class="flex min-h-[56px] w-full flex-1 items-center justify-center gap-3 rounded-2xl bg-indigo-600 px-6 text-sm font-black text-white shadow-lg shadow-indigo-600/30 transition duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-600/30 active:translate-y-0 active:scale-[0.99] sm:text-base disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:-translate-y-0 disabled:hover:shadow-none disabled:active:scale-100 disabled:bg-slate-400">

            <i data-lucide="shopping-bag" class="h-5 w-5"></i>

            <span>Thêm vào giỏ hàng</span>

        </button>

    @else

        <button type="button"
            disabled
            class="flex min-h-[56px] w-full flex-1 cursor-not-allowed items-center justify-center gap-3 rounded-2xl bg-slate-200 px-6 text-sm font-black text-slate-500 sm:text-base">

            <i data-lucide="x-circle" class="h-5 w-5"></i>

            <span>Sản phẩm đã hết hàng</span>

        </button>

    @endif

</div>
            </form>

            {{-- Actions: Wishlist & Compare --}}
            <div class="grid grid-cols-2 gap-3">
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border py-3 text-xs font-bold transition
                        {{ $isWishlisted
                            ? 'border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100'
                            : 'border-slate-200 bg-white text-slate-500 hover:border-rose-300 hover:bg-rose-50 hover:text-rose-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                            fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        {{ $isWishlisted ? 'Bỏ Yêu thích' : 'Yêu thích' }}
                    </button>
                </form>

                <form action="{{ route('compare.add', $product->id) }}" method="POST" id="compare_form">
                    @csrf
                    <input type="hidden" name="variant_id" id="compare_variant_id" value="">
                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white py-3 text-xs font-bold text-slate-500 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-600">
                        <i data-lucide="bar-chart-2" class="h-4 w-4"></i>
                        So sánh
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- DESCRIPTION --}}
    @if($product->description)
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
            <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i data-lucide="file-text" class="h-4 w-4"></i>
                </div>
                <h3 class="text-base font-bold text-slate-900 sm:text-lg">Mô tả chi tiết sản phẩm</h3>
            </div>

            <div class="prose prose-slate max-w-none whitespace-pre-line text-sm leading-relaxed text-slate-700">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
    @endif

    {{-- REVIEWS + RELATED PRODUCTS --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">

        {{-- Reviews --}}
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:p-8">
                <div class="mb-6 flex items-center gap-2 border-b border-slate-100 pb-4">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i data-lucide="message-circle" class="h-4 w-4"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 sm:text-lg">Đánh giá từ khách hàng</h3>
                </div>

                {{-- Rating Summary --}}
                <div class="grid grid-cols-1 gap-6 rounded-2xl border border-slate-100 bg-slate-50/70 p-4 sm:p-6 md:grid-cols-3">
                    <div class="space-y-2 text-center md:border-r md:border-slate-200">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Đánh giá trung bình</p>

                        <div class="text-4xl font-black text-indigo-600 sm:text-5xl">
                            {{ $product->average_rating }}
                        </div>

                        <div class="flex justify-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-4 w-4 {{ $i <= round($product->average_rating) ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            @endfor
                        </div>

                        <p class="text-[10px] text-slate-400">
                            {{ $product->reviews_count }} lượt đánh giá
                        </p>
                    </div>

                    {{-- Breakdown --}}
                    <div class="space-y-2.5 md:col-span-2 md:px-4">
                        @php
                            $breakdown = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
                            foreach($product->activeReviews as $rev) {
                                if (isset($breakdown[$rev->rating])) {
                                    $breakdown[$rev->rating]++;
                                }
                            }
                            $totalRevs = $product->reviews_count ?: 1;
                        @endphp

                        @foreach([5,4,3,2,1] as $star)
                            @php
                                $count = $breakdown[$star];
                                $percent = ($count / $totalRevs) * 100;
                            @endphp

                            <div class="flex items-center gap-2 text-[10px] sm:text-xs">
                                <span class="flex w-8 items-center justify-end gap-1 font-bold text-slate-600">
                                    {{ $star }}
                                    <svg class="h-3 w-3 fill-current text-amber-400" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                </span>

                                <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full bg-indigo-600" style="width: {{ $percent }}%"></div>
                                </div>

                                <span class="w-12 text-right text-slate-400">
                                    {{ $count }} ({{ round($percent) }}%)
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Reviews List --}}
                @if($product->activeReviews->count() > 0)
                    <div class="mt-6 divide-y divide-slate-100">
                        @foreach($product->activeReviews->sortByDesc('created_at') as $review)
                            @php
                                $fullname = $review->user->fullname ?? 'Khách hàng';
                                $maskedName = mb_strlen($fullname) > 2
                                    ? mb_substr($fullname, 0, 1) . str_repeat('*', mb_strlen($fullname) - 2) . mb_substr($fullname, -1)
                                    : mb_substr($fullname, 0, 1) . '*';
                            @endphp

                            <div class="space-y-3 py-5 first:pt-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border border-indigo-100 bg-indigo-50 text-xs font-black text-indigo-700">
                                            {{ mb_strtoupper(mb_substr($fullname, 0, 1)) }}
                                        </div>

                                        <div>
                                            <h5 class="text-xs font-bold text-slate-800">{{ $maskedName }}</h5>

                                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                                <div class="flex">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-3.5 w-3.5 {{ $i <= $review->rating ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24">
                                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                                        </svg>
                                                    @endfor
                                                </div>

                                                <span class="text-[10px] text-slate-400">
                                                    {{ $review->created_at->format('H:i d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <p class="text-xs leading-relaxed text-slate-700">
                                    {{ $review->review_text }}
                                </p>

                                @if($review->images->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($review->images as $img)
                                            <a href="{{ asset($img->image_path) }}" target="_blank"
                                                class="h-16 w-16 overflow-hidden rounded-xl border border-slate-100 bg-slate-50 transition hover:border-indigo-400">
                                                <img src="{{ asset($img->image_path) }}" class="h-full w-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                @if($review->replies->count() > 0)
                                    <div class="space-y-3 border-l-2 border-indigo-500 pl-4">
                                        @foreach($review->replies as $reply)
                                            <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-3">
                                                <div class="mb-1 flex items-center justify-between gap-3">
                                                    <span class="text-[10px] font-bold text-indigo-700">Phản hồi từ Cửa hàng</span>
                                                    <span class="text-[9px] text-slate-400">{{ $reply->created_at->format('H:i d/m/Y') }}</span>
                                                </div>

                                                <p class="text-xs leading-relaxed text-slate-700">
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
                    <div class="py-10 text-center text-xs text-slate-400">
                        Sản phẩm này chưa có đánh giá nào.
                    </div>
                @endif
            </div>
        </div>

        {{-- Related Products --}}
        <div class="space-y-6">
            @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="mb-5 flex items-center gap-2 border-b border-slate-100 pb-4">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                            <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">Sản phẩm liên quan</h3>
                    </div>

                    <div class="space-y-3">
                        @foreach($relatedProducts as $rel)
                            <div class="group flex items-center gap-3 rounded-2xl border border-transparent p-2 transition hover:border-slate-100 hover:bg-slate-50">
                                <a href="{{ route('products.show', $rel->slug) }}" class="h-16 w-16 shrink-0 overflow-hidden rounded-xl border border-slate-100 bg-slate-50">
                                    @if($rel->image)
                                        <img src="{{ asset($rel->image) }}" alt="{{ $rel->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-[9px] text-slate-400">No Image</div>
                                    @endif
                                </a>

                                <div class="min-w-0 flex-1">
                                    <h4 class="truncate text-xs font-bold text-slate-800 transition group-hover:text-indigo-600">
                                        <a href="{{ route('products.show', $rel->slug) }}">{{ $rel->name }}</a>
                                    </h4>

                                    <div class="mt-1 text-xs font-black text-indigo-600">
                                        {{ format_price($rel->final_price) }}
                                    </div>
                                </div>

                                {{-- Small Wishlist Button --}}
                                <form action="{{ route('wishlist.toggle', $rel->id) }}" method="POST" class="shrink-0">
                                    @csrf
                                    <button type="submit"
                                        title="{{ in_array($rel->id, $wishlistIds ?? []) ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                        class="flex h-8 w-8 items-center justify-center rounded-full border shadow-sm transition hover:scale-105
                                        {{ in_array($rel->id, $wishlistIds ?? [])
                                            ? 'border-rose-500 bg-rose-500 text-white hover:bg-rose-600'
                                            : 'border-slate-200 bg-white text-slate-400 hover:border-rose-300 hover:text-rose-500' }}">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24"
                                            fill="{{ in_array($rel->id, $wishlistIds ?? []) ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
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

    {{-- Variant Image Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const variantRadios = document.querySelectorAll('input[name="product_variant_id"]');
            const mainImage = document.getElementById('main_detail_image');

            if (variantRadios.length && mainImage) {
                function updateVariantImage() {
                    const checkedRadio = document.querySelector('input[name="product_variant_id"]:checked');

                    if (checkedRadio && checkedRadio.dataset.image) {
                        mainImage.src = checkedRadio.dataset.image;
                    }
                }

                variantRadios.forEach(radio => {
                    radio.addEventListener('change', updateVariantImage);
                });

                updateVariantImage();
            }
        });
    </script>

</div>
@endsection