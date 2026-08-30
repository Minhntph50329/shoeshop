@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng - Veloce Admin')
@section('page_title', 'Quản lý Đơn hàng')

@php
$statusColors = \App\Models\Order::statusColors();
$statusLabels = [
    1 => 'Chờ xác nhận', 2 => 'Chờ lấy hàng', 3 => 'Đang giao',
    4 => 'Giao hàng thành công', 5 => 'Chờ trả hàng', 6 => 'Đã trả hàng',
    7 => 'Hoàn tiền', 8 => 'Đã hủy', 9 => 'Gửi hàng', 10 => 'Nhận hàng thành công',
];
@endphp

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        @php
        $cards = [
            ['label'=>'Tổng đơn',        'value'=>$stats['total'],     'icon'=>'shopping-bag',   'bg'=>'bg-indigo-50',   'text'=>'text-indigo-600'],
            ['label'=>'Chờ xác nhận',    'value'=>$stats['pending'],   'icon'=>'clock',          'bg'=>'bg-amber-50',    'text'=>'text-amber-600'],
            ['label'=>'Đang giao',        'value'=>$stats['shipping'],  'icon'=>'truck',          'bg'=>'bg-blue-50',     'text'=>'text-blue-600'],
            ['label'=>'Thành công',       'value'=>$stats['done'],      'icon'=>'check-circle-2', 'bg'=>'bg-emerald-50',  'text'=>'text-emerald-600'],
            ['label'=>'Đã hủy',           'value'=>$stats['cancelled'], 'icon'=>'x-circle',       'bg'=>'bg-red-50',      'text'=>'text-red-600'],
        ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-3">
            <div class="{{ $card['bg'] }} p-2.5 rounded-xl shrink-0">
                <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 {{ $card['text'] }}"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-400 font-medium truncate">{{ $card['label'] }}</p>
                <p class="text-xl font-black text-slate-800">{{ number_format($card['value']) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="flex items-start gap-2 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700">
            <i data-lucide="check-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-start gap-2 rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700">
            <i data-lucide="alert-circle" class="mt-0.5 h-5 w-5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="w-full rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div class="relative min-w-0 sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm mã đơn, tên, email, SĐT..."
                    class="w-full min-w-0 pl-9 pr-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div class="min-w-0">
                <select name="status" class="w-full min-w-0 px-3 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s->id }}" {{ request('status') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <input type="date" name="from" value="{{ request('from') }}"
                    class="flex-1 py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition whitespace-nowrap shrink-0">
                    Lọc
                </button>
                @if(request()->anyFilled(['search','status','from','to']))
                    <a href="{{ route('admin.orders.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-medium hover:bg-slate-200 transition shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MOBILE CARDS -->
    <div class="space-y-4 md:hidden">
        @forelse($orders as $order)
            @php
                $cs = $order->statuses->first();
                $csId = $cs ? $cs->id : null;
                $colors = $csId && isset($statusColors[$csId]) ? $statusColors[$csId] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
            @endphp
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Mã đơn hàng</p>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="font-black text-indigo-600 hover:text-indigo-800 transition text-sm mt-0.5 inline-block">
                            #{{ $order->code }}
                        </a>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            {{ $order->created_at->format('d/m/Y') }} <span class="mx-1">•</span> {{ $order->created_at->format('H:i') }}
                        </div>
                    </div>
                    <div class="shrink-0 ml-2">
                        @if($cs)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                {{ $cs->name }}
                            </span>
                        @else
                            <span class="text-slate-300 text-[10px] border border-slate-200 px-2 py-0.5 rounded-full">Chưa có</span>
                        @endif
                    </div>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Khách hàng</p>
                        <p class="mt-1 font-semibold text-slate-800 truncate">{{ $order->fullname ?? 'Khách vãng lai' }}</p>
                        <div class="flex gap-2 text-[11px] text-slate-500 truncate mt-0.5">
                            <span class="truncate">{{ $order->phone_number }}</span>
                            <span class="truncate">{{ $order->email }}</span>
                        </div>
                    </div>
                    
                    <div class="min-w-0 col-span-2 flex items-center justify-between bg-slate-50 p-2 rounded-lg border border-slate-100">
                        <div class="flex items-center gap-1.5">
                            @if($order->is_paid)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-semibold">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Đã TT
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[10px] font-semibold">
                                    <i data-lucide="clock" class="w-3 h-3"></i> Chưa TT
                                </span>
                            @endif
                            <span class="text-slate-500 text-[11px]">{{ $order->payment->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="min-w-0 col-span-2 flex justify-between items-center bg-indigo-50/50 p-3 rounded-xl border border-indigo-50">
                        <p class="text-[11px] font-bold text-indigo-900">Tổng tiền</p>
                        <div class="text-right">
                            <p class="font-black text-indigo-600 text-lg leading-none">{{ format_price($order->grand_total) }}</p>
                            @if($order->discount_amount > 0)
                                <p class="text-emerald-600 text-[10px] mt-0.5">-{{ format_price($order->discount_amount) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action -->
                <div class="mt-4 flex items-center justify-end border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                       class="inline-flex items-center gap-2 w-full justify-center py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl text-xs font-bold transition">
                        Xem chi tiết <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">
                <div class="flex flex-col items-center gap-3">
                    <i data-lucide="package-x" class="w-10 h-10 opacity-30"></i>
                    <p>Không có đơn hàng nào.</p>
                </div>
            </div>
        @endforelse
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[900px] text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Mã đơn hàng</th>
                        <th class="p-4">Khách hàng</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Thanh toán</th>
                        <th class="p-4">Tổng tiền</th>
                        <th class="p-4">Ngày đặt</th>
                        <th class="p-4 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-600">
                    @forelse($orders as $order)
                    @php
                        $cs = $order->statuses->first();
                        $csId = $cs ? $cs->id : null;
                        $colors = $csId && isset($statusColors[$csId]) ? $statusColors[$csId] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="font-black text-indigo-600 hover:text-indigo-800 transition">
                                #{{ $order->code }}
                            </a>
                        </td>
                        <td class="p-4">
                            <div>
                                <p class="font-semibold text-slate-800">{{ $order->fullname ?? 'Khách vãng lai' }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $order->email }}</p>
                                <p class="text-slate-400 text-[11px]">{{ $order->phone_number }}</p>
                            </div>
                        </td>
                        <td class="p-4">
                            @if($cs)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['border'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                                    {{ $cs->name }}
                                </span>
                            @else
                                <span class="text-slate-300 text-[11px]">Chưa có</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex items-center gap-1.5">
                                @if($order->is_paid)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[11px] font-semibold">
                                        <i data-lucide="check-circle" class="w-3 h-3"></i> Đã TT
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-amber-50 text-amber-700 rounded-full text-[11px] font-semibold">
                                        <i data-lucide="clock" class="w-3 h-3"></i> Chưa TT
                                    </span>
                                @endif
                            </div>
                            <p class="text-slate-400 text-[11px] mt-0.5">{{ $order->payment->name ?? 'N/A' }}</p>
                        </td>
                        <td class="p-4">
                            <p class="font-black text-slate-800">{{ format_price($order->grand_total) }}</p>
                            @if($order->discount_amount > 0)
                                <p class="text-emerald-600 text-[11px]">-{{ format_price($order->discount_amount) }}</p>
                            @endif
                        </td>
                        <td class="p-4 text-slate-400 text-[11px]">
                            {{ $order->created_at->format('d/m/Y') }}<br>
                            <span class="text-slate-300">{{ $order->created_at->format('H:i') }}</span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[11px] font-bold hover:bg-indigo-100 transition">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <i data-lucide="package-x" class="w-12 h-12 opacity-30"></i>
                                <p class="font-medium">Không có đơn hàng nào</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($orders->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
