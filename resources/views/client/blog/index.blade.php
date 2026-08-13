@extends('layouts.app')

@section('title', 'Tin tức & Blog Thời trang - Veloce')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="border-b border-slate-100 pb-4">
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">Tin tức & Blog Thời trang</h1>
        <p class="text-xs text-slate-500">Cập nhật những bài viết, xu hướng phối đồ và tin tức mới nhất về thế giới Sneaker</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Left: Blog Sidebar -->
        <div class="space-y-6">
            
            <!-- Search Box -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-4">
                <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Tìm kiếm tin tức</h3>
                <form action="{{ route('blog') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Nhập từ khóa..." class="w-full py-2.5 pl-3 pr-9 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600">
                        <button type="submit" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-indigo-600">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Categories List -->
            @if(isset($categories) && $categories->count() > 0)
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 space-y-4">
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Danh mục tin tức</h3>
                    <ul class="space-y-2 text-xs">
                        <li>
                            <a href="{{ route('blog') }}" class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ !request('category') ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                                <span>Tất cả tin tức</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('blog', ['category' => $cat->id]) }}" class="flex items-center justify-between py-1.5 px-3 rounded-xl {{ request('category') == $cat->id ? 'bg-indigo-50 text-indigo-600 font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
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
                    <h3 class="font-bold text-sm text-slate-900 border-b border-slate-100 pb-3">Bài viết gần đây</h3>
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

        <!-- Right: Blog Posts Grid -->
        <div class="lg:col-span-3 space-y-6">
            @if(isset($posts) && $posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($posts as $post)
                        <div class="group bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-xl shadow-slate-100/50 hover:shadow-2xl transition duration-300 flex flex-col justify-between">
                            <div>
                                <div class="relative h-48 bg-slate-100 overflow-hidden">
                                    @if($post->thumbnail)
                                        <img src="{{ asset($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 bg-slate-100">
                                            <i data-lucide="newspaper" class="w-10 h-10"></i>
                                        </div>
                                    @endif
                                    @if($post->category)
                                        <span class="absolute top-3 left-3 bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-md">
                                            {{ $post->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="p-5 space-y-2">
                                    <div class="flex items-center gap-3 text-[11px] text-slate-400 font-medium">
                                        <span class="flex items-center gap-1"><i data-lucide="calendar" class="w-3.5 h-3.5"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y') : 'N/A' }}</span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1"><i data-lucide="eye" class="w-3.5 h-3.5"></i> {{ number_format($post->views) }} lượt xem</span>
                                    </div>
                                    <h3 class="font-bold text-sm text-slate-900 group-hover:text-indigo-600 transition line-clamp-2 leading-snug">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                        {{ Str::limit(strip_tags($post->content), 100) }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-5 pt-0">
                                <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-2 text-xs font-bold text-indigo-600 group-hover:text-indigo-700 hover:underline">
                                    Đọc tiếp <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($posts->hasPages())
                    <div class="pt-4">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white p-12 rounded-3xl border border-slate-100 shadow-sm text-center max-w-md mx-auto space-y-4">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto">
                        <i data-lucide="newspaper" class="w-10 h-10"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Chưa có bài viết nào!</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">Hiện tại chưa có bài viết tin tức nào được đăng tải. Vui lòng quay lại sau.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
