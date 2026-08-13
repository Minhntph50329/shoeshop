@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn - Veloce')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Giỏ hàng của bạn</h1>
            <p class="text-xs text-slate-500">Quản lý các sản phẩm bạn đã chọn trước khi thanh toán</p>
        </div>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 hover:text-indigo-700">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Tiếp tục mua sắm
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-xs flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 shrink-0 text-emerald-500"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-xs flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0 text-rose-500"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    @if(isset($cart) && $cart->items->count() > 0)
        <!-- Separate Standalone Bulk Delete Form (No HTML Nesting) -->
        <form action="{{ route('cart.removeMultiple') }}" method="POST" id="bulk_delete_form" onsubmit="return confirm('Bạn có chắc muốn xóa các sản phẩm đã chọn?')">
            @csrf
            @method('DELETE')
        </form>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left: Cart Items Table -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Table Top Header Action Bar -->
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex items-center justify-between gap-4 mb-4">
                    <label class="flex items-center gap-2 cursor-pointer font-bold text-xs text-slate-700 select-none">
                        <input type="checkbox" id="select_all" checked onchange="toggleSelectAll(this)" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                        <span>Chọn tất cả ({{ $cart->items->count() }} sản phẩm)</span>
                    </label>

                    <button type="submit" form="bulk_delete_form" id="bulk_delete_btn" disabled class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-md opacity-40 cursor-not-allowed transition flex items-center gap-1.5">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        <span>Xóa mục đã chọn (<span id="selected_count">0</span>)</span>
                    </button>
                </div>

                <!-- Cart Items List -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 overflow-hidden">
                    <div class="divide-y divide-slate-100">
                        @foreach($cart->items as $item)
                            @php
                                $maxStock = $item->variant ? $item->variant->stock : ($item->product ? $item->product->stock : 0);
                            @endphp
                            <div class="p-6 flex flex-col sm:flex-row items-center justify-between gap-6 hover:bg-slate-50/50 transition">
                                <div class="flex items-center gap-4 w-full sm:w-auto">
                                    <!-- Row Checkbox linked to bulk_delete_form -->
                                    <input type="checkbox" name="item_ids[]" checked value="{{ $item->id }}" data-price="{{ $item->price_at_time }}" data-quantity="{{ $item->quantity }}" form="bulk_delete_form" onchange="updateSelectedCount()" class="item-checkbox w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500 cursor-pointer">

                                    <div class="w-20 h-20 bg-slate-100 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center border border-slate-200">
                                        @php
                                            $itemImage = ($item->variant && $item->variant->image) 
                                                ? $item->variant->image 
                                                : ($item->product->image ?? null);
                                        @endphp
                                        @if($itemImage)
                                            <img src="{{ asset($itemImage) }}" alt="{{ $item->product->name ?? 'Sản phẩm' }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="package" class="w-8 h-8 text-slate-400"></i>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <h3 class="font-bold text-sm text-slate-900 leading-snug">
                                            <a href="{{ route('products.show', $item->product->id) }}" class="hover:text-indigo-600 transition">
                                                {{ $item->product->name ?? 'Sản phẩm không tồn tại' }}
                                            </a>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if($item->variant)
                                                <span class="inline-block px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold">
                                                    SKU: {{ $item->variant->sku }}
                                                </span>
                                                @foreach($item->variant->attributeValues as $val)
                                                    <span class="inline-block px-2.5 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-bold">
                                                        {{ $val->attribute->name }}: {{ $val->value }}
                                                    </span>
                                                @endforeach
                                            @endif
                                            <span class="inline-block px-2.5 py-0.5 {{ $maxStock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} rounded-full text-[10px] font-bold">
                                                Tồn kho: {{ $maxStock }}
                                            </span>
                                        </div>
                                        <p class="text-xs font-bold text-indigo-600 sm:hidden">
                                            {{ number_format($item->price_at_time, 0, ',', '.') }} đ
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end gap-6 w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                                    <!-- Price -->
                                    <div class="hidden sm:block text-right">
                                        <span class="text-xs text-slate-400 block font-medium">Đơn giá</span>
                                        <span class="text-xs font-bold text-slate-800">
                                            {{ number_format($item->price_at_time, 0, ',', '.') }} đ
                                        </span>
                                    </div>

                                    <!-- Quantity Update Controls (- and +) -->
                                    <div class="flex items-center">
                                        <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-slate-50 shadow-sm">
                                            <!-- Decrement Form -->
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="quantity" value="{{ max(1, $item->quantity - 1) }}">
                                                <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white transition font-black text-sm active:scale-95">
                                                    -
                                                </button>
                                            </form>

                                            <span class="w-10 text-center text-xs font-black text-slate-900 bg-white py-1.5 border-x border-slate-200">
                                                {{ $item->quantity }}
                                            </span>

                                            <!-- Increment Form (with stock boundary check) -->
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="quantity" value="{{ $item->quantity + 1 }}">
                                                <button type="submit" {{ $item->quantity >= $maxStock ? 'disabled title=Đã_đạt_giới_hạn_tồn_kho' : '' }} 
                                                    class="w-8 h-8 flex items-center justify-center text-slate-700 hover:bg-indigo-600 hover:text-white disabled:opacity-30 disabled:hover:bg-slate-50 disabled:hover:text-slate-700 disabled:cursor-not-allowed transition font-black text-sm active:scale-95">
                                                    +
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="text-right">
                                        <span class="text-xs text-slate-400 block font-medium sm:hidden">Thành tiền</span>
                                        <span class="text-sm font-black text-indigo-600">
                                            {{ number_format($item->subtotal, 0, ',', '.') }} đ
                                        </span>
                                    </div>

                                    <!-- Single Item Remove -->
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Xóa khỏi giỏ hàng">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa TẤT CẢ sản phẩm trong giỏ hàng?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 text-slate-600 rounded-xl text-xs font-bold transition">
                            <i data-lucide="trash" class="w-4 h-4"></i> Dọn dẹp tất cả giỏ hàng
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right: Summary Sidebar -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                    <h2 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-4">Tóm tắt đơn hàng</h2>

                    <div class="space-y-3 text-xs">
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tổng số lượng sản phẩm:</span>
                            <span class="font-bold text-slate-900">{{ $cart->total_quantity }} món</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Tạm tính:</span>
                            <span class="font-bold text-slate-900">{{ number_format($cart->total, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-600">
                            <span>Phí vận chuyển:</span>
                            <span class="font-bold text-emerald-600">Miễn phí</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-bold uppercase text-slate-500 block">Tổng thanh toán</span>
                            <small class="text-[10px] text-slate-400">(Đã bao gồm VAT)</small>
                        </div>
                        <span class="text-xl font-black text-indigo-600">{{ number_format($cart->total, 0, ',', '.') }} đ</span>
                    </div>

                    <button type="button" onclick="proceedToCheckout()" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 transition active:scale-[0.99]">
                        <span>Tiến hành thanh toán</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart State -->
        <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-sm text-center max-w-md mx-auto space-y-4">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto">
                <i data-lucide="shopping-bag" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Giỏ hàng của bạn đang trống!</h3>
            <p class="text-xs text-slate-500 leading-relaxed">Hãy khám phá hàng ngàn sản phẩm giày thời trang chất lượng cao tại Veloce Store.</p>
            <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition">
                <span>Khám phá ngay</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    @endif
</div>

<!-- JS Checkbox Select All & Bulk Delete Handler -->
<script>
function toggleSelectAll(master) {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateSelectedCount();
}

function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    const count = checkboxes.length;
    const btn = document.getElementById('bulk_delete_btn');
    const countSpan = document.getElementById('selected_count');
    const master = document.getElementById('select_all');
    const allCheckboxes = document.querySelectorAll('.item-checkbox');

    if (countSpan) countSpan.textContent = count;

    // Recalculate selected totals
    let totalQty = 0;
    let totalPrice = 0;
    checkboxes.forEach(cb => {
        const qty = parseInt(cb.getAttribute('data-quantity')) || 0;
        const price = parseFloat(cb.getAttribute('data-price')) || 0;
        totalQty += qty;
        totalPrice += (qty * price);
    });

    // Update sidebar text
    const sidebarQty = document.querySelector('.space-y-3.text-xs div:first-child span:last-child');
    if (sidebarQty) sidebarQty.textContent = totalQty + ' món';
    
    const sidebarSubtotal = document.querySelector('.space-y-3.text-xs div:nth-child(2) span:last-child');
    if (sidebarSubtotal) sidebarSubtotal.textContent = totalPrice.toLocaleString('vi-VN') + ' đ';

    const sidebarGrand = document.querySelector('.pt-4.border-t.border-slate-100 span.text-xl.font-black');
    if (sidebarGrand) sidebarGrand.textContent = totalPrice.toLocaleString('vi-VN') + ' đ';

    if (btn) {
        if (count > 0) {
            btn.disabled = false;
            btn.classList.remove('opacity-40', 'cursor-not-allowed');
            btn.classList.add('opacity-100', 'cursor-pointer');
        } else {
            btn.disabled = true;
            btn.classList.add('opacity-40', 'cursor-not-allowed');
            btn.classList.remove('opacity-100', 'cursor-pointer');
        }
    }

    if (master && allCheckboxes.length > 0) {
        master.checked = count === allCheckboxes.length;
    }
}

function proceedToCheckout() {
    const selected = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(cb => cb.value);
    if (selected.length === 0) {
        alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        return;
    }
    window.location.href = "{{ route('checkout') }}?items=" + selected.join(',');
}

// Call on load to set selected count initially
document.addEventListener('DOMContentLoaded', () => {
    updateSelectedCount();
});
</script>
@endsection
