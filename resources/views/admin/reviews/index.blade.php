@extends('layouts.admin')

@section('title', 'Quản lý Đánh giá sản phẩm - Veloce')
@section('page_title', 'Quản lý Đánh giá & Bình luận')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Đánh giá sản phẩm</h2>
            <p class="text-sm text-slate-500">Xem và quản lý các đánh giá sản phẩm từ người dùng</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tìm kiếm</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tên khách hàng, sản phẩm, nội dung..."
                    class="w-full py-2 px-3 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Số sao</label>
                <select name="rating" class="w-full py-2 px-3 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                    <option value="">-- Tất cả --</option>
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                    @endfor
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                    Lọc kết quả
                </button>
                <a href="{{ route('admin.reviews.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table / Cards --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-6">Đánh giá</th>
                        <th class="py-3 px-6">Sản phẩm</th>
                        <th class="py-3 px-6">Nội dung & Hình ảnh</th>
                        <th class="py-3 px-6">Trạng thái</th>
                        <th class="py-3 px-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($reviews as $review)
                        <tr class="hover:bg-slate-50/50 transition align-top">
                            {{-- Reviewer & Time --}}
                            <td class="py-4 px-6 space-y-1">
                                <div class="font-bold text-slate-800">{{ $review->user->fullname ?? 'Khách hàng ẩn danh' }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $review->user->email ?? '' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $review->created_at->format('H:i d/m/Y') }}</div>
                                <div class="flex text-amber-400 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'fill-current text-amber-400' : 'text-slate-200' }}" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    @endfor
                                </div>
                            </td>

                            {{-- Product info --}}
                            <td class="py-4 px-6 max-w-xs">
                                @if($review->product)
                                    <a href="{{ route('products.show', $review->product->slug) }}" target="_blank" class="font-bold text-slate-800 hover:text-indigo-600 transition line-clamp-2">
                                        {{ $review->product->name }}
                                    </a>
                                    @if($review->orderItem && $review->orderItem->name_variant)
                                        <span class="inline-block mt-1 text-[10px] bg-slate-100 text-slate-600 font-semibold px-2 py-0.5 rounded">
                                            {{ $review->orderItem->name_variant }}: {{ $review->orderItem->attributes_variant }}
                                        </span>
                                    @endif
                                    @if($review->order)
                                        <div class="text-[10px] text-slate-400 mt-1 font-mono">Đơn hàng: #{{ $review->order->code }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Sản phẩm đã bị xóa</span>
                                @endif
                            </td>

                            {{-- Text & images & replies --}}
                            <td class="py-4 px-6 space-y-3 max-w-md">
                                <p class="text-xs text-slate-700 leading-relaxed font-semibold">
                                    {{ $review->review_text }}
                                </p>
                                
                                {{-- Images --}}
                                @if($review->images->count() > 0)
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($review->images as $img)
                                            <a href="{{ asset($img->image_path) }}" target="_blank" class="w-12 h-12 rounded-lg overflow-hidden border border-slate-100 bg-slate-50 shrink-0">
                                                <img src="{{ asset($img->image_path) }}" class="w-full h-full object-cover">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Admin replies --}}
                                @if($review->adminReplies->count() > 0)
                                    <div class="mt-2 pl-3 border-l-2 border-indigo-500 space-y-2">
                                        @foreach($review->adminReplies as $reply)
                                            <div class="bg-indigo-50/40 p-3 rounded-xl border border-indigo-50/50 space-y-1 relative">
                                                <div class="flex items-center justify-between text-[10px]">
                                                    <span class="font-bold text-indigo-700">Phản hồi bởi: {{ $reply->user->fullname ?? 'Admin' }}</span>
                                                    <span class="text-slate-400">{{ $reply->created_at->format('H:i d/m/Y') }}</span>
                                                </div>
                                                <p class="text-xs text-slate-600 leading-relaxed">{{ $reply->review_text }}</p>
                                                
                                                <form action="{{ route('admin.reviews.destroy', $reply->id) }}" method="POST" class="absolute top-1 right-2" onsubmit="return confirm('Bạn có chắc muốn xóa phản hồi này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 transition" title="Xóa phản hồi">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </td>

                            {{-- Active Status --}}
                            <td class="py-4 px-6">
                                @if($review->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Hiển thị
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Đang ẩn
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-6 text-right space-x-1 whitespace-nowrap">
                                <button type="button" 
                                        onclick="openReplyModal({{ $review->id }}, '{{ addslashes($review->user->fullname ?? 'Khách hàng') }}', '{{ addslashes($review->review_text) }}')"
                                        class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-lg transition" 
                                        title="Trả lời đánh giá">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </button>
                                
                                <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" 
                                            class="inline-flex items-center p-2 bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-600 rounded-lg transition" 
                                            title="{{ $review->is_active ? 'Ẩn đánh giá' : 'Hiển thị đánh giá' }}">
                                        @if($review->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        @endif
                                    </button>
                                </form>

                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này cùng các phản hồi đi kèm?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-lg transition" title="Xóa vĩnh viễn">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-400 font-medium">Không tìm thấy đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Admin Reply Modal --}}
<div id="replyModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800">Trả lời đánh giá</h3>
            <button onclick="closeReplyModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="replyForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-xs text-slate-500 font-medium">Đang trả lời đánh giá của:</p>
                <p id="reply_user_name" class="text-xs font-bold text-slate-800 mt-0.5"></p>
                <div class="mt-1 p-2 bg-slate-50 border border-slate-100 rounded-lg text-[11px] text-slate-600 italic">
                    "<span id="reply_review_text"></span>"
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nội dung phản hồi <span class="text-rose-500">*</span></label>
                <textarea name="reply_text" rows="4" placeholder="Cảm ơn quý khách đã tin dùng sản phẩm của Veloce..."
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeReplyModal()"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Hủy bỏ
                </button>
                <button type="submit"
                    class="flex-1 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                    Gửi phản hồi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReplyModal(reviewId, userName, reviewText) {
    const form = document.getElementById('replyForm');
    form.action = `/admin/reviews/${reviewId}/reply`;
    
    document.getElementById('reply_user_name').innerText = userName;
    document.getElementById('reply_review_text').innerText = reviewText;
    form.querySelector('textarea').value = '';

    const modal = document.getElementById('replyModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReplyModal() {
    const modal = document.getElementById('replyModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('replyModal').addEventListener('click', function(e) {
    if (e.target === this) closeReplyModal();
});
</script>
@endsection
