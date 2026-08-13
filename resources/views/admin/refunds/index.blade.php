@extends('layouts.admin')

@section('title', 'Quản lý Trả hàng & Hoàn tiền - Veloce Admin')
@section('page_title', 'Yêu cầu Trả hàng / Hoàn tiền')

@section('content')
<div class="space-y-6">

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
            <div class="{{ $card['bg'] }} p-2.5 rounded-xl">
                <i data-lucide="{{ $card['icon'] }}" class="w-5 h-5 {{ $card['text'] }}"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-medium">{{ $card['label'] }}</p>
                <p class="text-xl font-black text-slate-800">{{ number_format($card['value']) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.refunds.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm theo mã đơn hàng, tên khách hàng, email..."
                    class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>

            <div>
                <select name="status" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
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
                    <a href="{{ route('admin.refunds.index') }}" class="px-3 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-medium hover:bg-slate-200 transition">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                <tbody class="divide-y divide-slate-50 text-xs">
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
                                {{ number_format($refund->total_amount, 0, ',', '.') }}đ
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

        @if($refunds->hasPages())
            <div class="px-6 py-4 border-t border-slate-50">
                {{ $refunds->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
