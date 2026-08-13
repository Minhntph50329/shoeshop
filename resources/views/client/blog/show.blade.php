@extends('layouts.app')

@section('title', $post->title . ' - Veloce Blog')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600">Trang chủ</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <a href="{{ route('blog') }}" class="hover:text-indigo-600">Tin tức</a>
        <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
        <span class="text-slate-900 font-bold truncate max-w-xs">{{ $post->title }}</span>
    </nav>

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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Left: Blog Sidebar -->
        <div class="space-y-6">
            <!-- Categories List -->
            @if(isset($categories) && $categories->count() > 0)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Danh mục tin tức</h3>
                    <ul class="space-y-2 text-xs">
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blog', ['category' => $cat->id]) }}" class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ $post->category_id == $cat->id ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                    <span>{{ $cat->name }}</span>
                                    <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-bold">{{ $cat->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Recent Posts -->
            @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Bài viết liên quan</h3>
                    <div class="space-y-3">
                        @foreach($recentPosts as $rPost)
                            <a href="{{ route('blog.show', $rPost->slug) }}" class="flex items-center gap-3 group">
                                <div class="w-14 h-14 bg-slate-100 rounded-xl overflow-hidden shrink-0">
                                    @if($rPost->thumbnail)
                                        <img src="{{ asset($rPost->thumbnail) }}" alt="{{ $rPost->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <i data-lucide="newspaper" class="w-6 h-6"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <h4 class="font-bold text-xs text-slate-800 group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
                                        {{ $rPost->title }}
                                    </h4>
                                    <span class="text-[10px] text-slate-400 block">{{ $rPost->created_at ? $rPost->created_at->format('d/m/Y') : '' }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Main Content Area -->
        <div class="lg:col-span-3 space-y-8">
            
            <article class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <!-- Meta Banner -->
                <div class="space-y-3">
                    @if($post->category)
                        <span class="inline-block bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    <h1 class="text-2xl sm:text-4xl font-black text-slate-900 leading-tight">
                        {{ $post->title }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 font-medium border-b border-slate-100 pb-4">
                        <span class="flex items-center gap-1.5"><i data-lucide="user" class="w-4 h-4 text-indigo-600"></i> {{ $post->author->fullname ?? 'Admin Veloce' }}</span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }}</span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="eye" class="w-4 h-4 text-slate-400"></i> {{ number_format($post->views) }} lượt xem</span>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->thumbnail)
                    <div class="rounded-2xl overflow-hidden max-h-[450px] bg-slate-100">
                        <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                @endif

                <!-- Article Content Body -->
                <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-4 font-medium">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </article>

            <!-- Comments Section -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-6">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i data-lucide="message-square" class="w-5 h-5 text-indigo-600"></i>
                        <span>Bình luận ({{ $comments->count() }})</span>
                    </h3>
                </div>

                <!-- Comments List -->
                <div class="space-y-6 divide-y divide-slate-100">
                    @forelse($comments as $comment)
                        <div class="pt-6 first:pt-0 space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($comment->user_name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="space-y-1 flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="font-bold text-xs text-slate-900">
                                            {{ $comment->user_name ?? 'Khách vắng mặt' }}
                                            @if($comment->user && $comment->user->isAdmin())
                                                <span class="ml-1.5 px-2 py-0.5 bg-indigo-600 text-white text-[9px] font-bold rounded-full">QTV</span>
                                            @endif
                                        </h4>
                                        <span class="text-[10px] text-slate-400">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</span>
                                    </div>
                                    <p class="text-xs text-slate-700 leading-relaxed font-medium bg-slate-50 p-3 rounded-2xl">
                                        {{ $comment->content }}
                                    </p>
                                </div>
                            </div>

                            <!-- Replies List -->
                            @if($comment->replies && $comment->replies->count() > 0)
                                <div class="ml-12 space-y-3 pt-2">
                                    @foreach($comment->replies as $reply)
                                        <div class="flex items-start gap-3 p-3 bg-indigo-50/50 rounded-2xl border border-indigo-100/50">
                                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($reply->user_name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div class="space-y-1 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h5 class="font-bold text-xs text-slate-900">
                                                        {{ $reply->user_name }}
                                                        @if($reply->user && $reply->user->isAdmin())
                                                            <span class="ml-1 px-1.5 py-0.5 bg-indigo-600 text-white text-[8px] font-bold rounded">QTV</span>
                                                        @endif
                                                    </h5>
                                                    <span class="text-[9px] text-slate-400">{{ $reply->created_at ? $reply->created_at->diffForHumans() : '' }}</span>
                                                </div>
                                                <p class="text-xs text-slate-700 leading-relaxed font-medium">
                                                    {{ $reply->content }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Chưa có bình luận nào cho bài viết này. Hãy là người đầu tiên bình luận!</p>
                    @endforelse
                </div>

                <!-- Add Comment Form -->
                @if($post->alow_comments)
                    <div class="pt-6 border-t border-slate-100 space-y-4">
                        <h4 class="font-bold text-sm text-slate-900">Viết bình luận của bạn</h4>

                        <form action="{{ route('blog.comment.store', $post->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @if(!Auth::check())
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Họ và tên (user_name) <span class="text-rose-500">*</span></label>
                                        <input type="text" name="user_name" value="{{ old('user_name') }}" required placeholder="Nguyễn Văn A" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 mb-1">Email (user_email) <span class="text-rose-500">*</span></label>
                                        <input type="email" name="user_email" value="{{ old('user_email') }}" required placeholder="email@example.com" class="w-full py-2.5 px-3 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                                    </div>
                                </div>
                            @else
                                <div class="text-xs text-slate-600 font-medium">
                                    Đang bình luận dưới tên: <span class="font-bold text-slate-900">{{ Auth::user()->fullname }}</span> ({{ Auth::user()->email }})
                                </div>
                            @endif

                            <div>
                                <textarea name="content" rows="3" required placeholder="Nhập nội dung bình luận tại đây..." class="w-full py-3 px-4 border border-slate-200 rounded-2xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600"></textarea>
                            </div>

                            <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-md shadow-indigo-200 transition">
                                Gửi bình luận
                            </button>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs text-slate-500 text-center font-semibold">
                        Bài viết này đã tắt tính năng bình luận.
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>
@endsection
