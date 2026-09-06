<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin Dashboard - Veloce ShoeShop')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 999px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-slate-50 text-slate-800 antialiased">

    {{-- Overlay Mobile --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 z-40 hidden bg-slate-950/50 backdrop-blur-[2px] md:hidden">
    </div>

    {{-- Sidebar --}}
    <aside id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col border-r border-slate-800 bg-slate-900 text-white -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0">

        {{-- Logo --}}
        <div class="flex h-16 shrink-0 items-center justify-between border-b border-slate-800 px-5">
            <span class="text-lg font-black tracking-[0.12em] text-indigo-300">
                VELOCE ADMIN
            </span>

            <button id="close-sidebar-btn" type="button"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-800 hover:text-white md:hidden">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 py-5">

            {{-- QUẢN LÝ --}}
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                Quản lý
            </p>

            <div class="space-y-1">

                <a href="{{ route('admin.dashboard') }}"
                    class="group relative flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-900/30'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="layout-dashboard"
                        class="h-5 w-5 transition-transform duration-200 group-hover:scale-110"></i>

                    <span class="font-medium">Tổng quan</span>

                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute right-3 h-2 w-2 rounded-full bg-white"></span>
                    @endif
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.products.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="package"
                        class="h-5 w-5 transition-transform duration-200 group-hover:scale-110"></i>

                    <span class="font-medium">Sản phẩm</span>
                </a>

                <a href="{{ route('admin.category.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.category.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="folder-tree"
                        class="h-5 w-5 transition-transform duration-200 group-hover:scale-110"></i>

                    <span class="font-medium">Danh mục</span>
                </a>

                <a href="{{ route('admin.brand.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.brand.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="tag"
                        class="h-5 w-5 transition-transform duration-200 group-hover:scale-110"></i>

                    <span class="font-medium">Thương hiệu</span>
                </a>

                <a href="{{ route('admin.attributes.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.attributes.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="sliders-horizontal"
                        class="h-5 w-5 transition-transform duration-300 group-hover:rotate-90"></i>

                    <span class="font-medium">Thuộc tính</span>
                </a>
            </div>

            {{-- BÁN HÀNG --}}
            <p class="mb-2 mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                Bán hàng
            </p>

            <div class="space-y-1">

                <a href="{{ route('admin.orders.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.orders.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="shopping-cart"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Đơn hàng</span>
                </a>

                <a href="{{ route('admin.refunds.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.refunds.*')
                        ? 'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow-lg shadow-purple-900/30'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="refresh-cw"
                        class="h-5 w-5 transition-transform duration-500 group-hover:rotate-180"></i>

                    <span class="font-medium">Trả hàng</span>
                </a>

                <a href="{{ route('admin.customers.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.customers.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="users"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Khách hàng</span>
                </a>

                <a href="{{ route('admin.shipping-rates.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.shipping-rates.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="truck"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Phí vận chuyển</span>
                </a>
            </div>

            {{-- NỘI DUNG --}}
            <p class="mb-2 mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                Nội dung
            </p>

            <div class="space-y-1">

                <a href="{{ route('admin.banner.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.banner.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="image"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Banner</span>
                </a>

                <a href="{{ route('admin.voucher.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.voucher.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="ticket-percent"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Voucher</span>
                </a>

                <a href="{{ route('admin.news.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.news.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="newspaper"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Tin tức</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.reviews.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="star"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Đánh giá</span>
                </a>
            </div>

            {{-- HỆ THỐNG --}}
            <p class="mb-2 mt-7 px-3 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">
                Hệ thống
            </p>

            <div class="space-y-1 pb-5">

                <a href="{{ route('admin.contacts.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.contacts.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="mail"
                        class="h-5 w-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Liên hệ</span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 transition-all duration-200
                    {{ request()->routeIs('admin.settings.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="settings"
                        class="h-5 w-5 transition-transform duration-500 group-hover:rotate-90"></i>

                    <span class="font-medium">Cài đặt</span>
                </a>
            </div>
        </nav>
    </aside>

    {{-- MAIN --}}
    <div class="min-h-screen min-w-0 md:ml-64">

        {{-- HEADER --}}
        <header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 md:px-8">

            {{-- Left --}}
            <div class="flex min-w-0 items-center gap-3">

                <button id="mobile-menu-btn" type="button"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 md:hidden">

                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>

                <h1 class="truncate text-base font-bold text-slate-800 sm:text-lg">
                    @yield('page_title', 'Hệ thống Quản lý')
                </h1>
            </div>

            {{-- Right --}}
            <div class="relative">

                <button id="user-menu-btn" type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 transition hover:bg-indigo-100 hover:text-indigo-600">

                    <i data-lucide="user" class="h-5 w-5"></i>
                </button>

                {{-- User Dropdown --}}
                <div id="user-dropdown"
                    class="absolute right-0 top-12 z-50 hidden w-52 overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-xl">

                    <div class="border-b border-slate-100 px-4 py-3">
                        <p class="text-xs text-slate-500">
                            Admin: <span class="font-semibold text-slate-800">{{ Auth::user()->fullname }}</span>
                        </p>

                        <p class="mt-0.5 text-xs text-slate-500">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <a href="{{ route('home') }}"
                        class="flex items-center gap-2 px-4 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-indigo-600">

                        <i data-lucide="house" class="h-4 w-4"></i>
                        Trang chủ
                    </a>

                    <div class="my-1 border-t border-slate-100"></div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit"
                            class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm font-medium text-rose-600 transition hover:bg-rose-50">

                            <i data-lucide="log-out" class="h-4 w-4"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="min-w-0 p-4 sm:p-6 md:p-8">
            @yield('content')
        </main>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        lucide.createIcons();

        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const adminSidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            if (!adminSidebar || !sidebarOverlay) return;

            adminSidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            if (!adminSidebar || !sidebarOverlay) return;

            adminSidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', openSidebar);
        }

        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', closeSidebar);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                if (sidebarOverlay) {
                    sidebarOverlay.classList.add('hidden');
                }

                document.body.classList.remove('overflow-hidden');
            }
        });

        {{-- User Menu --}}
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');

        if (userMenuBtn && userDropdown) {

            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!userMenuBtn.contains(e.target) &&
                    !userDropdown.contains(e.target)) {

                    userDropdown.classList.add('hidden');
                }
            });
        }

        {{-- Tự động ẩn lỗi validation --}}
        document.addEventListener('DOMContentLoaded', function() {
            const errorElements = document.querySelectorAll('.auto-hide-error');

            if (errorElements.length > 0) {
                setTimeout(function() {
                    errorElements.forEach(function(el) {
                        el.style.transition = 'opacity 0.5s ease-out';
                        el.style.opacity = '0';

                        setTimeout(function() {
                            el.remove();
                        }, 500);
                    });
                }, 4000);
            }
        });
    </script>

    @yield('scripts')

</body>
</html>