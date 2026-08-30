@extends('layouts.admin')

@section('title', 'Quản lý Trả hàng & Hoàn tiền - Veloce Admin')
@section('page_title', 'Yêu cầu Trả hàng / Hoàn tiền')

@section('content')
<div class="w-full max-w-full space-y-6 overflow-x-hidden">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        @php
        $cards = [
            ['label'=>'Tổng yêu cầu',     'value'=>$stats['total'],     'icon'=>'refresh-cw',    'bg'=>'bg-indigo-50',   'text'=>'text-indigo-600'],
            ['label'=>'Chờ xử lý',       'value'=>$stats['pending'],   'icon'=>'clock',          'bg'=>'bg-amber-50',    'text'=>'text-amber-600'],
            ['label'=>'Đã duyệt',        'value'=>$stats['approved'],  'icon'=>'check',          'bg'=>'bg-blue-50',     'text'=>'text-blue-600'],
            ['label'=>'Đã hoàn tiền',    'value'=>$stats['completed'], 'icon'=>'check-circle-2', 'bg'=>'bg-emerald-50',  'text'=>'text-emerald-600'],
            ['label'=>'Đã từ chối',      'value'=>$stats['rejected'],  'icon'=>'x-circle',       'bg'=>'bg-red-50',      'text'=>'text-red-600'],
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
        <form method="GET" action="{{ route('admin.refunds.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <div class="relative min-w-0 sm:col-span-2">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm theo mã đơn hàng, tên khách hàng, email..."
                    class="w-full min-w-0 pl-9 pr-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div class="min-w-0">
                <select name="status" class="w-full min-w-0 px-3 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">
                    Lọc
                </button>
                @if(request()->anyFilled(['search','status']))
                    <a href="{{ route('admin.refunds.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-medium hover:bg-slate-200 transition shrink-0">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- MOBILE CARDS -->
    <div class="space-y-4 md:hidden">
        @forelse($refunds as $refund)
            @php
                $refColor = $statusColors[$refund->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
            @endphp
            <div class="w-full overflow-hidden rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                <!-- Header -->
                <div class="flex justify-between items-start mb-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Mã đơn hàng</p>
                        <p class="font-bold text-slate-800 text-sm mt-0.5">#{{ $refund->order->code ?? 'N/A' }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border shrink-0 {{ $refColor['bg'] }} {{ $refColor['text'] }} {{ $refColor['border'] }}">
                        {{ $statuses[$refund->status] ?? $refund->status }}
                    </span>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-2 gap-3 border-t border-slate-100 pt-4">
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Khách hàng</p>
                        <p class="mt-1 font-semibold text-slate-800 truncate">{{ $refund->user->fullname ?? 'N/A' }}</p>
                        <p class="text-[11px] text-slate-400 truncate">{{ $refund->user->email ?? '' }}</p>
                    </div>
                    
                    <div class="min-w-0 col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Ngân hàng hoàn tiền</p>
                        <div class="mt-1 bg-slate-50 p-2 rounded-lg border border-slate-100">
                            <p class="font-medium text-slate-800 text-xs">{{ $refund->bank_name }}</p>
                            <p class="text-[11px] text-slate-500 mt-0.5">{{ $refund->bank_account }}</p>
                            <p class="text-[11px] text-slate-500 italic">{{ $refund->user_bank_name }}</p>
                        </div>
                    </div>

                    <div class="min-w-0 col-span-2 flex justify-between items-center bg-indigo-50/50 p-3 rounded-xl border border-indigo-50">
                        <p class="text-[11px] font-bold text-indigo-900">Tổng tiền hoàn</p>
                        <p class="font-black text-indigo-600 text-lg">{{ format_price($refund->total_amount) }}</p>
                    </div>
                </div>

                <!-- Action -->
                <div class="mt-4 flex items-center justify-end border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.refunds.show', $refund->id) }}" 
                       class="inline-flex items-center gap-2 w-full justify-center py-2.5 bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-xl text-xs font-bold transition">
                        Xem chi tiết <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-xs text-slate-400 shadow-sm">Không có yêu cầu trả hàng nào.</div>
        @endforelse
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden md:block">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[900px] text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="p-4">Mã Đơn hàng</th>
                        <th class="p-4">Khách hàng</th>
                        <th class="p-4">Ngân hàng</th>
                        <th class="p-4">Trạng thái yêu cầu</th>
                        <th class="p-4">Tổng tiền hoàn</th>
                        <th class="p-4 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-xs text-slate-700">
                    @forelse($refunds as $refund)
                        @php
                            $refColor = $statusColors[$refund->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                        @endphp
                        <tr class="hover:bg-slate-50/40 transition">
                            <td class="p-4 font-bold text-slate-800">
                                #{{ $refund->order->code ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                <div class="font-semibold text-slate-800">{{ $refund->user->fullname ?? 'N/A' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $refund->user->email ?? '' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-slate-800">{{ $refund->bank_name }}</div>
                                <div class="text-[10px] text-slate-400">{{ $refund->bank_account }} ({{ $refund->user_bank_name }})</div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $refColor['bg'] }} {{ $refColor['text'] }} {{ $refColor['border'] }}">
                                    {{ $statuses[$refund->status] ?? $refund->status }}
                                </span>
                            </td>
                            <td class="p-4 font-black text-indigo-600">
                                {{ format_price($refund->total_amount) }}
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('admin.refunds.show', $refund->id) }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 border border-slate-200 rounded-lg text-[10px] font-bold text-slate-600 hover:bg-slate-50 transition">
                                    Chi tiết <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Không có yêu cầu trả hàng nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($refunds->hasPages())
        <div class="rounded-2xl border border-slate-100 bg-white p-3 shadow-sm sm:p-4 mt-4">
            {{ $refunds->links() }}
        </div>
    @endif
</div>
@endsection
