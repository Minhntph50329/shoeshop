@extends('layouts.app')

@section('title', $post->title . ' - Veloce Blog')

@section('content')

<div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">

    {{-- ================= BREADCRUMB ================= --}}

    <nav class="flex items-center gap-1.5 overflow-hidden text-xs text-slate-500">

        <a href="{{ route('home') }}"
            class="shrink-0 transition hover:text-indigo-600">
            Trang chủ
        </a>

        <i data-lucide="chevron-right" class="h-3.5 w-3.5 shrink-0 text-slate-300"></i>

        <a href="{{ route('blog') }}"
            class="shrink-0 transition hover:text-indigo-600">
            Tin tức
        </a>

        <i data-lucide="chevron-right" class="h-3.5 w-3.5 shrink-0 text-slate-300"></i>

        <span class="truncate font-medium text-slate-700">
            {{ $post->title }}
        </span>

    </nav>



    {{-- ================= ALERT SUCCESS ================= --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700 shadow-sm">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">

                <i data-lucide="check-circle" class="h-4 w-4"></i>

            </div>

            <div class="pt-1">

                {{ session('success') }}

            </div>

        </div>

    @endif



    {{-- ================= ALERT ERROR ================= --}}

    @if(session('error'))

        <div class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700 shadow-sm">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-600">

                <i data-lucide="alert-circle" class="h-4 w-4"></i>

            </div>

            <div class="pt-1">

                {{ session('error') }}

            </div>

        </div>

    @endif



    {{-- ================= MAIN LAYOUT ================= --}}

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4 lg:gap-8">


        {{-- ================================================= --}}
        {{-- ================= SIDEBAR ======================= --}}
        {{-- ================================================= --}}

        <aside class="space-y-5 lg:sticky lg:top-24 lg:h-fit">


            {{-- ================= CATEGORIES ================= --}}

            @if(isset($categories) && $categories->count() > 0)

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


                    {{-- Header --}}

                    <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-4">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600">

                            <i data-lucide="layout-grid" class="h-4 w-4"></i>

                        </div>

                        <h3 class="text-sm font-bold text-slate-800">

                            Danh mục tin tức

                        </h3>

                    </div>



                    {{-- Categories list --}}

                    <div class="p-3">

                        <ul class="space-y-1.5 text-xs">

                            @foreach($categories as $cat)

                                <li>

                                    <a
                                        href="{{ route('blog', ['category' => $cat->id]) }}"

                                        class="flex items-center justify-between rounded-xl px-3 py-2.5 transition duration-200

                                        {{ $post->category_id == $cat->id
                                            ? 'bg-indigo-600 font-bold text-white shadow-sm'
                                            : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'
                                        }}">

                                        <span class="truncate">

                                            {{ $cat->name }}

                                        </span>


                                        @if($post->category_id == $cat->id)

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



            {{-- ================= RELATED POSTS ================= --}}

            @if(isset($recentPosts) && $recentPosts->count() > 0)

                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


                    {{-- Header --}}

                    <div class="flex items-center gap-2 border-b border-slate-100 px-5 py-4">

                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-50 text-amber-600">

                            <i data-lucide="clock-3" class="h-4 w-4"></i>

                        </div>

                        <h3 class="text-sm font-bold text-slate-800">

                            Bài viết liên quan

                        </h3>

                    </div>



                    {{-- Posts --}}

                    <div class="space-y-3 p-4">

                        @foreach($recentPosts as $rPost)

                            <a
                                href="{{ route('blog.show', $rPost->slug) }}"

                                class="group flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-slate-50">


                                {{-- Image --}}

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



                                {{-- Content --}}

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



        {{-- ================================================= --}}
        {{-- ================= MAIN CONTENT ================== --}}
        {{-- ================================================= --}}

        <main class="space-y-6 lg:col-span-3">


            {{-- ================= ARTICLE ================= --}}

            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">


                {{-- ========== ARTICLE HEADER ========== --}}

                <div class="p-5 sm:p-7 md:p-8">


                    {{-- Category --}}

                    @if($post->category)

                        <div class="mb-4">

                            <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-indigo-600">

                                <i data-lucide="tag" class="h-3 w-3"></i>

                                {{ $post->category->name }}

                            </span>

                        </div>

                    @endif



                    {{-- Title --}}

                    <h1 class="text-2xl font-black leading-tight tracking-tight text-slate-900 sm:text-3xl md:text-4xl">

                        {{ $post->title }}

                    </h1>



                    {{-- Meta information --}}

                    <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 border-t border-slate-100 pt-4 text-[11px] text-slate-400">


                        {{-- Author --}}

                        <span class="flex items-center gap-1.5">

                            <i data-lucide="user" class="h-3.5 w-3.5 text-indigo-500"></i>

                            <span>

                                {{ $post->author->fullname ?? 'Admin Veloce' }}

                            </span>

                        </span>



                        {{-- Date --}}

                        <span class="flex items-center gap-1.5">

                            <i data-lucide="calendar" class="h-3.5 w-3.5"></i>

                            {{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '' }}

                        </span>



                        {{-- Views --}}

                        <span class="flex items-center gap-1.5">

                            <i data-lucide="eye" class="h-3.5 w-3.5"></i>

                            {{ number_format($post->views) }} lượt xem

                        </span>

                    </div>

                </div>



                {{-- ========== FEATURED IMAGE ========== --}}

                @if($post->thumbnail)

                    <div class="border-y border-slate-100 bg-slate-100">

                        <img
                            src="{{ asset($post->thumbnail) }}"
                            alt="{{ $post->title }}"

                            class="max-h-[500px] w-full object-cover">

                    </div>

                @endif



                {{-- ========== ARTICLE CONTENT ========== --}}

                <div class="p-5 sm:p-7 md:p-8">


                    <div class="prose prose-slate max-w-none text-sm leading-8 text-slate-700">

                        {!! nl2br(e($post->content)) !!}

                    </div>

                </div>


            </article>



            {{-- ================================================= --}}
            {{-- ================= COMMENTS ====================== --}}
            {{-- ================================================= --}}

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 md:p-8">


                {{-- ================= HEADER ================= --}}

                <div class="flex items-center justify-between border-b border-slate-100 pb-4">

                    <div class="flex items-center gap-2.5">

                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                            <i data-lucide="message-square" class="h-4 w-4"></i>

                        </div>


                        <div>

                            <h3 class="text-base font-black text-slate-900">

                                Bình luận

                            </h3>

                            <p class="text-[10px] text-slate-400">

                                {{ $comments->count() }} bình luận

                            </p>

                        </div>

                    </div>

                </div>



                {{-- ================= COMMENTS LIST ================= --}}

                <div class="divide-y divide-slate-100">


                    @forelse($comments as $comment)


                        <div class="py-5 first:pt-5">


                            <div class="flex items-start gap-3 sm:gap-4">


                                {{-- Avatar --}}

                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">

                                    {{ strtoupper(substr($comment->user_name ?? 'U', 0, 1)) }}

                                </div>



                                <div class="min-w-0 flex-1">


                                    {{-- Comment header --}}

                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">


                                        <h4 class="flex flex-wrap items-center gap-1.5 text-xs font-bold text-slate-800">

                                            {{ $comment->user_name ?? 'Khách vãng lai' }}


                                            @if($comment->user && $comment->user->isAdmin())

                                                <span class="rounded-md bg-indigo-600 px-1.5 py-0.5 text-[8px] font-bold text-white">

                                                    QTV

                                                </span>

                                            @endif

                                        </h4>



                                        <span class="text-[10px] text-slate-400">

                                            {{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}

                                        </span>

                                    </div>



                                    {{-- Comment content --}}

                                    <div class="mt-2 rounded-xl bg-slate-50 px-4 py-3">

                                        <p class="text-xs leading-relaxed text-slate-700">

                                            {{ $comment->content }}

                                        </p>

                                    </div>


                                </div>

                            </div>



                            {{-- ================= REPLIES ================= --}}

                            @if($comment->replies && $comment->replies->count() > 0)

                                <div class="ml-5 mt-4 space-y-3 border-l border-indigo-100 pl-4 sm:ml-12">


                                    @foreach($comment->replies as $reply)


                                        <div class="flex items-start gap-3 rounded-xl border border-indigo-100 bg-indigo-50/40 p-3">


                                            {{-- Avatar --}}

                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white">

                                                {{ strtoupper(substr($reply->user_name ?? 'A', 0, 1)) }}

                                            </div>



                                            <div class="min-w-0 flex-1">


                                                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">


                                                    <h5 class="flex flex-wrap items-center gap-1 text-xs font-bold text-slate-800">

                                                        {{ $reply->user_name }}


                                                        @if($reply->user && $reply->user->isAdmin())

                                                            <span class="rounded bg-indigo-600 px-1.5 py-0.5 text-[8px] text-white">

                                                                QTV

                                                            </span>

                                                        @endif

                                                    </h5>



                                                    <span class="text-[9px] text-slate-400">

                                                        {{ $reply->created_at ? $reply->created_at->diffForHumans() : '' }}

                                                    </span>

                                                </div>



                                                <p class="mt-2 text-xs leading-relaxed text-slate-700">

                                                    {{ $reply->content }}

                                                </p>

                                            </div>

                                        </div>


                                    @endforeach


                                </div>

                            @endif


                        </div>


                    @empty


                        {{-- Empty comments --}}

                        <div class="py-10 text-center">


                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-50 text-slate-400">

                                <i data-lucide="message-circle" class="h-5 w-5"></i>

                            </div>


                            <p class="mt-3 text-xs text-slate-400">

                                Chưa có bình luận nào cho bài viết này.

                            </p>


                            <p class="mt-1 text-[10px] text-slate-400">

                                Hãy là người đầu tiên bình luận!

                            </p>

                        </div>


                    @endforelse


                </div>



                {{-- ================================================= --}}
                {{-- ================= COMMENT FORM ================= --}}
                {{-- ================================================= --}}

                @if($post->alow_comments)


                    <div class="mt-3 border-t border-slate-100 pt-6">


                        <div class="mb-5">

                            <h4 class="text-sm font-bold text-slate-800">

                                Viết bình luận của bạn

                            </h4>

                            <p class="mt-1 text-[10px] text-slate-400">

                                Chia sẻ ý kiến của bạn về bài viết này.

                            </p>

                        </div>



                        <form
                            action="{{ route('blog.comment.store', $post->id) }}"
                            method="POST"
                            class="space-y-4">

                            @csrf



                            {{-- Guest information --}}

                            @if(!Auth::check())


                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">


                                    {{-- Name --}}

                                    <div>

                                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                                            Họ và tên

                                            <span class="text-rose-500">*</span>

                                        </label>


                                        <input
                                            type="text"
                                            name="user_name"
                                            value="{{ old('user_name') }}"
                                            required
                                            placeholder="Nguyễn Văn A"

                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                                    </div>



                                    {{-- Email --}}

                                    <div>

                                        <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                                            Email

                                            <span class="text-rose-500">*</span>

                                        </label>


                                        <input
                                            type="email"
                                            name="user_email"
                                            value="{{ old('user_email') }}"
                                            required
                                            placeholder="email@example.com"

                                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                                    </div>


                                </div>


                            @else


                                {{-- Logged in user --}}

                                <div class="flex items-center gap-2.5 rounded-xl border border-indigo-100 bg-indigo-50/60 px-4 py-3 text-xs text-slate-600">


                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-600">

                                        <i data-lucide="user" class="h-3.5 w-3.5"></i>

                                    </div>


                                    <div class="min-w-0">

                                        Đang bình luận dưới tên:

                                        <span class="font-bold text-slate-800">

                                            {{ Auth::user()->fullname }}

                                        </span>

                                        <span class="text-slate-400">

                                            ({{ Auth::user()->email }})

                                        </span>

                                    </div>

                                </div>


                            @endif



                            {{-- Comment content --}}

                            <div>

                                <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                                    Nội dung bình luận

                                    <span class="text-rose-500">*</span>

                                </label>


                                <textarea
                                    name="content"
                                    rows="4"
                                    required
                                    placeholder="Nhập nội dung bình luận tại đây..."

                                    class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs leading-relaxed text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10"></textarea>

                            </div>



                            {{-- Submit button --}}

                            <button
                                type="submit"

                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-5 py-3 text-xs font-bold text-white shadow-sm shadow-indigo-600/20 transition duration-200 hover:bg-indigo-700 hover:shadow-md active:scale-[0.98]">

                                <i data-lucide="send" class="h-3.5 w-3.5"></i>

                                Gửi bình luận

                            </button>


                        </form>


                    </div>


                @else


                    {{-- Comments disabled --}}

                    <div class="mt-6 flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-4 text-xs text-slate-500">

                        <i data-lucide="message-square-off" class="h-4 w-4 text-slate-400"></i>

                        Bài viết này đã tắt tính năng bình luận.

                    </div>


                @endif


            </section>


        </main>

    </div>

</div>

@endsection