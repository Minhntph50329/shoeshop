@extends('layouts.admin')

@section('title', 'Quản lý Liên hệ khách hàng - Veloce')
@section('page_title', 'Quản lý Liên hệ')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Liên hệ & Đóng góp ý kiến</h2>
            <p class="text-sm text-slate-500">Xem và phản hồi các tin nhắn liên hệ từ khách hàng</p>
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
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Tìm kiếm</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tên, email, số điện thoại, lời nhắn..."
                    class="w-full py-2 px-3 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">Trạng thái</label>
                <select name="status" class="w-full py-2 px-3 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                    <option value="">-- Tất cả --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chưa xử lý (Chờ phản hồi)</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">
                    Lọc kết quả
                </button>
                <a href="{{ route('admin.contacts.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table / List --}}
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold uppercase text-slate-500 tracking-wider">
                        <th class="py-3 px-6">Khách hàng</th>
                        <th class="py-3 px-6">Nội dung liên hệ</th>
                        <th class="py-3 px-6">Trạng thái</th>
                        <th class="py-3 px-6">Phản hồi</th>
                        <th class="py-3 px-6 text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-slate-50/50 transition align-top">
                            {{-- Customer Info --}}
                            <td class="py-4 px-6 space-y-1 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $contact->name }}</div>
                                <div class="text-xs text-slate-400 font-mono">{{ $contact->email }}</div>
                                @if($contact->phone)
                                    <div class="text-xs text-slate-500 font-mono">{{ $contact->phone }}</div>
                                @endif
                                <div class="text-[10px] text-indigo-500 font-semibold mt-1">
                                    {{ $contact->user_id ? '✓ Thành viên' : '✗ Khách vãng lai' }}
                                </div>
                            </td>

                            {{-- Message Content --}}
                            <td class="py-4 px-6 max-w-sm">
                                <p class="text-xs text-slate-700 leading-relaxed font-medium bg-slate-50/50 p-2.5 rounded-lg border border-slate-100">
                                    {{ $contact->message }}
                                </p>
                                <span class="text-[9px] text-slate-400 block mt-1">Gửi lúc: {{ $contact->created_at->format('H:i d/m/Y') }}</span>
                            </td>

                            {{-- Status --}}
                            <td class="py-4 px-6">
                                @if($contact->status === 'pending')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Chờ phản hồi
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Đã phản hồi
                                    </span>
                                @endif
                            </td>

                            {{-- Reply Content --}}
                            <td class="py-4 px-6 max-w-sm">
                                @if($contact->status === 'replied')
                                    <div class="space-y-1.5">
                                        <p class="text-xs text-slate-600 leading-relaxed italic bg-indigo-50/30 p-2.5 rounded-lg border border-indigo-50/50">
                                            "{{ $contact->reply_message }}"
                                        </p>
                                        @if($contact->replied_at)
                                            <span class="text-[9px] text-slate-400 block">Lúc: {{ $contact->replied_at->format('H:i d/m/Y') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Chưa phản hồi</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-6 text-right space-x-1 whitespace-nowrap">
                                <button type="button" 
                                        onclick="openReplyModal({{ $contact->id }}, '{{ addslashes($contact->name) }}', '{{ addslashes($contact->message) }}')"
                                        class="inline-flex items-center p-2 bg-slate-100 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 rounded-lg transition" 
                                        title="{{ $contact->status === 'replied' ? 'Phản hồi lại' : 'Trả lời' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                </button>

                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-600 rounded-lg transition" title="Xóa liên hệ">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 px-6 text-center text-slate-400 font-medium">Không tìm thấy tin nhắn liên hệ nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Reply Modal --}}
<div id="replyModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-black text-slate-800">Trả lời liên hệ</h3>
            <button onclick="closeReplyModal()" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="replyForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <p class="text-xs text-slate-500 font-medium">Khách hàng:</p>
                <p id="reply_user_name" class="text-xs font-bold text-slate-800 mt-0.5"></p>
                <div class="mt-1.5 p-2.5 bg-slate-50 border border-slate-100 rounded-lg text-[11px] text-slate-600 italic">
                    "<span id="reply_review_text"></span>"
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nội dung phản hồi <span class="text-rose-500">*</span></label>
                <textarea name="reply_message" rows="4" required minlength="5" placeholder="Cảm ơn quý khách đã gửi phản hồi. Veloce xin được trả lời câu hỏi của quý khách..."
                    class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeReplyModal()"
                    class="flex-1 py-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                    Hủy
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
function openReplyModal(contactId, userName, messageText) {
    const form = document.getElementById('replyForm');
    form.action = `/admin/contacts/${contactId}/reply`;
    
    document.getElementById('reply_user_name').innerText = userName;
    document.getElementById('reply_review_text').innerText = messageText;
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
