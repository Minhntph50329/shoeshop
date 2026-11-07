@extends('layouts.app')

@section('title', 'So sánh sản phẩm - Veloce ShoeShop')

@section('content')
<div class="space-y-4 sm:space-y-6 lg:space-y-7">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 overflow-hidden text-xs text-slate-400">
        <a href="{{ route('home') }}" class="shrink-0 transition hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="h-3 w-3 shrink-0"></i>
        <span class="truncate font-semibold text-slate-700">So sánh sản phẩm</span>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm sm:rounded-3xl">
        <div class="mb-4 flex flex-col items-stretch gap-3 border-b border-slate-100 p-4 pb-4 sm:mb-6 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:p-6 sm:pb-4">
            <div class="flex min-w-0 items-center gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 sm:h-10 sm:w-10">
                    <i data-lucide="bar-chart-2" class="h-5 w-5"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black text-slate-900 sm:text-2xl">So sánh sản phẩm</h1>
                    <p class="mt-1 text-xs leading-relaxed text-slate-500 sm:text-sm">Đối chiếu thông số, phân loại size & màu sắc của các sản phẩm bạn đã chọn</p>
                </div>
            </div>
            
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-700 transition hover:bg-slate-200">
                <i data-lucide="plus" class="h-4 w-4"></i>
                Thêm sản phẩm khác
            </a>
        </div>

        @if(session('success'))
            <div class="mx-4 mt-4 rounded-xl sm:mx-6 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-600">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div class="mx-4 mt-4 rounded-xl sm:mx-6 bg-indigo-50 px-4 py-3 text-sm font-bold text-indigo-600">
                {{ session('info') }}
            </div>
        @endif
        @if(session('warning'))
            <div class="mx-4 mt-4 rounded-xl sm:mx-6 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-600">
                {{ session('warning') }}
            </div>
        @endif

        @if($products->count() > 0)
            <div class="compare-table-wrap overflow-x-auto overscroll-x-contain px-2 pb-4 pt-3 sm:px-5 sm:pb-6">
                <p class="mb-2 flex items-center justify-center gap-1.5 text-[10px] font-medium text-slate-400 sm:hidden"><i data-lucide="arrow-left-right" class="h-3 w-3"></i> Vuốt ngang để xem đầy đủ các sản phẩm</p>
                <table class="w-full min-w-[620px] border-separate border-spacing-0 text-xs sm:min-w-[760px] sm:text-sm">
                    <thead>
                        <tr>
                            <th class="sticky left-0 z-20 w-[105px] min-w-[105px] border-b border-slate-200 bg-slate-50 p-2 text-left text-[9px] font-black uppercase tracking-[0.08em] text-slate-500 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-[10px] sm:tracking-[0.12em]">
                                Tiêu chí
                            </th>
                            @foreach($products as $item)
                                <th class="w-[165px] min-w-[165px] border-b border-slate-200 p-1.5 align-top sm:w-[210px] sm:min-w-[210px] sm:p-3">
                                    <div class="relative overflow-hidden h-full rounded-xl border border-slate-200/80 bg-white p-2 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md sm:rounded-2xl sm:p-3">
                                        {{-- Nút xóa theo key phân loại --}}
                                        <form action="{{ route('compare.remove', $item->key) }}" method="POST" class="absolute -right-2 -top-2 z-10">
                                            @csrf
                                            <button type="submit" aria-label="Xóa sản phẩm" class="flex h-6 w-6 items-center justify-center rounded-full border border-white bg-rose-500 text-white shadow-md transition hover:scale-105 hover:bg-rose-600 mt-5 mr-4">
                                                <i data-lucide="x" class="h-3 w-3"></i>
                                            </button>
                                        </form>

                                        <a href="{{ route('products.show', $item->slug) }}" class="block aspect-square w-full overflow-hidden rounded-lg bg-slate-50 mb-2 sm:mb-3 sm:rounded-xl">
                                            @if($item->image)
                                                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="h-full w-full object-contain p-2 transition duration-300 hover:scale-105">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center text-xs text-slate-400">Không có ảnh</div>
                                            @endif
                                        </a>

                                        <a href="{{ route('products.show', $item->slug) }}" class="block line-clamp-2 min-h-[2rem] px-0.5 text-[11px] font-extrabold leading-4 text-slate-800 transition hover:text-indigo-600 sm:truncate sm:text-sm">
                                            {{ $item->name }}
                                        </a>

                                        {{-- Nhãn phân loại đã chọn --}}
                                        @if($item->variant_label)
                                            <div class="mt-1.5 flex items-center gap-1">
                                                <span class="inline-flex items-center gap-1 rounded-md bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700 border border-indigo-100">
                                                    <i data-lucide="layers" class="h-2.5 w-2.5"></i>
                                                    {{ $item->variant_label }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </th>
                            @endforeach
                            
                            {{-- Fill empty columns up to 4 --}}
                            @for($i = $products->count(); $i < 4; $i++)
                                <th class="w-[165px] min-w-[165px] border-b border-slate-200 p-1.5 sm:w-[210px] sm:min-w-[210px] sm:p-3">
                                    <div class="flex h-full min-h-[174px] flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50/70 text-slate-400 transition hover:border-indigo-200 hover:bg-indigo-50/40">
                                        <i data-lucide="plus" class="mb-2 h-6 w-6"></i>
                                        <span class="text-xs font-semibold">Trống</span>
                                    </div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Thương hiệu --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Thương hiệu</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 text-xs font-semibold leading-snug text-slate-600 sm:p-4 sm:text-sm">{{ $item->brand_name }}</td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>

                        {{-- Giá theo biến thể --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Giá</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 sm:p-4">
                                    <div class="text-base font-black text-indigo-600 sm:text-lg">{{ format_price($item->price) }}</div>
                                    @if($item->discount > 0 && $item->original_price > $item->price)
                                        <div class="text-xs text-slate-400 line-through">{{ format_price($item->original_price) }}</div>
                                    @endif
                                </td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>

                        {{-- Size đã chọn --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Kích thước (Size)</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 text-xs font-bold text-slate-800 sm:p-4 sm:text-sm">
                                    @if($item->size !== '—')
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-black text-slate-800">
                                            {{ $item->size }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>

                        {{-- Màu sắc đã chọn --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Màu sắc</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 text-xs font-bold text-slate-800 sm:p-4 sm:text-sm">
                                    @if($item->color !== '—')
                                        <span class="inline-flex items-center rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700 border border-indigo-100">
                                            {{ $item->color }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>

                        {{-- Chất liệu --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Chất liệu</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 text-xs font-medium leading-snug text-slate-600 sm:p-4 sm:text-sm">{{ $item->material }}</td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>

                        {{-- Tồn kho của biến thể --}}
                        <tr class="group">
                            <td class="sticky left-0 z-10 border-b border-slate-100 bg-slate-50 w-[105px] min-w-[105px] p-2 text-[11px] leading-snug font-extrabold text-slate-700 sm:w-[170px] sm:min-w-[170px] sm:p-4 sm:text-sm">Tình trạng</td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 sm:p-4">
                                    @if($item->stock > 0)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Còn hàng ({{ $item->stock }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                                            Hết hàng
                                        </span>
                                    @endif
                                </td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td class="border-b border-slate-100"></td> @endfor
                        </tr>
                        
                        {{-- Nút Xem chi tiết --}}
                        <tr>
                            <td class="sticky left-0 z-10 bg-slate-50 p-2 sm:p-4"></td>
                            @foreach($products as $item)
                                <td class="border-b border-slate-100 p-2.5 sm:p-4">
                                    <a href="{{ route('products.show', $item->slug) }}" class="flex min-h-10 w-full items-center justify-center gap-1 rounded-lg bg-indigo-600 px-2 py-2 text-[11px] font-bold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md sm:gap-1.5 sm:rounded-xl sm:px-3 sm:py-2.5 sm:text-xs">
                                        <i data-lucide="shopping-bag" class="h-4 w-4"></i>
                                        Xem chi tiết
                                    </a>
                                </td>
                            @endforeach
                            @for($i = $products->count(); $i < 4; $i++) <td></td> @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center px-5 py-12 text-center sm:py-16">
                <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-300">
                    <i data-lucide="bar-chart-2" class="h-8 w-8"></i>
                </div>
                <h3 class="mb-2 text-base font-black text-slate-800 sm:text-lg">Chưa có sản phẩm nào trong danh sách</h3>
                <p class="mb-6 max-w-md text-xs leading-relaxed text-slate-500 sm:text-sm">Vui lòng quay lại cửa hàng để chọn sản phẩm và phân loại bạn muốn so sánh.</p>
                <a href="{{ route('shop') }}" class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-md">
                    Đến cửa hàng ngay
                </a>
            </div>
        @endif
    </div>
</div>

<style>
    .compare-table-wrap {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
        -webkit-overflow-scrolling: touch;
    }

    .compare-table-wrap::-webkit-scrollbar {
        height: 5px;
    }

    .compare-table-wrap::-webkit-scrollbar-track {
        background: transparent;
    }

    .compare-table-wrap::-webkit-scrollbar-thumb {
        border-radius: 9999px;
        background: #cbd5e1;
    }

    @media (max-width: 640px) {
        .compare-table-wrap {
            margin-inline: 0;
        }

        .compare-table-wrap table {
            table-layout: fixed;
        }

        .compare-table-wrap th,
        .compare-table-wrap td {
            overflow-wrap: anywhere;
        }
    }
</style>

@endsection
