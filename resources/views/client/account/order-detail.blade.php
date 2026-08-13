@extends('layouts.app')

@section('title', 'Đơn hàng #' . $order->code . ' - Veloce')

@php
$statusColors = $statusColors ?? \App\Models\Order::statusColors();
$cs = $currentStatus;
$csId = $cs ? $cs->id : null;
$csColors = $csId && isset($statusColors[$csId]) ? $statusColors[$csId] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];

// Status steps for visual timeline
$statusSteps = [
    ['id'=>1, 'name'=>'Chờ xác nhận',   'icon'=>'clock'],
    ['id'=>2, 'name'=>'Chờ lấy hàng',   'icon'=>'box'],
    ['id'=>9, 'name'=>'Gửi hàng',        'icon'=>'send'],
    ['id'=>3, 'name'=>'Đang giao',       'icon'=>'truck'],
    ['id'=>4, 'name'=>'Đã giao',         'icon'=>'package-check'],
    ['id'=>10,'name'=>'Nhận thành công', 'icon'=>'check-circle-2'],
];
$doneIds = $order->statusHistories->pluck('order_status_id')->unique()->toArray();
@endphp

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('my-orders') }}" class="hover:text-indigo-600 transition">Đơn hàng của tôi</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">#{{ $order->code }}</span>
    </nav>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-xl font-black text-slate-800">#{{ $order->code }}</h1>
                    @if($cs)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $csColors['bg'] }} {{ $csColors['text'] }} {{ $csColors['border'] }}">
                            <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                            {{ $cs->name }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400">Đặt lúc {{ $order->created_at->format('H:i, d/m/Y') }}</p>
            </div>
            <div class="flex gap-2">
                {{-- Cancel button --}}
                @if($csId == 1)
                    <button onclick="openCancelModal()"
                        class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Hủy đơn hàng
                    </button>
                @endif
                {{-- Confirm button --}}
                @if($csId == 4)
                    <form action="{{ route('client.orders.confirm', $order->id) }}" method="POST"
                          onsubmit="return confirm('Xác nhận bạn đã nhận được hàng?')">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> Đã nhận hàng
                        </button>
                    </form>
                @endif
                {{-- Return / Refund button --}}
                @if(in_array($csId, [4, 10]) && !$order->refunds()->whereIn('status', ['pending', 'approved', 'completed'])->exists())
                    <a href="{{ route('client.orders.refund', $order->id) }}"
                       class="flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-indigo-600 border border-indigo-200 rounded-xl hover:bg-indigo-50 transition">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Trả hàng / Hoàn tiền
                    </a>
                @endif
            </div>
        </div>

        {{-- Status Progress Bar (for normal flow) --}}
        @if(!in_array($csId, [5,6,7,8]))
        <div class="mt-6 overflow-x-auto">
            <div class="flex items-center min-w-max gap-0">
                @foreach($statusSteps as $i => $step)
                @php
                    $isDone    = in_array($step['id'], $doneIds);
                    $isCurrent = $step['id'] === $csId;
                @endphp
                <div class="flex items-center">
                    <div class="flex flex-col items-center gap-1">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center transition
                            {{ $isDone || $isCurrent ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-slate-100 text-slate-400' }}">
                            <i data-lucide="{{ $step['icon'] }}" class="w-4 h-4"></i>
                        </div>
                        <span class="text-[10px] font-medium text-center w-16 leading-tight {{ $isDone || $isCurrent ? 'text-indigo-600' : 'text-slate-400' }}">{{ $step['name'] }}</span>
                    </div>
                    @if(!$loop->last)
                    <div class="h-0.5 w-12 mx-1 mb-4 {{ $isDone ? 'bg-indigo-600' : 'bg-slate-200' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @elseif($csId == 8)
        <div class="mt-4 flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
            <i data-lucide="x-circle" class="w-5 h-5 text-red-500 flex-shrink-0"></i>
            <div>
                <p class="text-sm font-bold text-red-700">Đơn hàng đã bị hủy</p>
                @if($order->cancel_reason)
                    <p class="text-xs text-red-500">Lý do: {{ $order->cancel_reason }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Right sidebar --}}
        <div class="space-y-6">

            {{-- Delivery Info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-bold text-sm text-slate-800">Thông tin giao hàng</h3>
                    @if($csId == 1)
                        <button onclick="openEditAddressModal()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center gap-1">
                            <i data-lucide="edit-3" class="w-3 h-3"></i> Thay đổi
                        </button>
                    @endif
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex items-start gap-2 text-slate-700">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                        <span class="font-semibold">{{ $order->fullname }}</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                        <span>{{ $order->phone_number }}</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                        <span class="break-all">{{ $order->email }}</span>
                    </div>
                    <div class="flex items-start gap-2 text-slate-700">
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                        <span>{{ $order->address }}</span>
                    </div>
                    @if($order->note)
                    <div class="flex items-start gap-2 text-slate-700 pt-2 border-t border-slate-100">
                        <i data-lucide="message-square" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                        <span>{{ $order->note }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Thanh toán</h3>
                </div>
                <div class="p-5 space-y-2 text-xs">
                    <div class="flex items-center justify-between text-slate-700">
                        <span class="text-slate-500">Phương thức</span>
                        <span class="font-semibold">{{ $order->payment->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-slate-700">
                        <span class="text-slate-500">Tình trạng</span>
                        @if($order->is_paid)
                            <span class="text-emerald-600 font-bold">✓ Đã thanh toán</span>
                        @else
                            <span class="text-amber-600 font-bold">⏳ Chưa thanh toán</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-slate-700">
                        <span class="text-slate-500">Vận chuyển</span>
                        <span class="font-semibold">{{ $order->shipping_type == 'express' ? 'Nhanh' : 'Tiêu chuẩn' }}</span>
                    </div>
                </div>
            </div>

            {{-- Back button --}}
            <a href="{{ route('my-orders') }}"
               class="flex items-center justify-center gap-2 w-full py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-semibold hover:bg-slate-50 transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại danh sách
            </a>
        </div>
        {{-- Products & Total --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Refund details --}}
            @if($order->refunds && $order->refunds->count())
                @php
                    $refund = $order->refunds->sortByDesc('created_at')->first();
                    $refColors = \App\Models\Refund::statusColors();
                    $refLabels = \App\Models\Refund::statusLabels();
                    $refColor = $refColors[$refund->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4 text-indigo-600"></i>
                            <h3 class="font-bold text-sm text-slate-800">Yêu cầu Trả hàng / Hoàn tiền</h3>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $refColor['bg'] }} {{ $refColor['text'] }} {{ $refColor['border'] }}">
                            {{ $refLabels[$refund->status] ?? $refund->status }}
                        </span>
                    </div>
                    <div class="p-6 space-y-4 text-xs">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl">
                            <div>
                                <p class="text-slate-400 font-medium">Số tài khoản</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $refund->bank_account }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 font-medium">Chủ tài khoản</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $refund->user_bank_name }}</p>
                            </div>
                            <div>
                                <p class="text-slate-400 font-medium">Ngân hàng</p>
                                <p class="font-bold text-slate-800 mt-0.5">{{ $refund->bank_name }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-slate-400 font-medium mb-1">Sản phẩm trả lại</p>
                            <div class="border border-slate-100 rounded-xl divide-y divide-slate-50">
                                @foreach($refund->items as $refItem)
                                    <div class="p-3 flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $refItem->name }}</p>
                                            @if($refItem->name_variant)
                                                <p class="text-[10px] text-slate-400">{{ $refItem->name_variant }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-indigo-600">x{{ $refItem->quantity }}</p>
                                            <p class="text-[10px] text-slate-400">{{ number_format($refItem->effective_price, 0, ',', '.') }}đ/sp</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-slate-400 font-medium">Lý do từ khách hàng</p>
                                <p class="text-slate-700 mt-1 italic">"{{ $refund->reason }}"</p>
                                @if($refund->reason_image)
                                    <div class="mt-3">
                                        <p class="text-slate-400 font-medium mb-1">Hình ảnh minh chứng gửi kèm:</p>
                                        <div class="w-32 h-32 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                                            <a href="{{ asset('storage/' . $refund->reason_image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $refund->reason_image) }}" class="w-full h-full object-cover">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                @if($refund->aadmin_reason)
                                    <p class="text-slate-400 font-medium font-semibold">Phản hồi từ Admin</p>
                                    <p class="text-slate-750 mt-1 font-semibold text-red-600">"{{ $refund->aadmin_reason }}"</p>
                                @endif
                                @if($order->img_refunded_money)
                                    <div class="mt-3">
                                        <p class="text-slate-400 font-medium mb-1">Ảnh giao dịch hoàn tiền:</p>
                                        <div class="w-32 h-32 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                                            <a href="{{ asset('storage/' . $order->img_refunded_money) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $order->img_refunded_money) }}" class="w-full h-full object-cover">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-between items-center pt-3 border-t border-slate-100 font-bold text-sm">
                            <span class="text-slate-600">Tổng tiền hoàn:</span>
                            <span class="text-indigo-600 text-base">{{ number_format($refund->total_amount, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Products --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Sản phẩm đã đặt</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($order->items as $item)
                    @php
                        $img = null;
                        if ($item->productVariant && $item->productVariant->image) {
                            $img = asset($item->productVariant->image);
                        } elseif ($item->product && $item->product->image) {
                            $img = asset($item->product->image);
                        } elseif ($item->product && $item->product->images->first()) {
                            $img = asset('storage/' . $item->product->images->first()->url);
                        }
                    @endphp
                    <div class="flex items-center gap-4 px-6 py-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                            <img src="{{ $img ?? 'https://placehold.co/64x64/f1f5f9/94a3b8?text=?' }}"
                                 alt="{{ $item->name }}" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-sm text-slate-800 line-clamp-1">{{ $item->name }}</h4>
                            @if($item->name_variant)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $item->name_variant }}: {{ $item->attributes_variant }}</p>
                            @endif
                            <p class="text-xs text-slate-400">x{{ $item->quantity }}</p>
                            @if($csId == 10 && auth()->user()->role === 'client')
                                <div class="mt-2">
                                    @if($item->review)
                                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="flex text-amber-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-3.5 h-3.5 {{ $i <= $item->review->rating ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                                @endfor
                                            </span>
                                            <span class="ml-1">Đã đánh giá</span>
                                        </div>
                                    @else
                                        <button type="button" 
                                                onclick="openReviewModal({{ $item->product_id }}, {{ $order->id }}, {{ $item->id }}, '{{ addslashes($item->name) }}', '{{ addslashes($item->name_variant ?? '') }}', '{{ $img ?? 'https://placehold.co/64x64/f1f5f9/94a3b8?text=?' }}')"
                                                class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.907c.961 0 1.36 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                            Viết đánh giá
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            @if($item->old_price && $item->old_price > $item->effective_price)
                                <p class="text-[11px] text-slate-300 line-through">{{ number_format($item->old_price, 0, ',', '.') }}đ</p>
                            @endif
                            <p class="font-bold text-indigo-600 text-sm">{{ number_format($item->effective_price, 0, ',', '.') }}đ</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Totals --}}
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 space-y-2">
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Tạm tính</span>
                        <span>{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between text-xs text-emerald-600">
                        <span>Giảm giá {{ $order->coupon ? '(' . $order->coupon->code . ')' : '' }}</span>
                        <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-xs text-slate-600">
                        <span>Phí vận chuyển</span>
                        <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                    </div>
                    <div class="flex justify-between font-black text-sm text-slate-800 pt-2 border-t border-slate-200">
                        <span>Tổng cộng</span>
                        <span class="text-indigo-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</span>
                    </div>
                </div>
            </div>

            {{-- Status History --}}
            @if($order->statusHistories->count())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Lịch sử cập nhật</h3>
                </div>
                <div class="px-6 py-5">
                    <ol class="relative border-l border-slate-200 ml-3 space-y-5">
                        @foreach($order->statusHistories->sortByDesc('created_at')->values() as $index => $history)
                        @php
                            $hColors = isset($statusColors[$history->order_status_id]) ? $statusColors[$history->order_status_id] : ['bg'=>'bg-slate-100','text'=>'text-slate-600'];
                        @endphp
                        <li class="ml-6 history-item {{ $index >= 5 ? 'hidden' : '' }}">
                            <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full ring-4 ring-white {{ $history->is_current ? 'bg-indigo-600' : 'bg-slate-200' }}">
                                <i data-lucide="{{ $history->is_current ? 'check' : 'dot' }}" class="w-3 h-3 {{ $history->is_current ? 'text-white' : 'text-slate-400' }}"></i>
                            </span>
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $hColors['bg'] }} {{ $hColors['text'] }}">
                                        {{ $history->status->name ?? 'N/A' }}
                                    </span>
                                    @if($history->note)
                                        <p class="text-xs text-slate-500 mt-1">{{ $history->note }}</p>
                                    @endif
                                </div>
                                <time class="text-[11px] text-slate-400 whitespace-nowrap">{{ $history->created_at->format('H:i d/m/Y') }}</time>
                            </div>
                        </li>
                        @endforeach
                    </ol>

                    @if($order->statusHistories->count() > 5)
                        <div class="mt-4 text-center">
                            <button id="btnShowMoreHistory" onclick="showAllHistory()" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition">
                                Xem thêm ({{ $order->statusHistories->count() - 5 }})
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800">Hủy đơn hàng #{{ $order->code }}</h3>
            <button onclick="closeCancelModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
            </button>
        </div>
        <form action="{{ route('client.orders.cancel', $order->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="flex items-center gap-3 p-3 bg-red-50 rounded-xl border border-red-100">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 flex-shrink-0"></i>
                <p class="text-xs text-red-700">Sau khi hủy, đơn hàng không thể khôi phục. Bạn có chắc chắn muốn hủy?</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lý do hủy <span class="text-red-500">*</span></label>
                <select name="cancel_reason" required
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition bg-white">
                    <option value="">-- Chọn lý do --</option>
                    <option>Tôi muốn thay đổi địa chỉ giao hàng</option>
                    <option>Tôi muốn thay đổi sản phẩm</option>
                    <option>Tôi tìm được nơi mua rẻ hơn</option>
                    <option>Không còn nhu cầu mua</option>
                    <option>Đặt nhầm sản phẩm</option>
                    <option>Lý do khác</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ghi chú thêm</label>
                <textarea name="cancel_note" rows="2" placeholder="Nhập thêm ghi chú..."
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeCancelModal()"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Không hủy
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition">
                    Xác nhận hủy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Address Modal --}}
<div id="editAddressModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-black text-slate-800">Cập nhật địa chỉ nhận hàng</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Vui lòng chọn Tỉnh/Thành, Quận/Huyện, Phường/Xã từ gợi ý</p>
            </div>
            <button onclick="closeEditAddressModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
            </button>
        </div>
        <form action="{{ route('client.orders.updateAddress', $order->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Họ và tên <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname', $order->fullname) }}" required
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Số điện thoại <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $order->phone_number) }}" required
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $order->email) }}" required
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Province Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tỉnh / Thành phố <span class="text-rose-500">*</span></label>
                    <input type="text" id="modalProvinceInput" name="province" required autocomplete="off" placeholder="Gõ để tìm kiếm..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <div id="modalProvinceList" class="absolute left-0 w-full max-h-40 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>

                {{-- District Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Quận / Huyện <span class="text-rose-500">*</span></label>
                    <input type="text" id="modalDistrictInput" name="district" required disabled autocomplete="off" placeholder="Chọn tỉnh trước..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
                    <div id="modalDistrictList" class="absolute left-0 w-full max-h-40 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>

                {{-- Ward Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Phường / Xã <span class="text-rose-500">*</span></label>
                    <input type="text" id="modalWardInput" name="ward" required disabled autocomplete="off" placeholder="Chọn huyện trước..." 
                        class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
                    <div id="modalWardList" class="absolute left-0 w-full max-h-40 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>
            </div>

            {{-- Street --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tên đường, Tòa nhà, Số nhà <span class="text-rose-500">*</span></label>
                <input type="text" id="modalStreetInput" name="street" required disabled placeholder="Nhập số nhà, tên đường cụ thể..." 
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
            </div>

            {{-- Compiled Address (Gửi đi chi tiết địa chỉ đầy đủ) --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Địa chỉ hiển thị đầy đủ</label>
                <input type="text" id="modalCompiledAddressInput" name="address" value="{{ old('address', $order->address) }}" required readonly placeholder="Địa chỉ sẽ tự động tạo..." 
                    class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditAddressModal()"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Hủy bỏ
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                    Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Review Modal --}}
<div id="reviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-black text-slate-800">Đánh giá sản phẩm</h3>
                <p class="text-[10px] text-slate-400 mt-0.5">Chia sẻ cảm nhận của bạn về sản phẩm</p>
            </div>
            <button onclick="closeReviewModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        
        <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="order_id" id="review_order_id">
            <input type="hidden" name="order_item_id" id="review_order_item_id">
            <input type="hidden" name="rating" id="review_rating_val" value="5">

            {{-- Product Info Header in Modal --}}
            <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl">
                <div class="w-12 h-12 rounded-lg overflow-hidden border border-slate-100 flex-shrink-0 bg-white">
                    <img id="review_product_img" src="" class="w-full h-full object-cover">
                </div>
                <div class="min-w-0">
                    <h4 id="review_product_name" class="font-bold text-xs text-slate-800 line-clamp-1"></h4>
                    <p id="review_product_variant" class="text-[10px] text-slate-400 mt-0.5"></p>
                </div>
            </div>

            {{-- Star Rating --}}
            <div class="text-center space-y-1.5">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Mức độ hài lòng</label>
                <div class="flex justify-center gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})" data-star-idx="{{ $i }}" class="text-amber-400 hover:scale-110 transition focus:outline-none">
                            <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </button>
                    @endfor
                </div>
                <span id="rating_label" class="text-xs font-bold text-amber-600 block">Cực kỳ hài lòng</span>
            </div>

            {{-- Review Content --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Đánh giá chi tiết <span class="text-rose-500">*</span></label>
                <textarea name="review_text" rows="4" required minlength="5" placeholder="Sản phẩm dùng tốt không? Kiểu dáng, chất liệu thế nào? Hãy chia sẻ ý kiến của bạn nhé..."
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none"></textarea>
            </div>

            {{-- Image Upload --}}
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Thêm hình ảnh thực tế (Tối đa 5 ảnh)</label>
                <input type="file" name="images[]" multiple accept="image/*" id="review_images_input"
                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                <div id="image_preview_container" class="flex gap-2 overflow-x-auto mt-2 pb-1"></div>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeReviewModal()"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Hủy bỏ
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition">
                    Gửi đánh giá
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeCancelModal() {
    const modal = document.getElementById('cancelModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) closeCancelModal();
});

function openEditAddressModal() {
    const modal = document.getElementById('editAddressModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closeEditAddressModal() {
    const modal = document.getElementById('editAddressModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
document.getElementById('editAddressModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditAddressModal();
});

function showAllHistory() {
    document.querySelectorAll('.history-item').forEach(item => item.classList.remove('hidden'));
    document.getElementById('btnShowMoreHistory').style.display = 'none';
}

const starLabels = {
    1: 'Rất không hài lòng',
    2: 'Không hài lòng',
    3: 'Bình thường',
    4: 'Hài lòng',
    5: 'Cực kỳ hài lòng'
};

function openReviewModal(productId, orderId, orderItemId, productName, productVariantName, productImg) {
    document.getElementById('review_order_id').value = orderId;
    document.getElementById('review_order_item_id').value = orderItemId;
    document.getElementById('review_product_name').innerText = productName;
    document.getElementById('review_product_variant').innerText = productVariantName ? 'Biến thể: ' + productVariantName : '';
    document.getElementById('review_product_img').src = productImg;
    
    // reset values
    setRating(5);
    document.getElementById('review_images_input').value = '';
    document.getElementById('image_preview_container').innerHTML = '';
    document.querySelector('#reviewModal textarea').value = '';
    
    const modal = document.getElementById('reviewModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) closeReviewModal();
});

function setRating(rating) {
    document.getElementById('review_rating_val').value = rating;
    document.getElementById('rating_label').innerText = starLabels[rating];
    
    for (let i = 1; i <= 5; i++) {
        const starBtn = document.querySelector(`[data-star-idx="${i}"]`);
        if (starBtn) {
            const svg = starBtn.querySelector('svg');
            if (i <= rating) {
                svg.classList.remove('text-slate-200');
                svg.classList.add('text-amber-400', 'fill-current');
            } else {
                svg.classList.add('text-slate-200');
                svg.classList.remove('text-amber-400', 'fill-current');
            }
        }
    }
}

// Preview uploaded images
document.getElementById('review_images_input').addEventListener('change', function(e) {
    const previewContainer = document.getElementById('image_preview_container');
    previewContainer.innerHTML = '';
    
    const files = e.target.files;
    if (files.length > 5) {
        alert('Bạn chỉ có thể chọn tối đa 5 hình ảnh.');
        this.value = '';
        return;
    }
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = function(evt) {
            const div = document.createElement('div');
            div.className = 'w-12 h-12 rounded-lg overflow-hidden border border-slate-200 flex-shrink-0 bg-slate-50 relative';
            div.innerHTML = `<img src="${evt.target.result}" class="w-full h-full object-cover">`;
            previewContainer.appendChild(div);
        }
        reader.readAsDataURL(file);
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const provinceInput = document.getElementById('modalProvinceInput');
    const provinceList = document.getElementById('modalProvinceList');
    const districtInput = document.getElementById('modalDistrictInput');
    const districtList = document.getElementById('modalDistrictList');
    const wardInput = document.getElementById('modalWardInput');
    const wardList = document.getElementById('modalWardList');
    const streetInput = document.getElementById('modalStreetInput');
    const compiledInput = document.getElementById('modalCompiledAddressInput');

    let allProvinces = [];
    let districts = [];
    let wards = [];

    let selectedProvinceCode = null;
    let selectedDistrictCode = null;

    // Tải danh sách tỉnh thành
    fetch('https://provinces.open-api.vn/api/?depth=1')
        .then(res => res.json())
        .then(data => {
            allProvinces = data;
        })
        .catch(err => {
            console.error('Không thể tải dữ liệu Tỉnh/Thành phố:', err);
        });

    function renderSuggestions(inputElement, listElement, getDataList, onSelect) {
        inputElement.addEventListener('focus', () => {
            filterAndShow();
        });

        document.addEventListener('click', (e) => {
            if (!inputElement.contains(e.target) && !listElement.contains(e.target)) {
                listElement.classList.add('hidden');
            }
        });

        inputElement.addEventListener('input', () => {
            filterAndShow();
        });

        function filterAndShow() {
            const dataList = getDataList();
            const query = inputElement.value.trim().toLowerCase();
            const filtered = dataList.filter(item => item.name.toLowerCase().includes(query));

            if (filtered.length > 0) {
                listElement.innerHTML = filtered.map(item => `
                    <div class="px-4 py-2 text-xs text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer font-medium transition" data-code="${item.code}">
                        ${item.name}
                    </div>
                `).join('');
                listElement.classList.remove('hidden');

                listElement.querySelectorAll('[data-code]').forEach(el => {
                    el.addEventListener('click', () => {
                        const code = el.getAttribute('data-code');
                        const name = el.innerText.trim();
                        inputElement.value = name;
                        listElement.classList.add('hidden');
                        onSelect(code, name);
                    });
                });
            } else {
                listElement.innerHTML = `<div class="px-4 py-2 text-xs text-slate-400">Không tìm thấy kết quả</div>`;
                listElement.classList.remove('hidden');
            }
        }
    }

    renderSuggestions(provinceInput, provinceList, () => allProvinces, (code, name) => {
        selectedProvinceCode = code;
        
        districtInput.value = '';
        districtInput.disabled = true;
        districtInput.placeholder = 'Đang tải danh sách...';
        districtList.classList.add('hidden');

        wardInput.value = '';
        wardInput.disabled = true;
        wardInput.placeholder = 'Chọn huyện trước...';
        wardList.classList.add('hidden');

        streetInput.value = '';
        streetInput.disabled = true;

        updateCompiledAddress();

        fetch(`https://provinces.open-api.vn/api/p/${code}?depth=2`)
            .then(res => res.json())
            .then(data => {
                districts = data.districts || [];
                districtInput.disabled = false;
                districtInput.placeholder = 'Gõ để tìm quận/huyện...';
                
                setupDistrictSuggestions();
            })
            .catch(err => {
                console.error('Lỗi tải danh sách Quận/Huyện:', err);
                districtInput.placeholder = 'Không thể tải';
            });
    });

    function setupDistrictSuggestions() {
        renderSuggestions(districtInput, districtList, () => districts, (code, name) => {
            selectedDistrictCode = code;

            wardInput.value = '';
            wardInput.disabled = true;
            wardInput.placeholder = 'Đang tải danh sách...';
            wardList.classList.add('hidden');

            streetInput.value = '';
            streetInput.disabled = true;

            updateCompiledAddress();

            fetch(`https://provinces.open-api.vn/api/d/${code}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    wards = data.wards || [];
                    wardInput.disabled = false;
                    wardInput.placeholder = 'Gõ để tìm phường/xã...';

                    setupWardSuggestions();
                })
                .catch(err => {
                    console.error('Lỗi tải danh sách Phường/Xã:', err);
                    wardInput.placeholder = 'Không thể tải';
                });
        });
    }

    function setupWardSuggestions() {
        renderSuggestions(wardInput, wardList, () => wards, (code, name) => {
            streetInput.disabled = false;
            streetInput.placeholder = 'Nhập số nhà, ngõ ngách, tên đường...';
            
            updateCompiledAddress();
        });
    }

    streetInput.addEventListener('input', updateCompiledAddress);

    function updateCompiledAddress() {
        const p = provinceInput.value.trim();
        const d = districtInput.value.trim();
        const w = wardInput.value.trim();
        const s = streetInput.value.trim();

        const parts = [];
        if (s) parts.push(s);
        if (w) parts.push(w);
        if (d) parts.push(d);
        if (p) parts.push(p);

        compiledInput.value = parts.join(', ');
    }
});
</script>
@endsection
