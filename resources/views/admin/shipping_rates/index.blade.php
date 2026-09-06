@extends('layouts.admin')

@section('title', 'Cấu hình Phí Vận Chuyển - Veloce Admin')
@section('page_title', 'Phí Vận Chuyển')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Bảng Phí Vận Chuyển</h1>
            <p class="text-sm text-slate-500 mt-1">Quản lý và cấu hình phí vận chuyển theo từng vùng miền (Kho hàng: Hà Nội).</p>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-2 shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('admin.shipping-rates.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        @csrf

        {{-- General Settings - giống trang Settings > Shipping --}}
        <div class="px-6 py-5 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="settings" class="w-4 h-4 text-slate-400"></i> Cấu hình chung
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Phí mặc định --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Phí vận chuyển mặc định (VNĐ)</label>
                    <div class="relative">
                        <input type="number" min="0" name="shipping_fee"
                            value="{{ old('shipping_fee', \App\Models\Setting::get('shipping_fee', '30000')) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm pr-8">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">đ</span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1">Phí áp dụng chung cho tất cả các đơn hàng chưa đạt điều kiện miễn phí vận chuyển.</p>
                </div>

                {{-- Miễn phí từ --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Miễn phí vận chuyển từ (VNĐ)</label>
                    <div class="relative">
                        <input type="number" min="0" name="free_shipping_min"
                            value="{{ old('free_shipping_min', \App\Models\Setting::get('free_shipping_min', '500000')) }}"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm pr-8">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">đ</span>
                    </div>
                </div>

                {{-- Thời gian giao hàng --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Thời gian giao hàng dự kiến</label>
                    <input type="text" name="shipping_time"
                        value="{{ old('shipping_time', \App\Models\Setting::get('shipping_time', '2 - 5 ngày')) }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm">
                </div>

                {{-- Cho phép giao toàn quốc --}}
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="allow_national_shipping" value="1" class="sr-only peer"
                            {{ \App\Models\Setting::get('allow_national_shipping', '1') == '1' ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-bold text-slate-700">Cho phép giao hàng toàn quốc</span>
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
                return 0; // default
            }
        @endphp

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-slate-50/80 text-slate-600 font-semibold border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Vùng miền</th>
                        <th class="px-6 py-4">Cùng Tỉnh (Nội tỉnh)</th>
                        <th class="px-6 py-4">Khác Tỉnh (Cùng miền)</th>
                        <th class="px-6 py-4">Khác Miền (Liên miền)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-700">
                    @foreach(['north', 'central', 'south'] as $region)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $regionLabels[$region] }}</div>
                                @if($region == 'north')
                                    <span class="text-[10px] text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded-full mt-1 inline-block">Kho hàng</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <input type="number" name="rates[{{ $region }}][same_province]" 
                                           value="{{ old("rates.$region.same_province", getFee($rates, $region, 'same_province')) }}" 
                                           class="w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                           min="0" required>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">đ</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <input type="number" name="rates[{{ $region }}][different_province]" 
                                           value="{{ old("rates.$region.different_province", getFee($rates, $region, 'different_province')) }}" 
                                           class="w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                           min="0" required>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">đ</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <input type="number" name="rates[{{ $region }}][different_region]" 
                                           value="{{ old("rates.$region.different_region", getFee($rates, $region, 'different_region')) }}" 
                                           class="w-full py-2 pl-3 pr-8 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition"
                                           min="0" required>
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-medium">đ</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 transition flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Lưu cấu hình phí vận chuyển
            </button>
        </div>
    </form>

</div>
@endsection
