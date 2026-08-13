@extends('layouts.app')

@section('title', 'Đơn hàng của tôi - Veloce')

@php
$statusColors = $statusColors ?? \App\Models\Order::statusColors();
$tabs = [
    'all' => 'Tất cả',
    1 => 'Chờ xác nhận',
    2 => 'Chờ lấy hàng',
    3 => 'Đang giao',
    4 => 'Giao hàng thành công',
    5 => 'Chờ trả hàng',
    6 => 'Đã trả hàng',
    7 => 'Hoàn tiền',
    8 => 'Đã hủy',
    10 => 'Nhận hàng thành công',
];
$activeTab = request('status') ?: 'all';
@endphp

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Đơn hàng của tôi</span>
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

    {{-- Status Tabs --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="border-b border-slate-100 overflow-x-auto">
            <div class="flex min-w-max px-4">
                @foreach($tabs as $tabKey => $tabLabel)
                @php
                    $isActive = (string)$activeTab === (string)$tabKey;
                    $count = $tabKey === 'all' ? $counts['all'] : ($counts[$tabKey] ?? 0);
                @endphp
                <a href="{{ route('my-orders', array_merge(request()->except(['status','page']), $tabKey !== 'all' ? ['status'=>$tabKey] : [])) }}"
                   class="flex items-center gap-1.5 px-3 py-3.5 text-xs font-semibold border-b-2 transition whitespace-nowrap
                       {{ $isActive ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
                    {{ $tabLabel }}
                    @if($count > 0)
                        <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold {{ $isActive ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">{{ $count }}</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>

        {{-- Search --}}
        <div class="p-4 border-b border-slate-100">
            <form method="GET" action="{{ route('my-orders') }}" class="flex gap-3">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Tìm theo mã đơn hàng..."
                        class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">Tìm</button>
            </form>
        </div>

        {{-- Order List --}}
        <div class="p-4 space-y-4">
            @forelse($orders as $order)
            @php
                $cs = $order->statuses->first();
                $csId = $cs ? $cs->id : null;
                $colors = $csId && isset($statusColors[$csId]) ? $statusColors[$csId] : ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                $firstItem = $order->items->first();
                $moreItems = $order->items->count() - 1;
                $img = null;
                if ($firstItem && $firstItem->productVariant && $firstItem->productVariant->image) {
                    $img = asset($firstItem->productVariant->image);
                } elseif ($firstItem && $firstItem->product && $firstItem->product->image) {
                    $img = asset($firstItem->product->image);
                } elseif ($firstItem && $firstItem->product && $firstItem->product->images->first()) {
                    $img = asset('storage/' . $firstItem->product->images->first()->url);
                }
            @endphp
            <div class="border border-slate-100 rounded-2xl overflow-hidden hover:shadow-md transition-shadow group">
                {{-- Card Header --}}
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50/70 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="font-black text-sm text-indigo-600">#{{ $order->code }}</span>
                        <span class="text-xs text-slate-400">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($cs)
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $colors['bg'] }} {{ $colors['text'] }} {{ $colors['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70"></span>
                            {{ $cs->name }}
                        </span>
                    @endif
                </div>

                {{-- Product Row --}}
                <div class="px-5 py-4 flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                        <img src="{{ $img ?? 'https://placehold.co/64x64/f1f5f9/94a3b8?text=?' }}"
                             alt="{{ $firstItem->name ?? '' }}" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-slate-800 truncate">{{ $firstItem->name ?? 'Sản phẩm' }}</p>
                        @if($firstItem && $firstItem->name_variant)
                            <p class="text-xs text-slate-400">{{ $firstItem->name_variant }}: {{ $firstItem->attributes_variant }}</p>
                        @endif
                        @if($moreItems > 0)
                            <p class="text-xs text-slate-400 mt-0.5">+ {{ $moreItems }} sản phẩm khác</p>
                        @endif
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="font-black text-sm text-indigo-600">{{ number_format($order->grand_total, 0, ',', '.') }}đ</p>
                        <p class="text-[11px] text-slate-400">{{ $order->items->sum('quantity') }} sản phẩm</p>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="flex items-center justify-between px-5 py-3 bg-slate-50/40 border-t border-slate-100">
                    <p class="text-[11px] text-slate-400">{{ $order->payment->name ?? 'N/A' }}</p>
                    <div class="flex items-center gap-2">
                        {{-- Cancel button (only status 1) --}}
                        @if($csId == 1)
                            <button onclick="openCancelModal({{ $order->id }})"
                                class="px-3 py-1.5 text-xs font-semibold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition">
                                Hủy đơn
                            </button>
                        @endif

                        {{-- Confirm button (only status 4) --}}
                        @if($csId == 4)
                            <form action="{{ route('client.orders.confirm', $order->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Xác nhận bạn đã nhận được hàng?')">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 text-xs font-semibold text-emerald-600 border border-emerald-200 rounded-xl hover:bg-emerald-50 transition">
                                    Đã nhận hàng
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('client.orders.show', $order->id) }}"
                           class="px-4 py-1.5 text-xs font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                            Chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-16 flex flex-col items-center gap-4 text-slate-400">
                <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                </svg>
                <p class="font-medium">Không có đơn hàng nào</p>
                <a href="{{ route('shop') }}" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition">
                    Mua sắm ngay
                </a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800">Hủy đơn hàng</h3>
            <button onclick="closeCancelModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <i data-lucide="x" class="w-4 h-4 text-slate-500"></i>
            </button>
        </div>
        <form id="cancelForm" method="POST" class="p-6 space-y-4">
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
                <textarea name="cancel_note" rows="2" placeholder="Nhập thêm ghi chú (nếu có)..."
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

<script>
function openCancelModal(orderId) {
    document.getElementById('cancelForm').action = '/my-orders/' + orderId + '/cancel';
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
</script>
@endsection
