@extends('layouts.app')

@section('title', 'Tin tức & Blog Thời trang - Veloce')

@section('content')

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

    <!-- Tiêu đề -->
    <div class="flex flex-col gap-2 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i data-lucide="newspaper" class="h-4 w-4"></i>
                </span>

                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">
                    Veloce Blog
                </span>
            </div>

            <h1 class="text-xl font-black tracking-tight text-slate-900 sm:text-2xl">
                Tin tức & Blog Thời trang
            </h1>

            <p class="mt-1 text-xs leading-relaxed text-slate-500 sm:text-sm">
                Cập nhật những bài viết, xu hướng phối đồ và tin tức mới nhất về thế giới Sneaker
            </p>
        </div>
    </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4 lg:gap-8">


        <!-- ================= SIDEBAR ================= -->
        <aside class="space-y-5 lg:sticky lg:top-24 lg:h-fit">


            <!-- Tìm kiếm -->
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-4">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <i data-lucide="search" class="h-4 w-4"></i>
                    </span>

                    <h3 class="text-sm font-bold text-slate-800">
                        Tìm kiếm
                    </h3>
                </div>

                <div class="p-4">
                    <form action="{{ route('blog') }}" method="GET">

                        <div class="relative">

                            <i data-lucide="search"
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                            </i>

                            <input
                                type="text"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Nhập từ khóa..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                        </div>

                    </form>
                </div>

            </div>


            <!-- Danh mục -->
            @if(isset($categories) && $categories->count() > 0)

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                    <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-4">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">
                            <i data-lucide="layout-grid" class="h-4 w-4"></i>
                        </span>

                        <h3 class="text-sm font-bold text-slate-800">
                            Danh mục
                        </h3>

                    </div>


                    <div class="p-3">

                        <ul class="space-y-1.5 text-xs">

                            <!-- Tất cả -->
                            <li>

                                <a
                                    href="{{ route('blog') }}"
                                    class="flex items-center justify-between rounded-xl px-3 py-2.5 transition
                                    {{ !request('category')
                                        ? 'bg-indigo-600 font-bold text-white shadow-sm'
                                        : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">

                                    <span>Tất cả tin tức</span>

                                    @if(!request('category'))
                                        <i data-lucide="check" class="h-3.5 w-3.5"></i>
                                    @endif

                                </a>

                            </li>


                            <!-- Danh mục -->
                            @foreach($categories as $cat)

                                <li>

                                    <a
                                        href="{{ route('blog', ['category' => $cat->id]) }}"
                                        class="flex items-center justify-between rounded-xl px-3 py-2.5 transition
                                        {{ request('category') == $cat->id
                                            ? 'bg-indigo-600 font-bold text-white shadow-sm'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600' }}">

                                        <span class="truncate">
                                            {{ $cat->name }}
                                        </span>


                                        @if(request('category') == $cat->id)

                                            <i data-lucide="check" class="h-3.5 w-3.5 shrink-0"></i>

                                        @else

                                            <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500">
                                                {{ $cat->posts_count }}
                                            </span>

                                        @endif

                                    </a>

                                </li>

                            @endforeach

                        </ul>

                    </div>

                </div>

            @endif


            <!-- Bài viết gần đây -->
            @if(isset($recentPosts) && $recentPosts->count() > 0)

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">

                    <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-4">

                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">
                            <i data-lucide="clock-3" class="h-4 w-4"></i>
                        </span>

                        <h3 class="text-sm font-bold text-slate-800">
                            Bài viết gần đây
                        </h3>

                    </div>


                    <div class="space-y-3 p-4">

                        @foreach($recentPosts as $rPost)

                            <a
                                href="{{ route('blog.show', $rPost->slug) }}"
                                class="group flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-slate-50">

                                <!-- Thumbnail -->

                                <div class="h-14 w-14 shrink-0 overflow-hidden rounded-xl bg-slate-100">

                                    @if($rPost->thumbnail)

                                        <img
                                            src="{{ asset($rPost->thumbnail) }}"
                                            alt="{{ $rPost->title }}"
                                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

                                    @else

                                        <div class="flex h-full w-full items-center justify-center text-slate-300">
                                            <i data-lucide="newspaper" class="h-5 w-5"></i>
                                        </div>

                                    @endif

                                </div>


                                <!-- Nội dung -->

                                <div class="min-w-0 flex-1">

                                    <h4 class="line-clamp-2 text-xs font-bold leading-snug text-slate-700 transition group-hover:text-indigo-600">
                                        {{ $rPost->title }}
                                    </h4>

                                    <div class="mt-1 flex items-center gap-1 text-[10px] text-slate-400">

                                        <i data-lucide="calendar" class="h-3 w-3"></i>

                                        {{ $rPost->created_at ? $rPost->created_at->format('d/m/Y') : '' }}

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        </aside>



        <!-- ================= DANH SÁCH BÀI VIẾT ================= -->

        <div class="space-y-6 lg:col-span-3">


            <!-- Header danh sách -->

            <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-5">

                <div>

                    <div class="text-[10px] font-bold uppercase tracking-[0.16em] text-indigo-500">
                        Danh sách bài viết
                    </div>

                    <div class="mt-1 flex items-baseline gap-2">

                        <span class="text-xl font-black text-slate-900">
                            {{ isset($posts) ? $posts->total() : 0 }}
                        </span>

                        <span class="text-xs text-slate-500">
                            bài viết đang hiển thị
                        </span>

                    </div>

                </div>


                <div class="flex items-center gap-2 text-xs text-slate-400">

                    <i data-lucide="newspaper" class="h-4 w-4"></i>

                    <span>
                        Khám phá tin tức mới nhất
                    </span>

                </div>

            </div>



            @if(isset($posts) && $posts->count() > 0)


                <!-- Grid bài viết -->

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">

                    @foreach($posts as $post)


                        <article
                            class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg">


                            <!-- Ảnh -->

                            <div class="relative h-48 overflow-hidden bg-slate-100">

                                @if($post->thumbnail)

                                    <img
                                        src="{{ asset($post->thumbnail) }}"
                                        alt="{{ $post->title }}"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-105">

                                @else

                                    <div class="flex h-full w-full items-center justify-center text-slate-300">

                                        <i data-lucide="newspaper" class="h-10 w-10"></i>

                                    </div>

                                @endif


                                <!-- Overlay nhẹ -->

                                <div class="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-black/20 to-transparent"></div>


                                <!-- Category -->

                                @if($post->category)

                                    <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold text-indigo-600 shadow-sm backdrop-blur">

                                        {{ $post->category->name }}

                                    </span>

                                @endif

                            </div>



                            <!-- Nội dung -->

                            <div class="flex flex-1 flex-col p-5">


                                <!-- Thông tin -->

                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[10px] text-slate-400">

                                    <span class="flex items-center gap-1">

                                        <i data-lucide="calendar" class="h-3 w-3"></i>

                                        {{ $post->created_at ? $post->created_at->format('d/m/Y') : 'N/A' }}

                                    </span>


                                    <span class="flex items-center gap-1">

                                        <i data-lucide="eye" class="h-3 w-3"></i>

                                        {{ number_format($post->views) }}

                                    </span>

                                </div>



                                <!-- Tiêu đề -->

                                <h3 class="mt-3 line-clamp-2 text-sm font-bold leading-snug text-slate-800 transition group-hover:text-indigo-600">

                                    <a href="{{ route('blog.show', $post->slug) }}">

                                        {{ $post->title }}

                                    </a>

                                </h3>



                                <!-- Mô tả -->

                                <p class="mt-2 line-clamp-3 text-xs leading-relaxed text-slate-500">

                                    {{ Str::limit(strip_tags($post->content), 120) }}

                                </p>



                                <!-- Đọc tiếp -->

                                <div class="mt-auto pt-5">

                                    <a
                                        href="{{ route('blog.show', $post->slug) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 transition hover:gap-2.5 hover:text-indigo-700">

                                        Đọc tiếp

                                        <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>

                                    </a>

                                </div>


                            </div>

                        </article>


                    @endforeach

                </div>



                <!-- Phân trang -->

                @if($posts->hasPages())

                    <div class="pt-3">

                        {{ $posts->links() }}

                    </div>

                @endif



            @else


                <!-- Không có bài viết -->

                <div class="rounded-2xl border border-slate-200 bg-white px-6 py-14 text-center shadow-sm">

                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-50 text-indigo-600">

                        <i data-lucide="newspaper" class="h-8 w-8"></i>

                    </div>


                    <h3 class="mt-5 text-lg font-black text-slate-800">

                        Chưa có bài viết nào!

                    </h3>


                    <p class="mx-auto mt-2 max-w-sm text-xs leading-relaxed text-slate-500">

                        Hiện tại chưa có bài viết tin tức nào được đăng tải.
                        Vui lòng quay lại sau.

                    </p>


                    @if(request()->anyFilled(['q', 'category']))

                        <a
                            href="{{ route('blog') }}"
                            class="mt-5 inline-flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2.5 text-xs font-bold text-indigo-600 transition hover:bg-indigo-100">

                            <i data-lucide="rotate-ccw" class="h-3.5 w-3.5"></i>

                            Xóa bộ lọc

                        </a>

                    @endif

                </div>


            @endif


        </div>

    </div>

</div>

@endsection