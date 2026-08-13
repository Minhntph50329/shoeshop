@extends('layouts.app')

@section('title', 'Yêu cầu trả hàng - Đơn hàng #' . $order->code)

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('my-orders') }}" class="hover:text-indigo-600 transition">Đơn hàng của tôi</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('client.orders.show', $order->id) }}" class="hover:text-indigo-600 transition">#{{ $order->code }}</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Yêu cầu trả hàng</span>
    </nav>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-gradient-to-r from-indigo-50 to-indigo-100/50 border-b border-slate-100">
            <h1 class="text-lg font-bold text-slate-800">Yêu cầu Trả hàng & Hoàn tiền</h1>
            <p class="text-xs text-slate-500 mt-1">Đơn hàng: #{{ $order->code }} | Vui lòng chọn sản phẩm và nhập thông tin hoàn tiền</p>
        </div>

        <form action="{{ route('client.orders.refund.store', $order->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- 1. Product Selection --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">1</span>
                    Chọn sản phẩm và số lượng muốn trả
                </h3>

                <div class="border border-slate-100 rounded-2xl divide-y divide-slate-50 overflow-hidden">
                    @foreach($order->items as $item)
                        @php
                            $img = $item->product && $item->product->images->first() ? asset('storage/' . $item->product->images->first()->url) : 'https://placehold.co/64x64/f1f5f9/94a3b8?text=?';
                        @endphp
                        <div class="flex flex-wrap items-center justify-between gap-4 p-4 hover:bg-slate-50/50 transition">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                                    <img src="{{ $img }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-xs text-slate-800 truncate">{{ $item->name }}</h4>
                                    @if($item->name_variant)
                                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->name_variant }}: {{ $item->attributes_variant }}</p>
                                    @endif
                                    <p class="text-[11px] text-indigo-600 font-bold mt-1">
                                        {{ number_format($item->effective_price, 0, ',', '.') }}đ 
                                        <span class="text-slate-300 font-normal">| Đã mua: x{{ $item->quantity }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                    <button type="button" onclick="decrementQty({{ $item->id }})" class="px-3 py-1.5 text-slate-500 hover:bg-slate-50 font-bold transition">-</button>
                                    <input type="number" 
                                           id="qty-{{ $item->id }}" 
                                           name="items[{{ $item->id }}][quantity]" 
                                           value="0" 
                                           min="0" 
                                           max="{{ $item->quantity }}" 
                                           class="w-12 text-center text-xs font-bold border-none focus:ring-0 text-slate-800"
                                           onchange="validateQty(this, {{ $item->quantity }})">
                                    <button type="button" onclick="incrementQty({{ $item->id }}, {{ $item->quantity }})" class="px-3 py-1.5 text-slate-500 hover:bg-slate-50 font-bold transition">+</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('items')
                    <p class="text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <hr class="border-slate-100">

            {{-- 2. Refund Bank Details --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">2</span>
                    Thông tin tài khoản nhận tiền hoàn
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Ngân hàng</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required placeholder="Ví dụ: Vietcombank, MB, Techcombank..."
                               class="w-full text-xs border border-slate-200 rounded-xl px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        @error('bank_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Số tài khoản</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account') }}" required placeholder="Nhập số tài khoản ngân hàng"
                               class="w-full text-xs border border-slate-200 rounded-xl px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none">
                        @error('bank_account')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tên chủ tài khoản</label>
                        <input type="text" name="user_bank_name" value="{{ old('user_bank_name') }}" required placeholder="Ví dụ: NGUYEN VAN A"
                               class="w-full text-xs border border-slate-200 rounded-xl px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none uppercase">
                        @error('user_bank_name')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-100">

            {{-- 3. Return Reason & Proof --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs">3</span>
                    Lý do và hình ảnh minh họa
                </h3>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Lý do trả hàng</label>
                        <textarea name="reason" rows="4" required placeholder="Vui lòng mô tả chi tiết lý do bạn muốn trả sản phẩm này..."
                                  class="w-full text-xs border border-slate-200 rounded-xl px-4 py-2.5 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none resize-none">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Hình ảnh bằng chứng (nếu có)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 border-dashed rounded-2xl cursor-pointer hover:bg-slate-50/50 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    <p class="mb-1 text-xs text-slate-500 font-semibold">Click để chọn ảnh hoặc kéo thả</p>
                                    <p class="text-[10px] text-slate-400">PNG, JPG, JPEG (Tối đa 2MB)</p>
                                </div>
                                <input type="file" name="reason_image" accept="image/*" class="hidden" onchange="previewImage(this)">
                            </label>
                        </div>
                        <div id="image-preview-container" class="hidden mt-2 p-2 border border-slate-100 rounded-xl w-32 h-32 relative bg-slate-50">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover rounded-lg">
                            <button type="button" onclick="removeImage()" class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full p-1 shadow hover:bg-red-600 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @error('reason_image')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Form buttons --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('client.orders.show', $order->id) }}" 
                   class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">
                    Hủy bỏ
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-100 transition">
                    Gửi yêu cầu trả hàng
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function incrementQty(itemId, maxVal) {
        const input = document.getElementById('qty-' + itemId);
        let val = parseInt(input.value) || 0;
        if (val < maxVal) {
            input.value = val + 1;
        }
    }

    function decrementQty(itemId) {
        const input = document.getElementById('qty-' + itemId);
        let val = parseInt(input.value) || 0;
        if (val > 0) {
            input.value = val - 1;
        }
    }

    function validateQty(input, maxVal) {
        let val = parseInt(input.value) || 0;
        if (val < 0) {
            input.value = 0;
        } else if (val > maxVal) {
            input.value = maxVal;
        }
    }

    function previewImage(input) {
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeImage() {
        const fileInput = document.querySelector('input[name="reason_image"]');
        const previewContainer = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        
        fileInput.value = '';
        preview.src = '#';
        previewContainer.classList.add('hidden');
    }
</script>
@endsection
