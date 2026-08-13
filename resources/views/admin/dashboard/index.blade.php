@extends('layouts.admin')

@section('title', 'Admin Dashboard - Veloce')
@section('page_title', 'Tổng quan hệ thống')

@section('content')
<div class="space-y-8">

    {{-- ═══════════ THÔNG BÁO NHANH ═══════════ --}}
    @if($pendingOrders > 0 || $pendingRefunds > 0 || $newReviews > 0 || $pendingContacts > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @if($pendingOrders > 0)
        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-amber-800">{{ $pendingOrders }} đơn chờ xác nhận</p>
                <p class="text-[10px] text-amber-600 group-hover:underline">Xem ngay →</p>
            </div>
        </a>
        @endif
        @if($pendingRefunds > 0)
        <a href="{{ route('admin.refunds.index') }}" class="flex items-center gap-3 p-4 bg-purple-50 border border-purple-200 rounded-xl hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0">
                <i data-lucide="refresh-cw" class="w-5 h-5 text-purple-600"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-purple-800">{{ $pendingRefunds }} yêu cầu trả hàng</p>
                <p class="text-[10px] text-purple-600 group-hover:underline">Xử lý ngay →</p>
            </div>
        </a>
        @endif
        @if($newReviews > 0)
        <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 p-4 bg-sky-50 border border-sky-200 rounded-xl hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center shrink-0">
                <i data-lucide="star" class="w-5 h-5 text-sky-600"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-sky-800">{{ $newReviews }} đánh giá mới (7 ngày)</p>
                <p class="text-[10px] text-sky-600 group-hover:underline">Xem đánh giá →</p>
            </div>
        </a>
        @endif
        @if($pendingContacts > 0)
        <a href="{{ route('admin.contacts.index') }}" class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-200 rounded-xl hover:shadow-md transition group">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <i data-lucide="mail" class="w-5 h-5 text-rose-600"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-rose-800">{{ $pendingContacts }} liên hệ chờ phản hồi</p>
                <p class="text-[10px] text-rose-600 group-hover:underline">Trả lời ngay →</p>
            </div>
        </a>
        @endif
    </div>
    @endif

    {{-- ═══════════ 4 THẺ THỐNG KÊ ═══════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Doanh thu --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition">
            <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center mb-3">
                    <i data-lucide="trending-up" class="w-5 h-5 text-emerald-600"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Doanh thu tháng {{ date('m/Y') }}</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($revenueThisMonth, 0, ',', '.') }}đ</h3>
            </div>
        </div>

        {{-- Đơn hàng --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition">
            <div class="absolute top-0 right-0 w-24 h-24 bg-indigo-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center mb-3">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Đơn hàng tháng này</span>
                <h3 class="text-2xl font-black text-indigo-600 mt-1">{{ $ordersThisMonth }} đơn</h3>
            </div>
        </div>

        {{-- Khách hàng --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition">
            <div class="absolute top-0 right-0 w-24 h-24 bg-sky-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-sky-100 flex items-center justify-center mb-3">
                    <i data-lucide="users" class="w-5 h-5 text-sky-600"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tổng khách hàng</span>
                <h3 class="text-2xl font-black text-slate-800 mt-1">{{ number_format($totalCustomers) }}</h3>
            </div>
        </div>

        {{-- Sắp hết hàng --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition">
            <div class="absolute top-0 right-0 w-24 h-24 bg-rose-50 rounded-full -translate-y-8 translate-x-8 group-hover:scale-125 transition"></div>
            <div class="relative">
                <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center mb-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                </div>
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Sắp hết hàng</span>
                <h3 class="text-2xl font-black text-rose-600 mt-1">{{ $lowStockCount }} sản phẩm</h3>
            </div>
        </div>
    </div>

    {{-- ═══════════ BIỂU ĐỒ ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Biểu đồ doanh thu 12 tháng --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Doanh thu 12 tháng gần nhất</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Tổng doanh thu đơn hàng đã hoàn tất</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 text-indigo-600"></i>
                </div>
            </div>
            <div class="h-72">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Biểu đồ trạng thái đơn hàng --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Trạng thái đơn hàng</h3>
                    <p class="text-[10px] text-slate-400 mt-0.5">Phân bố đơn hàng hiện tại</p>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-emerald-600"></i>
                </div>
            </div>
            <div class="h-72 flex items-center justify-center">
                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ═══════════ TOP SẢN PHẨM & ĐƠN HÀNG MỚI ═══════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top 10 sản phẩm bán chạy --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i data-lucide="trophy" class="w-4 h-4 text-amber-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Top 10 sản phẩm bán chạy</h3>
                </div>
                <a href="{{ route('admin.products.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">Xem tất cả →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($topProducts as $index => $item)
                    <div class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50/50 transition">
                        <span class="w-6 h-6 rounded-full text-[10px] font-black flex items-center justify-center shrink-0
                            {{ $index < 3 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="w-10 h-10 rounded-xl overflow-hidden border border-slate-100 bg-slate-50 shrink-0">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset($item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $item->product->name ?? 'Sản phẩm đã xóa' }}</p>
                            <p class="text-[10px] text-slate-400">Tồn kho: {{ $item->product->stock ?? 0 }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-black text-indigo-600">{{ $item->total_sold }} đã bán</p>
                            <p class="text-[10px] text-slate-400">{{ number_format($item->product->price ?? 0, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Chưa có dữ liệu sản phẩm bán chạy</div>
                @endforelse
            </div>
        </div>

        {{-- Đơn hàng mới nhất --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="shopping-bag" class="w-4 h-4 text-indigo-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Đơn hàng mới nhất</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">Xem tất cả →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @php $statusColors = \App\Models\Order::statusColors(); @endphp
                @forelse($latestOrders as $order)
                    @php
                        $cs = $order->statuses->first();
                        $csId = $cs ? $cs->id : null;
                        $colors = $csId && isset($statusColors[$csId]) ? $statusColors[$csId] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                    @endphp
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50/50 transition">
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
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $order->user->fullname ?? $order->fullname }} • {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs font-bold text-slate-800 shrink-0">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                    </a>
                @empty
                    <div class="p-8 text-center text-xs text-slate-400">Chưa có đơn hàng nào</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ═══════════ SẢN PHẨM SẮP HẾT HÀNG ═══════════ --}}
    @if($lowStockProducts->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-rose-100 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800">Sản phẩm sắp hết hàng <span class="text-slate-400 font-medium">(tồn kho ≤ 10)</span></h3>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-[10px] text-indigo-600 font-bold hover:underline">Quản lý sản phẩm →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-6">Sản phẩm</th>
                        <th class="py-3 px-6 text-center">Tồn kho</th>
                        <th class="py-3 px-6 text-center">Giá bán</th>
                        <th class="py-3 px-6 text-center">Trạng thái</th>
                        <th class="py-3 px-6 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($lowStockProducts as $product)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 shrink-0">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300"><i data-lucide="package" class="w-3.5 h-3.5"></i></div>
                                    @endif
                                </div>
                                <span class="text-xs font-semibold text-slate-800 truncate max-w-xs">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                {{ $product->stock == 0 ? 'bg-red-100 text-red-700' : ($product->stock <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-yellow-50 text-yellow-700') }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center text-xs font-semibold text-slate-700">{{ number_format($product->price, 0, ',', '.') }}đ</td>
                        <td class="py-3 px-6 text-center">
                            @if($product->stock == 0)
                                <span class="text-[10px] font-bold text-red-600">HẾT HÀNG</span>
                            @else
                                <span class="text-[10px] font-bold text-amber-600">SẮP HẾT</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-right">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-[10px] text-indigo-600 font-bold hover:underline">Cập nhật →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Biểu đồ doanh thu ──
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($revenueChart),
                backgroundColor: function(context) {
                    const chart = context.chart;
                    const {ctx, chartArea} = chart;
                    if (!chartArea) return 'rgba(99, 102, 241, 0.6)';
                    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.15)');
                    gradient.addColorStop(1, 'rgba(99, 102, 241, 0.7)');
                    return gradient;
                },
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1.5,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 11 },
                    padding: 12,
                    cornerRadius: 10,
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
                    ticks: { font: { size: 10 }, color: '#94a3b8' }
                }
            }
        }
    });

    // ── Biểu đồ trạng thái đơn hàng ──
    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusColors = [
        '#f59e0b', '#fb923c', '#3b82f6', '#10b981',
        '#a855f7', '#ec4899', '#ef4444', '#64748b',
        '#0ea5e9', '#14b8a6'
    ];
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: @json($orderStatusChartLabels),
            datasets: [{
                data: @json($orderStatusChartData),
                backgroundColor: statusColors.slice(0, @json(count($orderStatusChartLabels))),
                borderWidth: 2,
                borderColor: '#ffffff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { size: 10, weight: 'bold' },
                        color: '#475569',
                    }
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 11, weight: 'bold' },
                    bodyFont: { size: 11 },
                    padding: 12,
                    cornerRadius: 10,
                }
            }
        }
    });
});
</script>
@endsection
