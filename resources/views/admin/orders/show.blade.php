@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->code . ' - Veloce Admin')
@section('page_title', 'Chi tiết Đơn hàng')

@php
$statusColors = \App\Models\Order::statusColors();
$statusLabels = [
    1 => 'Chờ xác nhận', 2 => 'Chờ lấy hàng', 3 => 'Đang giao',
    4 => 'Giao hàng thành công', 5 => 'Chờ trả hàng', 6 => 'Đã trả hàng',
    7 => 'Hoàn tiền', 8 => 'Đã hủy', 9 => 'Gửi hàng', 10 => 'Nhận hàng thành công',
];
$cs = $currentStatus;
$csColors = $cs && isset($statusColors[$cs->id]) ? $statusColors[$cs->id] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
@endphp

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb + Back --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.orders.index') }}" class="hover:text-indigo-600 transition font-medium">Đơn hàng</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-black text-slate-800">#{{ $order->code }}</span>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT COLUMN (2/3) --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Order Header Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">#{{ $order->code }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Đặt lúc {{ $order->created_at->format('H:i d/m/Y') }}</p>
                    </div>
                    @if($cs)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $csColors['bg'] }} {{ $csColors['text'] }} {{ $csColors['border'] }}">
                        <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                        {{ $cs->name }}
                    </span>
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4 border-t border-slate-100">
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">Thanh toán</p>
                        <p class="text-xs font-semibold text-slate-800 mt-1">{{ $order->payment->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">Tình trạng TT</p>
                        @if($order->is_paid)
                            <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-bold text-emerald-600"><i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Đã thanh toán</span>
                        @else
                            <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-bold text-amber-600"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Chưa thanh toán</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">Vận chuyển</p>
                        <p class="text-xs font-semibold text-slate-800 mt-1">{{ $order->shipping_type == 'express' ? 'Nhanh' : 'Tiêu chuẩn' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] text-slate-400 font-medium uppercase tracking-wide">Phí ship</p>
                        <p class="text-xs font-semibold text-slate-800 mt-1">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</p>
                    </div>
                </div>
            </div>

            {{-- Products --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Sản phẩm ({{ $order->items->count() }})</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($order->items as $item)
                    @php
                        $variant = $item->productVariant;
                        // Ưu tiên: ảnh biến thể > ảnh sản phẩm > placeholder
                        if ($variant && $variant->image) {
                            $img = asset($variant->image);
                        } elseif ($item->product && $item->product->images->first()) {
                            $img = asset('storage/' . $item->product->images->first()->url);
                        } else {
                            $img = 'https://placehold.co/80x80/f1f5f9/94a3b8?text=No+Img';
                        }
                        // Lấy tên thuộc tính biến thể (VD: Xanh, 42)
                        $variantAttributes = $variant ? $variant->attributeValues->map(fn($av) => $av->value)->implode(', ') : null;
                    @endphp
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                            <img src="{{ $img }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-sm text-slate-800 line-clamp-1">{{ $item->name }}</h4>
                            @if($variantAttributes)
                                <p class="text-[11px] text-slate-400 mt-0.5">
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-100 rounded text-[10px] font-semibold text-slate-600">{{ $variantAttributes }}</span>
                                </p>
                            @elseif($item->name_variant)
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $item->name_variant }}: {{ $item->attributes_variant }}</p>
                            @endif
                        </div>
                        <div class="text-center flex-shrink-0 w-16">
                            <span class="text-xs text-slate-500 font-medium">x{{ $item->quantity }}</span>
                        </div>
                        <div class="text-right flex-shrink-0 w-28">
                            @if($item->old_price && $item->old_price > $item->price)
                                <p class="text-[11px] text-slate-300 line-through">{{ number_format($item->old_price, 0, ',', '.') }}đ</p>
                            @endif
                            <p class="font-bold text-indigo-600 text-sm">{{ number_format($item->effective_price, 0, ',', '.') }}đ</p>
                            <p class="text-[11px] text-slate-400">= {{ number_format($item->line_total, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Totals --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Tạm tính</span>
                        <span class="font-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-xs text-emerald-600">
                        <span>Giảm giá {{ $order->coupon ? '('. $order->coupon->code .')' : '' }}</span>
                        <span class="font-semibold">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Phí vận chuyển</span>
                        <span class="font-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between font-black text-sm text-slate-800 pt-2 border-t border-slate-200">
                        <span>Tổng cộng</span>
                        <span class="text-indigo-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>

            {{-- Status Timeline --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="git-branch" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Lịch sử trạng thái</h3>
                </div>
                <div class="px-6 py-5">
                    @if($order->statusHistories->count())
                    <ol class="relative border-l border-slate-200 ml-3 space-y-6">
                        @foreach($order->statusHistories->sortByDesc('created_at') as $history)
                        @php
                            $hColors = isset($statusColors[$history->order_status_id]) ? $statusColors[$history->order_status_id] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                        @endphp
                        <li class="ml-6">
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white {{ $history->is_current ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                <i data-lucide="{{ $history->is_current ? 'check' : 'circle' }}" class="w-3 h-3 {{ $history->is_current ? 'text-white' : 'text-slate-400' }}"></i>
                            </span>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $hColors['bg'] }} {{ $hColors['text'] }}">
                                        {{ $history->status->name ?? 'N/A' }}
                                    </span>
                                    @if($history->note)
                                        <p class="text-xs text-slate-500 mt-1">{{ $history->note }}</p>
                                    @endif
                                    @if($history->modifier)
                                        <p class="text-[11px] text-slate-400 mt-0.5">bởi {{ $history->modifier->fullname ?? $history->modifier->email }}</p>
                                    @endif
                                </div>
                                <time class="text-[11px] text-slate-400 whitespace-nowrap">{{ $history->created_at->format('H:i d/m/Y') }}</time>
                            </div>
                        </li>
                        @endforeach
                    </ol>
                    @else
                        <p class="text-xs text-slate-400 text-center py-4">Chưa có lịch sử cập nhật trạng thái</p>
                    @endif
                </div>
            </div>

            {{-- Payment Logs --}}
            @if($order->paymentLogs->count())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Log thanh toán</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($order->paymentLogs as $log)
                    <div class="px-6 py-4 text-xs">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-slate-700">TxnRef: {{ $log->txn_ref ?? 'N/A' }}</span>
                            @if($log->response_code == '00')
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full font-bold text-[11px]">Thành công</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full font-bold text-[11px]">Lỗi ({{ $log->response_code }})</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-3 gap-3 text-[11px] text-slate-500">
                            <div><span class="font-medium">Số tiền:</span> {{ number_format($log->amount, 0, ',', '.') }}đ</div>
                            <div><span class="font-medium">Ngân hàng:</span> {{ $log->bank_code ?? 'N/A' }}</div>
                            <div><span class="font-medium">Thời gian:</span> {{ $log->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- RIGHT COLUMN (1/3) --}}
        <div class="space-y-6">

            {{-- Update Status --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="refresh-ccw" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Cập nhật trạng thái</h3>
                </div>
                <div class="p-5">
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('POST')
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Trạng thái mới</label>
                            <select name="status_id"
                                class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                                @foreach($allStatuses as $status)
                                    <option value="{{ $status->id }}" {{ $cs && $cs->id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Ghi chú (tuỳ chọn)</label>
                            <textarea name="note" rows="3" placeholder="Nhập ghi chú..."
                                class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-200 transition flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            {{-- Customer Info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Thông tin khách hàng</h3>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 font-black flex items-center justify-center text-sm uppercase flex-shrink-0">
                            {{ substr($order->fullname ?? 'K', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800">{{ $order->fullname ?? 'Khách vãng lai' }}</p>
                            @if($order->user)
                                <a href="{{ route('admin.customers.show', $order->user_id) }}" class="text-indigo-500 hover:underline text-[11px]">Xem tài khoản</a>
                            @else
                                <p class="text-slate-400 text-[11px]">Khách không đăng nhập</p>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-2 pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-slate-600">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                            <span>{{ $order->phone_number ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0"></i>
                            <span class="break-all">{{ $order->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-start gap-2 text-slate-600">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400 flex-shrink-0 mt-0.5"></i>
                            <span>{{ $order->address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Note / Cancel Info --}}
            @if($order->note || $order->cancel_reason)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-4 h-4 text-slate-400"></i>
                    <h3 class="font-bold text-sm text-slate-800">Ghi chú</h3>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    @if($order->note)
                    <div>
                        <p class="text-slate-400 font-medium mb-1">Ghi chú đơn hàng:</p>
                        <p class="text-slate-700 bg-slate-50 rounded-lg p-3">{{ $order->note }}</p>
                    </div>
                    @endif
                    @if($order->cancel_reason)
                    <div>
                        <p class="text-red-400 font-medium mb-1">Lý do hủy:</p>
                        <p class="text-slate-700 bg-red-50 rounded-lg p-3">{{ $order->cancel_reason }}</p>
                        @if($order->cancel_note)
                            <p class="text-slate-500 mt-1">{{ $order->cancel_note }}</p>
                        @endif
                        @if($order->cancelled_at)
                            <p class="text-[11px] text-slate-400 mt-1">{{ $order->cancelled_at->format('H:i d/m/Y') }}</p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
