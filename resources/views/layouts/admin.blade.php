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
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white shrink-0 hidden md:flex flex-col border-r border-slate-800">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <span class="font-black tracking-wider text-indigo-400">VELOCE ADMIN</span>
        </div>
        <nav class="flex-1 p-4 space-y-1">

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="package" class="w-5 h-5"></i>
                Sản phẩm
            </a>

            <a href="{{ route('admin.category.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="folder-tree" class="w-5 h-5"></i>
                Danh mục
            </a>

            <a href="{{ route('admin.brand.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="tag" class="w-5 h-5"></i>
                Thương hiệu
            </a>

            <a href="{{ route('admin.attributes.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="sliders" class="w-5 h-5"></i>
                Thuộc tính
            </a>

            <a href="{{ route('admin.orders.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                Đơn hàng
            </a>

            <a href="{{ route('admin.refunds.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition {{ request()->routeIs('admin.refunds.*') ? 'bg-slate-800 text-white font-bold' : '' }}">
                <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                Trả hàng
            </a>

            <a href="{{ route('admin.customers.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="users" class="w-5 h-5"></i>
                Khách hàng
            </a>

            <a href="{{ route('admin.banner.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="image" class="w-5 h-5"></i>
                Banner
            </a>

            <a href="{{ route('admin.voucher.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="ticket-percent" class="w-5 h-5"></i>
                Voucher
            </a>

            <a href="{{ route('admin.news.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
                Tin tức
            </a>

            <a href="{{ route('admin.reviews.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="star" class="w-5 h-5"></i>
                Đánh giá
            </a>

            <a href="{{ route('admin.managers.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
                Quản lý
            </a>

            <a href="{{ route('admin.contacts.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition {{ request()->routeIs('admin.contacts.*') ? 'bg-slate-800 text-white font-bold' : '' }}">
                <i data-lucide="mail" class="w-5 h-5"></i>
                Liên hệ
            </a>

            <a href="{{ route('admin.settings.index') }}"
                class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition">
                <i data-lucide="settings" class="w-5 h-5"></i>
                Cài đặt
            </a>

        </nav>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col overflow-x-hidden">
        <!-- Top bar -->
        <header class="h-16 bg-white border-b border-slate-100 flex items-center justify-between px-8">
            <h1 class="text-lg font-bold text-slate-800">@yield('page_title', 'Hệ thống Quản lý')</h1>
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full">Admin Account</span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-8 flex-1">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
