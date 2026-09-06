@extends('layouts.admin')

@section('title', 'Cấu hình Phí Vận Chuyển - Veloce Admin')
@section('page_title', 'Phí Vận Chuyển')

@section('content')

<style>
    /* =========================================================
       SHIPPING RATE - RESPONSIVE TABLE
       Desktop: Table
       Mobile: Card
    ========================================================= */

    .shipping-rates-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    /* Mobile */
    @media (max-width: 767px) {

        .shipping-rates-table,
        .shipping-rates-table tbody,
        .shipping-rates-table tr,
        .shipping-rates-table td {
            display: block;
            width: 100%;
        }

        .shipping-rates-table thead {
            display: none;
        }

        .shipping-rates-table tbody {
            padding: 12px;
        }

        .shipping-rates-table tr {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            margin-bottom: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        }

        .shipping-rates-table tr:last-child {
            margin-bottom: 0;
        }

        .shipping-rates-table td {
            padding: 0;
            border: 0;
        }

        /* Region header */
        .shipping-rates-table td:first-child {
            padding: 14px 15px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .shipping-rates-table td:first-child .region-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Fee cells */
        .shipping-rates-table td.fee-cell {
            padding: 13px 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .shipping-rates-table td.fee-cell:last-child {
            border-bottom: 0;
        }

        .shipping-rates-table td.fee-cell::before {
            content: attr(data-label);
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 7px;
        }

        .shipping-rates-table .fee-input {
            width: 100%;
        }

        /* Make the region title a little stronger */
        .shipping-rates-table .region-name {
            font-size: 14px;
        }
    }

    /* Desktop */
    @media (min-width: 768px) {

        .shipping-rates-table tbody tr:hover {
            background: #f8fafc;
        }

        .shipping-rates-table th,
        .shipping-rates-table td {
            vertical-align: middle;
        }

        .shipping-rates-table td.fee-cell {
            min-width: 190px;
        }

        .shipping-rates-table .fee-input {
            min-width: 150px;
        }
    }
</style>

<div class="space-y-5 sm:space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 lg:p-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex items-start gap-3 min-w-0">

                <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                    <i data-lucide="truck" class="w-5 h-5 text-indigo-600"></i>
                </div>

                <div class="min-w-0">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-black text-slate-800 tracking-tight">
                        Bảng Phí Vận Chuyển
                    </h1>

                    <p class="text-xs sm:text-sm text-slate-500 mt-1 leading-relaxed">
                        Quản lý và cấu hình phí vận chuyển theo từng vùng miền.
                    </p>

                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] sm:text-xs font-bold">
                            <i data-lucide="warehouse" class="w-3.5 h-3.5"></i>
                            Kho hàng: Hà Nội
                        </span>
                    </div>
                </div>

            </div>

        </div>

    </div>


    {{-- ALERTS --}}
    @if(session('success'))
        <div class="p-3.5 sm:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs sm:text-sm flex items-start sm:items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 mt-0.5 sm:mt-0"></i>

            <span>
                {{ session('success') }}
            </span>
        </div>
    @endif


    {{-- FORM --}}
    <form action="{{ route('admin.shipping-rates.store') }}"
          method="POST"
          class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        @csrf


        {{-- GENERAL SETTINGS --}}
        <div class="px-4 sm:px-6 py-5 sm:py-6 border-b border-slate-100">

            <div class="flex items-center gap-3 mb-5">

                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                    <i data-lucide="settings" class="w-4 h-4 text-slate-600"></i>
                </div>

                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">
                        Cấu hình chung
                    </h3>

                    <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5">
                        Thiết lập các thông số vận chuyển mặc định
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5 lg:gap-6">

                {{-- PHÍ MẶC ĐỊNH --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">

                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Phí vận chuyển mặc định
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            min="0"
                            name="shipping_fee"
                            value="{{ old('shipping_fee', \App\Models\Setting::get('shipping_fee', '30000')) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm pr-9"
                        >

                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">
                            đ
                        </span>

                    </div>

                    <p class="text-[10px] text-slate-500 mt-2 leading-relaxed">
                        Phí áp dụng chung cho đơn hàng chưa đạt điều kiện miễn phí vận chuyển.
                    </p>

                </div>


                {{-- MIỄN PHÍ TỪ --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">

                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Miễn phí vận chuyển từ
                    </label>

                    <div class="relative">

                        <input
                            type="number"
                            min="0"
                            name="free_shipping_min"
                            value="{{ old('free_shipping_min', \App\Models\Setting::get('free_shipping_min', '500000')) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm pr-9"
                        >

                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold">
                            đ
                        </span>

                    </div>

                    <p class="text-[10px] text-slate-500 mt-2 leading-relaxed">
                        Đơn hàng đạt hoặc vượt mức này sẽ được miễn phí vận chuyển.
                    </p>

                </div>


                {{-- THỜI GIAN GIAO --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">

                    <label class="block text-xs font-bold text-slate-700 mb-2">
                        Thời gian giao hàng dự kiến
                    </label>

                    <input
                        type="text"
                        name="shipping_time"
                        value="{{ old('shipping_time', \App\Models\Setting::get('shipping_time', '2 - 5 ngày')) }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition text-sm"
                    >

                    <p class="text-[10px] text-slate-500 mt-2">
                        Ví dụ: 2 - 5 ngày.
                    </p>

                </div>


                {{-- GIAO TOÀN QUỐC --}}
                <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 flex items-center">

                    <label class="relative inline-flex items-center cursor-pointer">

                        <input
                            type="checkbox"
                            name="allow_national_shipping"
                            value="1"
                            class="sr-only peer"
                            {{ \App\Models\Setting::get('allow_national_shipping', '1') == '1' ? 'checked' : '' }}
                        >

                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600">
                        </div>

                        <div class="ml-3">

                            <span class="block text-sm font-bold text-slate-700">
                                Cho phép giao hàng toàn quốc
                            </span>

                            <span class="block text-[10px] text-slate-400 mt-0.5">
                                Cho phép khách hàng đặt hàng trên toàn quốc.
                            </span>

                        </div>

                    </label>

                </div>

            </div>

        </div>


        @php

            $regionLabels = [
                'north' => 'Miền Bắc',
                'central' => 'Miền Trung',
                'south' => 'Miền Nam',
            ];

            $typeLabels = [
                'same_province' => 'Cùng Tỉnh / Thành phố (Nội tỉnh)',
                'different_province' => 'Khác Tỉnh / Cùng miền (Ngoại tỉnh)',
                'different_region' => 'Khác miền (Liên miền)',
            ];


            // Hàm helper để lấy giá trị hiện tại
            function getFee($rates, $region, $type) {
                if (isset($rates[$region])) {
                    $rate = $rates[$region]->where('type', $type)->first();

                    if ($rate) return $rate->fee;
                }

                return 0;
            }

        @endphp


        {{-- SHIPPING RATES --}}
        <div>

            {{-- SECTION HEADER --}}
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">

                <div class="flex items-start sm:items-center gap-3">

                    <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                        <i data-lucide="map" class="w-4 h-4 text-indigo-600"></i>
                    </div>

                    <div>

                        <h3 class="text-sm sm:text-base font-bold text-slate-800">
                            Phí vận chuyển theo vùng
                        </h3>

                        <p class="text-[10px] sm:text-xs text-slate-400 mt-0.5 leading-relaxed">
                            Thiết lập mức phí dựa trên khoảng cách giữa kho hàng và địa chỉ nhận hàng.
                        </p>

                    </div>

                </div>

            </div>


            {{-- DESKTOP TABLE / MOBILE CARD --}}
            <div class="overflow-hidden">

                <table class="shipping-rates-table w-full text-left text-sm">

                    <thead class="bg-slate-50 text-slate-600 font-semibold border-b border-slate-100">

                        <tr>

                            <th class="px-5 lg:px-6 py-4">
                                Vùng miền
                            </th>

                            <th class="px-5 lg:px-6 py-4">
                                Cùng Tỉnh
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">
                                    Nội tỉnh
                                </span>
                            </th>

                            <th class="px-5 lg:px-6 py-4">
                                Khác Tỉnh
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">
                                    Cùng miền
                                </span>
                            </th>

                            <th class="px-5 lg:px-6 py-4">
                                Khác Miền
                                <span class="block text-[10px] text-slate-400 font-normal mt-0.5">
                                    Liên miền
                                </span>
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100 text-slate-700">

                        @foreach(['north', 'central', 'south'] as $region)

                            <tr class="transition">

                                {{-- VÙNG --}}
                                <td class="px-5 lg:px-6 py-4">

                                    <div class="region-title">

                                        <div>

                                            <div class="flex items-center gap-2">

                                                <div class="w-8 h-8 rounded-lg
                                                    {{ $region == 'north'
                                                        ? 'bg-indigo-50 text-indigo-600'
                                                        : ($region == 'central'
                                                            ? 'bg-orange-50 text-orange-600'
                                                            : 'bg-emerald-50 text-emerald-600') }}
                                                    flex items-center justify-center shrink-0">

                                                    <i
                                                        data-lucide="{{ $region == 'north'
                                                            ? 'mountain'
                                                            : ($region == 'central'
                                                                ? 'sun'
                                                                : 'map-pin') }}"
                                                        class="w-4 h-4">
                                                    </i>

                                                </div>

                                                <div>

                                                    <div class="region-name font-bold text-slate-800">
                                                        {{ $regionLabels[$region] }}
                                                    </div>

                                                    @if($region == 'north')

                                                        <span class="text-[9px] text-indigo-600 font-bold bg-indigo-50 px-2 py-0.5 rounded-full mt-1 inline-block">
                                                            Kho hàng
                                                        </span>

                                                    @else

                                                        <span class="text-[9px] text-slate-400 mt-1 inline-block">
                                                            Khu vực giao hàng
                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- CÙNG TỈNH --}}
                                <td
                                    class="px-5 lg:px-6 py-4 fee-cell"
                                    data-label="Cùng Tỉnh / Thành phố (Nội tỉnh)"
                                >

                                    <div class="relative">

                                        <input
                                            type="number"
                                            name="rates[{{ $region }}][same_province]"
                                            value="{{ old("rates.$region.same_province", getFee($rates, $region, 'same_province')) }}"
                                            class="fee-input w-full py-2.5 pl-3.5 pr-9 border border-slate-200 bg-white rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                            min="0"
                                            required
                                        >

                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">
                                            đ
                                        </span>

                                    </div>

                                </td>


                                {{-- KHÁC TỈNH --}}
                                <td
                                    class="px-5 lg:px-6 py-4 fee-cell"
                                    data-label="Khác Tỉnh / Cùng miền (Ngoại tỉnh)"
                                >

                                    <div class="relative">

                                        <input
                                            type="number"
                                            name="rates[{{ $region }}][different_province]"
                                            value="{{ old("rates.$region.different_province", getFee($rates, $region, 'different_province')) }}"
                                            class="fee-input w-full py-2.5 pl-3.5 pr-9 border border-slate-200 bg-white rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                            min="0"
                                            required
                                        >

                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">
                                            đ
                                        </span>

                                    </div>

                                </td>


                                {{-- KHÁC MIỀN --}}
                                <td
                                    class="px-5 lg:px-6 py-4 fee-cell"
                                    data-label="Khác Miền (Liên miền)"
                                >

                                    <div class="relative">

                                        <input
                                            type="number"
                                            name="rates[{{ $region }}][different_region]"
                                            value="{{ old("rates.$region.different_region", getFee($rates, $region, 'different_region')) }}"
                                            class="fee-input w-full py-2.5 pl-3.5 pr-9 border border-slate-200 bg-white rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                            min="0"
                                            required
                                        >

                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">
                                            đ
                                        </span>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="px-4 sm:px-6 py-4 sm:py-5 bg-slate-50 border-t border-slate-100">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-start gap-2 text-slate-500">

                    <i data-lucide="info" class="w-4 h-4 shrink-0 mt-0.5 text-slate-400"></i>

                    <p class="text-[10px] sm:text-xs leading-relaxed">
                        Kiểm tra lại mức phí trước khi lưu cấu hình.
                    </p>

                </div>


                <button
                    type="submit"
                    class="w-full sm:w-auto px-5 sm:px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl text-xs sm:text-sm font-bold shadow-lg shadow-indigo-200 transition flex items-center justify-center gap-2"
                >

                    <i data-lucide="save" class="w-4 h-4"></i>

                    <span>
                        Lưu cấu hình phí vận chuyển
                    </span>

                </button>

            </div>

        </div>

    </form>

</div>

@endsection