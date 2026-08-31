@extends('layouts.admin')

@section('title', 'Admin Dashboard - Veloce')
@section('page_title', 'Tổng quan hệ thống')

@section('content')
<div class="space-y-5">

    {{-- THÔNG BÁO NHANH --}}
    @if($pendingOrders > 0 || $pendingRefunds > 0 || $newReviews > 0 || $pendingContacts > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        @if($pendingOrders > 0)
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-3 p-3.5 bg-amber-50 border border-amber-200 rounded-xl hover:shadow-md transition group">
            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-amber-800 truncate">{{ $pendingOrders }} đơn chờ xác nhận</p>
                <p class="text-[10px] text-amber-600 group-hover:underline">Xem ngay →</p>
            </div>
        </a>
        @endif

        @if($pendingRefunds > 0)
        <a href="{{ route('admin.refunds.index') }}"
           class="flex items-center gap-3 p-3.5 bg-purple-50 border border-purple-200 rounded-xl hover:shadow-md transition group">
            <div class="w-9 h-9 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-purple-600"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-purple-800 truncate">{{ $pendingRefunds }} yêu cầu trả hàng</p>
                <p class="text-[10px] text-purple-600 group-hover:underline">Xử lý ngay →</p>
            </div>
        </a>
        @endif

        @if($newReviews > 0)
        <a href="{{ route('admin.reviews.index') }}"
           class="flex items-center gap-3 p-3.5 bg-sky-50 border border-sky-200 rounded-xl hover:shadow-md transition group">
            <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center shrink-0">
                <i data-lucide="star" class="w-4 h-4 text-sky-600"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-sky-800 truncate">{{ $newReviews }} đánh giá mới</p>
                <p class="text-[10px] text-sky-600 group-hover:underline">Xem đánh giá →</p>
            </div>
        </a>
        @endif

        @if($pendingContacts > 0)
        <a href="{{ route('admin.contacts.index') }}"
           class="flex items-center gap-3 p-3.5 bg-rose-50 border border-rose-200 rounded-xl hover:shadow-md transition group">
            <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <i data-lucide="mail" class="w-4 h-4 text-rose-600"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-bold text-rose-800 truncate">{{ $pendingContacts }} liên hệ chờ phản hồi</p>
                <p class="text-[10px] text-rose-600 group-hover:underline">Trả lời ngay →</p>
            </div>
        </a>
        @endif
    </div>
    @endif

    {{-- 4 THẺ THỐNG KÊ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

        {{-- DOANH THU --}}
        <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden hover:shadow-md transition">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-emerald-50 rounded-full -translate-y-5 translate-x-5"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center mb-2.5">
                    <i data-lucide="trending-up" class="w-4 h-4 text-emerald-600"></i>
                </div>
                <p class="text-[9px] sm:text-[10px] font-semibold text-slate-400 uppercase tracking-wide">
                    Doanh thu tháng {{ date('m/Y') }}
                </p>
                <h3 class="text-lg sm:text-xl font-black text-slate-800 mt-1 truncate">
                    {{ format_price($revenueThisMonth) }}
                </h3>
            </div>
        </div>

        {{-- ĐƠN HÀNG --}}
        <a href="{{ route('admin.orders.index') }}"
           class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden hover:shadow-md transition">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-indigo-50 rounded-full -translate-y-5 translate-x-5"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center mb-2.5">
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-indigo-600"></i>
                </div>
                <p class="text-[9px] sm:text-[10px] font-semibold text-slate-400 uppercase tracking-wide">
                    Đơn hàng tháng này
                </p>
                <h3 class="text-lg sm:text-xl font-black text-indigo-600 mt-1">
                    {{ $ordersThisMonth }} đơn
                </h3>
            </div>
        </a>

        {{-- KHÁCH HÀNG --}}
        <a href="{{ route('admin.customers.index') }}"
           class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden hover:shadow-md transition">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-sky-50 rounded-full -translate-y-5 translate-x-5"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-lg bg-sky-100 flex items-center justify-center mb-2.5">
                    <i data-lucide="users" class="w-4 h-4 text-sky-600"></i>
                </div>
                <p class="text-[9px] sm:text-[10px] font-semibold text-slate-400 uppercase tracking-wide">
                    Tổng khách hàng
                </p>
                <h3 class="text-lg sm:text-xl font-black text-slate-800 mt-1">
                    {{ number_format($totalCustomers) }}
                </h3>
            </div>
        </a>

        {{-- SẮP HẾT HÀNG --}}
        <a href="{{ route('admin.products.index') }}"
           class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm relative overflow-hidden hover:shadow-md transition">
            <div class="absolute top-0 right-0 w-16 h-16 sm:w-20 sm:h-20 bg-rose-50 rounded-full -translate-y-5 translate-x-5"></div>
            <div class="relative">
                <div class="w-9 h-9 rounded-lg bg-rose-100 flex items-center justify-center mb-2.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                </div>
                <p class="text-[9px] sm:text-[10px] font-semibold text-slate-400 uppercase tracking-wide">
                    Sắp hết hàng
                </p>
                <h3 class="text-lg sm:text-xl font-black text-rose-600 mt-1">
                    {{ $lowStockCount }} sản phẩm
                </h3>
            </div>
        </a>
    </div>

    {{-- BIỂU ĐỒ HÀNG 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- DOANH THU 12 THÁNG --}}
        <div class="lg:col-span-2 bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Doanh thu 12 tháng gần nhất</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tổng doanh thu đơn hàng đã hoàn tất</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-indigo-600"></i>
                </div>
            </div>
            <div class="h-56 sm:h-64 lg:h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- TRẠNG THÁI ĐƠN HÀNG --}}
        <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Trạng thái đơn hàng</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Phân bố đơn hàng hiện tại</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-emerald-600"></i>
                </div>
            </div>
            <div class="h-56 sm:h-64 lg:h-72 flex items-center justify-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- BIỂU ĐỒ SỐ LƯỢNG ĐƠN HÀNG --}}
    <div class="bg-white p-4 sm:p-5 rounded-xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-bold text-slate-800">Số lượng đơn hàng 12 tháng gần nhất</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Theo dõi xu hướng số đơn hàng theo từng tháng</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                <i data-lucide="chart-line" class="w-4 h-4 text-violet-600"></i>
            </div>
        </div>
        <div class="h-56 sm:h-64">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>

    {{-- TOP SẢN PHẨM & ĐƠN HÀNG --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- TOP SẢN PHẨM --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i data-lucide="trophy" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Top 10 sản phẩm bán chạy</h3>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline whitespace-nowrap">
                    Xem tất cả →
                </a>
            </div>


            {{-- TOP SẢN PHẨM --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

            @php
                $maxSold = $topProducts->max('total_sold') ?: 1;
            @endphp

            {{-- DANH SÁCH --}}
            <div class="divide-y divide-slate-100">

                @forelse($topProducts as $index => $item)

                    @php
                        $percent = min(
                            100,
                            round(($item->total_sold / $maxSold) * 100)
                        );
                    @endphp

                    <div class="px-4 sm:px-5 py-3 hover:bg-slate-50/70 transition">

                        <div class="flex items-center gap-3">

                            {{-- THỨ HẠNG --}}
                            <div
                                class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-[10px] font-black
                                {{ $index == 0
                                    ? 'bg-amber-100 text-amber-700'
                                    : ($index == 1
                                        ? 'bg-slate-200 text-slate-600'
                                        : ($index == 2
                                            ? 'bg-orange-100 text-orange-700'
                                            : 'bg-slate-100 text-slate-500')) }}">
                                {{ $index + 1 }}
                            </div>

                            {{-- ẢNH --}}
                            <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 shrink-0">

                                @if($item->product && $item->product->image)
                                    <img
                                        src="{{ asset($item->product->image) }}"
                                        class="w-full h-full object-cover"
                                        alt="{{ $item->product->name }}"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="package" class="w-4 h-4"></i>
                                    </div>
                                @endif

                            </div>

                            {{-- THÔNG TIN --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex items-center justify-between gap-3">

                                    <div class="min-w-0">

                                        <p class="text-xs font-bold text-slate-800 truncate">
                                            {{ $item->product->name ?? 'Sản phẩm đã xóa' }}
                                        </p>

                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            Tồn kho:
                                            {{ $item->product->stock ?? 0 }}
                                            sản phẩm
                                        </p>

                                    </div>

                                    {{-- SỐ LƯỢNG --}}
                                    <div class="text-right shrink-0">

                                        <p class="text-xs font-black text-indigo-600">
                                            {{ $item->total_sold }} đã bán
                                        </p>

                                        <p class="text-[10px] text-slate-400">
                                            {{ format_price($item->product->price ?? 0) }}
                                        </p>

                                    </div>

                                </div>

                                {{-- THANH TIẾN TRÌNH --}}
                                <div class="mt-2 w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">

                                    <div
                                        class="h-full rounded-full transition-all duration-500
                                        {{ $index == 0
                                            ? 'bg-amber-500'
                                            : ($index == 1
                                                ? 'bg-indigo-500'
                                                : ($index == 2
                                                    ? 'bg-orange-400'
                                                    : 'bg-slate-400')) }}"
                                        style="width: {{ $percent }}%"
                                    ></div>

                                </div>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="py-10 text-center">

                        <div class="w-10 h-10 mx-auto mb-2 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                            <i data-lucide="package" class="w-5 h-5"></i>
                        </div>

                        <p class="text-xs text-slate-400">
                            Chưa có dữ liệu sản phẩm bán chạy
                        </p>

                    </div>

                @endforelse

            </div>

</div>
        </div>

        {{-- ĐƠN HÀNG MỚI --}}
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Đơn hàng mới nhất</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline whitespace-nowrap">
                    Xem tất cả →
                </a>
            </div>

            <div class="divide-y divide-slate-50">
                @php $statusColors = \App\Models\Order::statusColors(); @endphp

                @forelse($latestOrders as $order)
                    @php
                        $cs = $order->statuses->first();
                        $csId = $cs ? $cs->id : null;
                        $colors = $csId && isset($statusColors[$csId])
                            ? $statusColors[$csId]
                            : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                    @endphp

                    <a href="{{ route('admin.orders.show', $order->id) }}"
                       class="flex items-center gap-3 px-4 sm:px-5 py-3 hover:bg-slate-50/50 transition">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-indigo-600">#{{ $order->code }}</span>

                                @if($cs)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold {{ $colors['bg'] }} {{ $colors['text'] }}">
                                    <span class="w-1 h-1 rounded-full bg-current opacity-70"></span>
                                    {{ $cs->name }}
                                </span>
                                @endif
                            </div>

                            <p class="text-[10px] text-slate-400 mt-0.5 truncate">
                                {{ $order->user->fullname ?? $order->fullname }}
                                • {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <span class="text-xs font-bold text-slate-800 shrink-0">
                            {{ format_price($order->grand_total) }}
                        </span>
                    </a>
                @empty
                <div class="p-6 text-center text-xs text-slate-400">
                    Chưa có đơn hàng nào
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SẢN PHẨM SẮP HẾT HÀNG --}}
    @if($lowStockProducts->count() > 0)
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-4 sm:px-5 py-3.5 border-b border-slate-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center shrink-0">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                </div>

                <h3 class="text-sm font-bold text-slate-800 truncate">
                    Sản phẩm sắp hết hàng
                    <span class="text-slate-400 font-medium hidden sm:inline">(tồn kho ≤ 10)</span>
                </h3>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="text-[10px] text-indigo-600 font-bold hover:underline whitespace-nowrap">
                Quản lý →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[700px]">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-4 sm:px-5">Sản phẩm</th>
                        <th class="py-3 px-4 sm:px-5 text-center">Tồn kho</th>
                        <th class="py-3 px-4 sm:px-5 text-center">Giá bán</th>
                        <th class="py-3 px-4 sm:px-5 text-center">Trạng thái</th>
                        <th class="py-3 px-4 sm:px-5 text-right">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50">
                    @foreach($lowStockProducts as $product)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 sm:px-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 shrink-0">
                                    @if($product->image)
                                    <img src="{{ asset($product->image) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                    </div>
                                    @endif
                                </div>

                                <span class="text-xs font-semibold text-slate-800 truncate max-w-xs">
                                    {{ $product->name }}
                                </span>
                            </div>
                        </td>

                        <td class="py-3 px-4 sm:px-5 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $product->stock == 0 ? 'bg-red-100 text-red-700' : ($product->stock <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-yellow-50 text-yellow-700') }}">
                                {{ $product->stock }}
                            </span>
                        </td>

                        <td class="py-3 px-4 sm:px-5 text-center text-xs font-semibold text-slate-700">
                            {{ format_price($product->price) }}
                        </td>

                        <td class="py-3 px-4 sm:px-5 text-center">
                            @if($product->stock == 0)
                            <span class="text-[10px] font-bold text-red-600">HẾT HÀNG</span>
                            @else
                            <span class="text-[10px] font-bold text-amber-600">SẮP HẾT</span>
                            @endif
                        </td>

                        <td class="py-3 px-4 sm:px-5 text-right">
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                               class="text-[10px] text-indigo-600 font-bold hover:underline">
                                Cập nhật →
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // BIỂU ĐỒ DOANH THU
    const revenueCanvas = document.getElementById('revenueChart');

    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'bar',
            data: {
                labels: @json($revenueLabels),
                datasets: [{
                    label: 'Doanh thu',
                    data: @json($revenueChart),
                    backgroundColor: 'rgba(99, 102, 241, 0.65)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('vi-VN').format(context.parsed.y) + 'đ';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000).toFixed(0) + 'M';
                                if (value >= 1000) return (value / 1000).toFixed(0) + 'K';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8'
                        }
                    }
                }
            }
        });
    }

    // BIỂU ĐỒ TRẠNG THÁI
    const statusCanvas = document.getElementById('orderStatusChart');

    if (statusCanvas) {
        const statusColors = [
            '#f59e0b', '#fb923c', '#3b82f6', '#10b981',
            '#a855f7', '#ec4899', '#ef4444', '#64748b',
            '#0ea5e9', '#14b8a6'
        ];

        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: @json($orderStatusChartLabels),
                datasets: [{
                    data: @json($orderStatusChartData),
                    backgroundColor: statusColors.slice(0, @json(count($orderStatusChartLabels))),
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            color: '#475569'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });
    }

    // BIỂU ĐỒ SỐ LƯỢNG ĐƠN HÀNG
    const ordersCanvas = document.getElementById('ordersChart');

    if (ordersCanvas) {
        new Chart(ordersCanvas, {
            type: 'line',
            data: {
                labels: @json($ordersChartLabels),
                datasets: [{
                    label: 'Số đơn hàng',
                    data: @json($ordersChartData),
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#8b5cf6'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' đơn hàng';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 },
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 },
                            color: '#94a3b8'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
    
        // BIỂU ĐỒ TOP 10 SẢN PHẨM BÁN CHẠY
    const topProductsCanvas = document.getElementById('topProductsChart');

    if (topProductsCanvas) {

        new Chart(topProductsCanvas, {
            type: 'bar',

            data: {
                labels: @json(
                    $topProducts->map(function($item) {
                        return $item->product->name ?? 'Sản phẩm đã xóa';
                    })->values()
                ),

                datasets: [{
                    label: 'Số lượng đã bán',

                    data: @json(
                        $topProducts->map(function($item) {
                            return $item->total_sold;
                        })->values()
                    ),

                    backgroundColor: [
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(139, 92, 246, 0.85)',
                        'rgba(236, 72, 153, 0.85)',
                        'rgba(20, 184, 166, 0.85)',
                        'rgba(249, 115, 22, 0.85)',
                        'rgba(100, 116, 139, 0.85)',
                        'rgba(244, 63, 94, 0.85)'
                    ],

                    borderRadius: 6,
                    borderSkipped: false
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                indexAxis: 'y',

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        cornerRadius: 8,

                        callbacks: {
                            label: function(context) {
                                return context.parsed.x + ' sản phẩm đã bán';
                            }
                        }
                    }
                },

                scales: {

                    x: {
                        beginAtZero: true,

                        grid: {
                            color: 'rgba(0,0,0,0.04)'
                        },

                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 10
                            },
                            color: '#94a3b8'
                        }
                    },

                    y: {
                        grid: {
                            display: false
                        },

                        ticks: {
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            color: '#475569',

                            callback: function(value) {

                                const label = this.getLabelForValue(value);

                                return label.length > 22
                                    ? label.substring(0, 22) + '...'
                                    : label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endsection