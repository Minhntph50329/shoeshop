<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Veloce eCommerce')</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#fafafa] text-slate-800 antialiased min-h-screen flex flex-col">

    <!-- Header / Navigation -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-black text-xl tracking-tight text-slate-900">
                <span class="bg-indigo-600 text-white p-1.5 rounded-lg text-xs leading-none">V</span>
                VELOCE
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-600">
                <a href="/" class="hover:text-indigo-600">Trang chủ</a>
                <a href="/shop" class="hover:text-indigo-600">Cửa hàng</a>
                <a href="/blog" class="hover:text-indigo-600">Tin tức</a>
                <a href="/contact" class="hover:text-indigo-600">Liên hệ</a>
            </nav>
            <div class="flex items-center gap-4">
                @php
                    $cartCount = auth()->check() && auth()->user()->activeCart ? auth()->user()->activeCart->items->sum('quantity') : 0;
                @endphp
                <a href="{{ route('cart') }}" class="relative p-2 hover:bg-slate-50 rounded-full text-slate-700">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-indigo-600 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
                    @else
                        <span class="absolute -top-1 -right-1 bg-slate-300 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">0</span>
                    @endif
                </a>
                @php
                    $wishlistCount = auth()->check()
                        ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
                        : 0;
                @endphp
                <a href="{{ route('wishlist') }}" class="relative p-2 hover:bg-slate-50 rounded-full text-slate-700" title="Yêu thích">
                    <i data-lucide="heart" class="w-5 h-5 {{ $wishlistCount > 0 ? 'text-rose-500' : '' }}"></i>
                    @if($wishlistCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 p-1 hover:bg-slate-50 rounded-full text-slate-700 transition focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->fullname }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                            @else
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                    {{ substr(auth()->user()->fullname ?? auth()->user()->email, 0, 1) }}
                                </div>
                            @endif
                        </button>

                        <div class="absolute right-0 top-full pt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 overflow-hidden z-50">
                            <div class="px-5 py-4 bg-slate-50 border-b">
                                <div class="flex items-center gap-3">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->fullname }}" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">
                                            {{ substr(auth()->user()->fullname ?? auth()->user()->email, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="font-semibold text-slate-900 text-xs line-clamp-1">
                                            {{ auth()->user()->fullname ?? auth()->user()->email }}
                                        </h6>
                                        <small class="text-slate-500 text-[11px]">
                                            {{ auth()->user()->isAdmin() ? 'Quản trị viên' : 'Thành viên' }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2 text-xs">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-purple-600 font-bold hover:bg-purple-50">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Trang quản trị Admin
                                    </a>
                                @endif
                                <a href="{{ route('profile') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-100 text-slate-700">
                                    <i data-lucide="user-circle" class="w-4 h-4"></i> Hồ sơ cá nhân
                                </a>
                                <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-100 text-slate-700">
                                    <i data-lucide="package-check" class="w-4 h-4"></i> Đơn hàng của tôi
                                </a>
                                @if(auth()->user()->role === 'client')
                                <a href="{{ route('my-contacts') }}" class="flex items-center gap-3 px-5 py-2.5 hover:bg-slate-100 text-slate-700">
                                    <i data-lucide="mail" class="w-4 h-4"></i> Tin nhắn của tôi
                                </a>
                                @endif
                            </div>

                            <div class="border-t">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-5 py-3 text-xs text-red-500 font-bold hover:bg-red-50 text-left transition">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200">
                            Đăng ký
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Stage -->
    <main class="flex-1 max-w-7xl mx-auto px-4 w-full py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="space-y-4">
                <span class="text-white font-black text-lg">VELOCE</span>
                <p class="text-xs leading-relaxed">Trải nghiệm mua sắm thời trang công nghệ đẳng cấp nhất với Veloce Store.</p>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Danh mục sản phẩm</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white">Giày sneaker</a></li>
                    <li><a href="#" class="hover:text-white">Áo khoác gió</a></li>
                    <li><a href="#" class="hover:text-white">Phụ kiện thời trang</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Chính sách</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="#" class="hover:text-white">Chính sách đổi trả</a></li>
                    <li><a href="#" class="hover:text-white">Chính sách bảo hành</a></li>
                    <li><a href="#" class="hover:text-white">Điều khoản sử dụng</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white text-xs font-bold uppercase tracking-wider mb-4">Liên hệ</h4>
                <p class="text-xs">Email: support@veloce.vn</p>
                <p class="text-xs">Phone: 1900 6789</p>
            </div>
        </div>
    </footer>

    <!-- Toast Notification -->
    @if(session('success') || session('error') || session('info'))
    <div id="toast-notification"
        class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl text-sm font-semibold
            max-w-sm w-full border
            {{ session('success') ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : '' }}
            {{ session('error')   ? 'bg-rose-50 border-rose-200 text-rose-800'         : '' }}
            {{ session('info')    ? 'bg-indigo-50 border-indigo-200 text-indigo-800'   : '' }}
            translate-x-0 transition-all duration-500"
        style="animation: slideInRight 0.4s ease-out;">

        {{-- Icon --}}
        @if(session('success'))
            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6L9 17l-5-5"/>
                </svg>
            </div>
        @elseif(session('error'))
            <div class="w-9 h-9 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
        @else
            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        @endif

        {{-- Message --}}
        <span class="flex-1 leading-snug">{{ session('success') ?? session('error') ?? session('info') }}</span>

        {{-- Close Button --}}
        <button onclick="closeToast()" class="ml-1 opacity-50 hover:opacity-100 transition shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>

        {{-- Progress Bar --}}
        <div class="absolute bottom-0 left-0 h-1 rounded-b-2xl
            {{ session('success') ? 'bg-emerald-400' : (session('error') ? 'bg-rose-400' : 'bg-indigo-400') }}"
            id="toast-progress" style="width: 100%; transition: width 3s linear;"></div>
    </div>
    @endif

    <style>
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(100px); }
            to   { opacity: 1; transform: translateX(0); }
        }
    </style>

    <script>
        lucide.createIcons();

        // Toast auto-dismiss
        (function() {
            const toast = document.getElementById('toast-notification');
            if (!toast) return;
            const bar = document.getElementById('toast-progress');

            // Start progress bar shrink
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    if (bar) bar.style.width = '0%';
                });
            });

            // Auto hide after 3.5s
            setTimeout(() => dismissToast(), 3500);
        })();

        function dismissToast() {
            const toast = document.getElementById('toast-notification');
            if (!toast) return;
            toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            setTimeout(() => toast.remove(), 400);
        }

        function closeToast() { dismissToast(); }
    </script>
</body>
</html>
