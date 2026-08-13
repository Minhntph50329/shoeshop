@extends('layouts.admin')

@section('title', 'Yêu cầu trả hàng #' . $refund->id . ' - Veloce Admin')
@section('page_title', 'Chi tiết Yêu cầu Trả hàng')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb + Back --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('admin.refunds.index') }}" class="hover:text-indigo-600 transition font-medium">Yêu cầu trả hàng</a>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
            <span class="font-black text-slate-800">Yêu cầu #{{ $refund->id }}</span>
        </div>
        <a href="{{ route('admin.refunds.index') }}" class="flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
        </a>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- LEFT COLUMN (2/3) --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Refund Request Info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-lg font-black text-slate-800">Đơn hàng #{{ $refund->order->code }}</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Yêu cầu tạo lúc: {{ $refund->created_at->format('H:i d/m/Y') }}</p>
                    </div>
                    @php
                        $refColor = $statusColors[$refund->status] ?? ['bg'=>'bg-slate-100','text'=>'text-slate-600','border'=>'border-slate-200'];
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold border {{ $refColor['bg'] }} {{ $refColor['text'] }} {{ $refColor['border'] }}">
                        <span class="w-2 h-2 rounded-full bg-current opacity-70"></span>
                        {{ $statusLabels[$refund->status] ?? $refund->status }}
                    </span>
                </div>

                {{-- Bank account details --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-100 bg-slate-50/50 p-4 rounded-xl">
                    <div>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Ngân hàng</p>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $refund->bank_name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Số tài khoản</p>
                        <p class="text-xs font-bold text-slate-800 mt-1">{{ $refund->bank_account }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide">Tên chủ tài khoản</p>
                        <p class="text-xs font-bold text-slate-800 mt-1 uppercase">{{ $refund->user_bank_name }}</p>
                    </div>
                </div>

                {{-- Reason and proof image --}}
                <div class="mt-6 space-y-4">
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Lý do từ khách hàng:</h4>
                        <p class="text-xs text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                            "{{ $refund->reason }}"
                        </p>
                    </div>
                    @if($refund->reason_image)
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Hình ảnh minh chứng từ khách:</h4>
                            <div class="w-48 h-48 rounded-xl overflow-hidden border border-slate-100 bg-slate-50">
                                <a href="{{ asset('storage/' . $refund->reason_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $refund->reason_image) }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Refund Items --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Sản phẩm yêu cầu trả ({{ $refund->items->count() }})</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($refund->items as $item)
                        @php
                            $img = $item->product && $item->product->images->first() ? asset('storage/' . $item->product->images->first()->url) : 'https://placehold.co/80x80/f1f5f9/94a3b8?text=No+Img';
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden border border-slate-100 flex-shrink-0 bg-slate-50">
                                <img src="{{ $img }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-xs text-slate-800 line-clamp-1">{{ $item->name }}</h4>
                                @if($item->name_variant)
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->name_variant }}</p>
                                @endif
                            </div>
                            <div class="text-center flex-shrink-0 w-16">
                                <span class="text-xs text-slate-500 font-bold">x{{ $item->quantity }}</span>
                            </div>
                            <div class="text-right flex-shrink-0 w-28">
                                <p class="font-bold text-indigo-600 text-xs">{{ number_format($item->effective_price, 0, ',', '.') }}đ</p>
                                <p class="text-[10px] text-slate-400">= {{ number_format($item->line_total, 0, ',', '.') }}đ</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between font-black text-sm text-slate-800">
                    <span>Tổng tiền hoàn trả:</span>
                    <span class="text-indigo-600 text-base">{{ number_format($refund->total_amount, 0, ',', '.') }}đ</span>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN (1/3) --}}
        <div class="space-y-6">

            {{-- Customer info --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-sm text-slate-800">Thông tin người mua</h3>
                </div>
                <div class="p-5 space-y-3 text-xs">
                    <div class="flex items-center gap-2 text-slate-700">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        <span class="font-semibold">{{ $refund->user->fullname ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-700">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-400"></i>
                        <span class="break-all">{{ $refund->user->email ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-slate-700">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                        <span>{{ $refund->order->phone_number ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            {{-- State Machine Actions --}}
            @if(in_array($refund->status, [\App\Models\Refund::STATUS_PENDING, \App\Models\Refund::STATUS_APPROVED]))
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-5 space-y-4">
                    <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-2">Xử lý yêu cầu</h3>
                    
                    @if($refund->status === \App\Models\Refund::STATUS_PENDING)
                        <form action="{{ route('admin.refunds.action', $refund->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="action" id="action-input" value="approve">

                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Phản hồi của Admin (Bắt buộc nếu Từ chối)</label>
                                <textarea name="aadmin_reason" rows="3" placeholder="Lý do từ chối hoặc lời nhắn gửi đến khách hàng..."
                                          class="w-full text-xs border border-slate-200 rounded-xl px-3 py-2 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none resize-none"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <button type="submit" onclick="setAction('reject')"
                                        class="py-2 px-3 border border-red-200 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition text-center">
                                    Từ chối
                                </button>
                                <button type="submit" onclick="setAction('approve')"
                                        class="py-2 px-3 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition text-center shadow-lg shadow-indigo-100">
                                    Phê duyệt
                                </button>
                            </div>
                        </form>
                    @endif

                    @if($refund->status === \App\Models\Refund::STATUS_APPROVED)
                        <form action="{{ route('admin.refunds.action', $refund->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4"
                              onsubmit="return confirm('Bạn xác nhận đã nhận lại hàng và hoàn tiền cho khách hàng thành công?')">
                            @csrf
                            <input type="hidden" name="action" value="complete">
                            
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wide">Ảnh giao dịch hoàn tiền</label>
                                <input type="file" name="img_refunded_money" accept="image/*" required
                                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>

                            <div class="p-3 bg-blue-50 border border-blue-100 text-blue-800 rounded-xl text-xs">
                                <strong>Lưu ý:</strong> Xác nhận này sẽ tự động chuyển trạng thái đơn hàng thành <strong>Hoàn tiền</strong>, cập nhật số lượng tồn kho sản phẩm, và gửi email thông báo cho khách hàng.
                            </div>

                            <button type="submit"
                                    class="w-full py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition text-center shadow-lg shadow-emerald-100">
                                Xác nhận hoàn thành
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            {{-- Logs/Admin feedback history display --}}
            @if($refund->status === \App\Models\Refund::STATUS_REJECTED || $refund->status === \App\Models\Refund::STATUS_COMPLETED)
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden p-5 space-y-4">
                    <h3 class="font-bold text-sm text-slate-800 border-b border-slate-100 pb-2">Lịch sử phản hồi</h3>
                    <div class="text-xs space-y-3">
                        <div>
                            <p class="text-slate-400">Ý kiến phản hồi từ Admin:</p>
                            <p class="text-slate-800 mt-1 font-semibold">{{ $refund->aadmin_reason ?? 'Không có ghi chú.' }}</p>
                        </div>
                        @if($refund->order && $refund->order->img_refunded_money)
                            <div>
                                <p class="text-slate-400 font-medium mb-1">Ảnh giao dịch hoàn tiền:</p>
                                <div class="w-32 h-32 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                                    <a href="{{ asset('storage/' . $refund->order->img_refunded_money) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $refund->order->img_refunded_money) }}" class="w-full h-full object-cover">
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>

<script>
    function setAction(actionVal) {
        document.getElementById('action-input').value = actionVal;
    }
</script>
@endsection
