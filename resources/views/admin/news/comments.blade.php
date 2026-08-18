@extends('layouts.admin')

@section('title', 'Quản lý Bình luận - Veloce Admin')
@section('page_title', 'Trang con 2: Quản lý Bình luận Bài viết')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Quản lý Bình luận Bài viết</h2>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.news.index') }}" class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Danh sách bài viết
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
        <form method="GET" action="{{ route('admin.news.comments') }}" class="flex items-center gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo nội dung, tên hoặc email người bình luận..." 
                    class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <button type="submit" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition">
                Tìm kiếm
            </button>
        </form>
    </div>

    <!-- Comments List -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100">
        @forelse($comments as $comment)
            <div class="p-6 space-y-4 hover:bg-slate-50/50 transition">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-900 text-sm">{{ $comment->user_name ?? 'Khách' }}</span>
                        <span class="text-xs text-slate-400">({{ $comment->user_email ?? 'Không có email' }})</span>
                        @if($comment->user && $comment->user->isAdmin())
                            <span class="px-2 py-0.5 bg-indigo-600 text-white text-[9px] font-bold rounded-full">Quản trị viên</span>
                        @endif
                        @if($comment->parent_id)
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded-full border border-amber-100">Phản hồi bình luận #{{ $comment->parent_id }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-400">
                        <span>{{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i:s') : '' }}</span>
                        @if($comment->is_active)
                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-100">Đang hiển thị</span>
                        @else
                            <span class="px-2.5 py-0.5 bg-rose-50 text-rose-700 rounded-full text-[10px] font-bold border border-rose-100">Đã ẩn</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-xs font-bold text-indigo-600">
                        Bài viết: 
                        <a href="{{ route('blog.show', $comment->post->slug ?? $comment->post_id) }}" target="_blank" class="hover:underline">
                            {{ $comment->post->title ?? 'Bài viết #' . $comment->post_id }}
                        </a>
                    </p>
                    <div class="bg-slate-50 p-4 rounded-xl text-xs text-slate-800 font-medium">
                        {{ $comment->content }}
                    </div>
                </div>

                <!-- Admin Action Buttons (Reply Form, Toggle Hide/Show, Delete) -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2">
                    <!-- Inline Reply Form -->
                    <form action="{{ route('admin.news.comments.reply', $comment->id) }}" method="POST" class="flex items-center gap-2 flex-1 max-w-xl">
                        @csrf
                        <div class="w-full">
                            <input type="text" name="reply_content" placeholder="Nhập câu trả lời của Admin..." 
                                class="w-full py-1.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                            @error('reply_content') <p class="text-[10px] text-rose-500 mt-1 absolute">{{ $message }}</p> @enderror
                        </div>
                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shrink-0 transition flex items-center gap-1">
                            <i data-lucide="corner-down-right" class="w-3.5 h-3.5"></i> Trả lời
                        </button>
                    </form>

                    <div class="flex items-center gap-2">
                        <!-- Toggle Hide/Show Form -->
                        <form action="{{ route('admin.news.comments.toggle', $comment->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="px-3 py-1.5 {{ $comment->is_active ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} rounded-xl text-xs font-bold transition inline-flex items-center gap-1">
                                <i data-lucide="{{ $comment->is_active ? 'eye-off' : 'eye' }}" class="w-3.5 h-3.5"></i>
                                {{ $comment->is_active ? 'Ẩn bình luận' : 'Hiển thị' }}
                            </button>
                        </form>

                        <!-- Delete Comment Form -->
                        <form action="{{ route('admin.news.comments.destroy', $comment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bình luận này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-xl text-xs font-bold transition inline-flex items-center gap-1">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-slate-400 text-xs">Chưa có bình luận nào cho các bài viết.</div>
        @endforelse

        @if($comments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $comments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
