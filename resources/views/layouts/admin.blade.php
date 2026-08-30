<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard - Veloce')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex overflow-x-hidden w-full">

    <!-- Sidebar -->
    <!-- Added fixed positioning for mobile sidebar and z-index -->
    <aside id="admin-sidebar" class="w-64 bg-slate-900 text-white shrink-0 hidden md:flex flex-col border-r border-slate-800 fixed md:sticky top-0 h-screen overflow-y-auto z-50">
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800">
            <span class="font-black tracking-wider text-indigo-400">VELOCE ADMIN</span>
            <!-- Close button for mobile -->
            <button id="close-sidebar-btn" class="md:hidden text-slate-300 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <nav class="flex-1 px-3 py-5 overflow-y-auto">

            {{-- ================= QUẢN LÝ ================= --}}
            <p class="px-3 mb-2 text-[10px] font-bold tracking-[0.18em] text-slate-500 uppercase">
                Quản lý
            </p>

            <div class="space-y-1">

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="group relative flex items-center gap-3 px-3 py-3 rounded-xl
                    transition-all duration-200
                    {{ request()->routeIs('admin.dashboard')
                        ? 'bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-900/30'
                        : 'text-slate-400 hover:bg-slate-800/80 hover:text-white' }}">

                    <i data-lucide="layout-dashboard"
                        class="w-5 h-5 transition-transform duration-200 group-hover:scale-110"></i>

                    <span class="font-medium">
                        Dashboard
                    </span>

                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute right-3 w-2 h-2 bg-white rounded-full"></span>
                    @endif
                </a>


                {{-- Sản phẩm --}}
                <a href="{{ route('admin.products.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.products.*')
                        ? 'bg-slate-800 text-white shadow-md'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="package"
                        class="w-5 h-5 transition-transform group-hover:scale-110"></i>

                    <span class="font-medium">Sản phẩm</span>
                </a>


                {{-- Danh mục --}}
                <a href="{{ route('admin.category.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.category.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="folder-tree"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Danh mục</span>
                </a>


                {{-- Thương hiệu --}}
                <a href="{{ route('admin.brand.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.brand.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="tag"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Thương hiệu</span>
                </a>


                {{-- Thuộc tính --}}
                <a href="{{ route('admin.attributes.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.attributes.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="sliders-horizontal"
                        class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300"></i>

                    <span class="font-medium">Thuộc tính</span>
                </a>

            </div>


            {{-- ================= BÁN HÀNG ================= --}}
            <p class="px-3 mt-7 mb-2 text-[10px] font-bold tracking-[0.18em] text-slate-500 uppercase">
                Bán hàng
            </p>

            <div class="space-y-1">

                {{-- Đơn hàng --}}
                <a href="{{ route('admin.orders.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.orders.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="shopping-cart"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Đơn hàng</span>
                </a>


                {{-- Trả hàng --}}
                <a href="{{ route('admin.refunds.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.refunds.*')
                        ? 'bg-gradient-to-r from-violet-600 to-purple-600 text-white shadow-lg shadow-purple-900/30'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="refresh-cw"
                        class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500"></i>

                    <span class="font-medium">Trả hàng</span>
                </a>


                {{-- Khách hàng --}}
                <a href="{{ route('admin.customers.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200
                    {{ request()->routeIs('admin.customers.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="users"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Khách hàng</span>
                </a>

            </div>


            {{-- ================= NỘI DUNG ================= --}}
            <p class="px-3 mt-7 mb-2 text-[10px] font-bold tracking-[0.18em] text-slate-500 uppercase">
                Nội dung
            </p>

            <div class="space-y-1">

                <a href="{{ route('admin.banner.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.banner.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="image"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Banner</span>
                </a>


                <a href="{{ route('admin.voucher.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.voucher.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="ticket-percent"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Voucher</span>
                </a>


                <a href="{{ route('admin.news.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.news.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="newspaper"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Tin tức</span>
                </a>


                <a href="{{ route('admin.reviews.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.reviews.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="star"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Đánh giá</span>
                </a>

            </div>


            {{-- ================= HỆ THỐNG ================= --}}
            <p class="px-3 mt-7 mb-2 text-[10px] font-bold tracking-[0.18em] text-slate-500 uppercase">
                Hệ thống
            </p>

            <div class="space-y-1 pb-4">

                <a href="{{ route('admin.contacts.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.contacts.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="mail"
                        class="w-5 h-5 group-hover:scale-110 transition-transform"></i>

                    <span class="font-medium">Liên hệ</span>
                </a>


                <a href="{{ route('admin.settings.index') }}"
                    class="group flex items-center gap-3 px-3 py-3 rounded-xl transition-all
                    {{ request()->routeIs('admin.settings.*')
                        ? 'bg-slate-800 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">

                    <i data-lucide="settings"
                        class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500"></i>

                    <span class="font-medium">Cài đặt</span>
                </a>

            </div>

        </nav>
    </aside>

    <!-- Overlay cho mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900 bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-4 md:px-8 shrink-0">
            <div class="flex items-center gap-3">
                <button id="mobile-menu-btn" class="md:hidden text-slate-600 hover:text-slate-900 transition focus:outline-none">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'Hệ thống Quản lý')</h1>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-xs font-semibold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full">Trang chủ</a>
                <span class="text-xs font-semibold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full">Admin Account</span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 md:p-8 flex-1 overflow-x-hidden w-full">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
        
        // Logic for mobile sidebar
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const adminSidebar = document.getElementById('admin-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            adminSidebar.classList.toggle('hidden');
            adminSidebar.classList.toggle('flex');
            sidebarOverlay.classList.toggle('hidden');
        }

        mobileMenuBtn.addEventListener('click', toggleSidebar);
        closeSidebarBtn.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Tự động ẩn các lỗi validation sau 4 giây
        document.addEventListener('DOMContentLoaded', function() {
            const errorElements = document.querySelectorAll('.auto-hide-error');
            if (errorElements.length > 0) {
                setTimeout(function() {
                    errorElements.forEach(el => {
                        el.style.transition = 'opacity 0.5s ease-out';
                        el.style.opacity = '0';
                        setTimeout(() => el.remove(), 500);
                    });
                }, 4000); // 4 giây
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
