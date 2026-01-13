<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QR Menu') }} Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,100..700;1,100..700&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '"Kantumruy Pro"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body
    class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 font-sans antialiased transition-colors duration-200">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar -->
        <aside
            class="w-full md:w-60 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <div class="p-6 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-black dark:text-white leading-none">QR Menu</h1>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                        {{ __('messages.admin_panel') }}</p>
                </div>
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="p-2 bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white rounded-xl transition-colors">
                    <svg x-show="!darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                        </path>
                    </svg>
                    <svg x-show="darkMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </button>
            </div>

            <nav class="space-y-6 px-4 mt-2 flex-1 overflow-y-auto custom-scrollbar">
                <!-- Group: Overview -->
                <div class="space-y-1">
                    <p class="px-3 text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2">
                        {{ __('messages.overview') }}</p>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.menu') }}</span>
                    </a>
                </div>

                <!-- Group: Operations -->
                <div class="space-y-1">
                    <p class="px-3 text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2">
                        {{ __('messages.operations') }}</p>

                    <!-- POS / Tables -->
                    <a href="{{ route('tables.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('tables.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('tables.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.table_mgmt') }}</span>
                    </a>

                    <!-- Orders -->
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('orders.index') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('orders.index') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7">
                            </path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.sale_orders') }}</span>
                        <span
                            class="ml-auto bg-red-500 text-white text-[8px] px-1.5 py-0.5 rounded-md font-black animate-pulse">LIVE</span>
                    </a>

                    <!-- Kitchen -->
                    <a href="{{ route('orders.kitchen') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('orders.kitchen') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('orders.kitchen') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                            </path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.kitchen_display') }}</span>
                    </a>
                </div>

                <!-- Group: Menu Configuration -->
                <div class="space-y-1">
                    <p class="px-3 text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2">
                        {{ __('messages.menu_setup') }}</p>

                    <a href="{{ route('categories.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('categories.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.manage_categories') }}</span>
                    </a>

                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('products.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span class="font-bold text-xs">{{ __('messages.product_catalog') }}</span>
                    </a>
                </div>

                <!-- Group: Administration -->
                @if (auth()->user()->role === 'admin')
                    <div class="space-y-1">
                        <p class="px-3 text-[9px] font-black uppercase text-gray-400 tracking-[0.2em] mb-2">
                            {{ __('messages.admin_control') }}</p>

                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('users.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="font-bold text-xs">{{ __('messages.staff_members') }}</span>
                        </a>

                        <a href="{{ route('roles.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group {{ request()->routeIs('roles.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-blue-600 dark:hover:text-white' }}">
                            <svg class="w-4 h-4 {{ request()->routeIs('roles.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-600 dark:text-gray-500' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                            <span class="font-bold text-xs">{{ __('messages.access_roles') }}</span>
                        </a>
                    </div>
                @endif
            </nav>

            <!-- Footer with User Info and Logout -->
            <div class="p-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                <div class="mb-3 px-3 text-[10px] dark:text-gray-400">
                    {{ __('messages.logged_in_as') }} <span
                        class="font-bold text-gray-900 dark:text-white capitalize">{{ auth()->user()->role }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 text-xs text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-xl transition-all duration-200 group">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="font-bold">{{ __('messages.logout') }}</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8 overflow-y-auto h-screen">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Notification Audio -->
    <audio id="orderNotificationSound" preload="auto">
        <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" type="audio/mpeg">
    </audio>

    <script>
        let lastPendingCount = 0;
        let lastWaiterCallCount = 0;
        let isFirstLoad = true;

        async function checkGlobalOrders() {
            try {
                const response = await fetch('/api/check-orders');
                const data = await response.json();

                // New Order Notification
                if (!isFirstLoad && data.count > lastPendingCount) {
                    playGlobalNotification();
                    showOrderToast('order');
                }

                // New Waiter Call Notification
                if (!isFirstLoad && data.waiter_calls > lastWaiterCallCount) {
                    playGlobalNotification();
                    showOrderToast('waiter');
                }

                // If counts increased, refresh relevant pages
                if (!isFirstLoad && (data.count > lastPendingCount || data.waiter_calls > lastWaiterCallCount)) {
                    if (window.location.pathname.includes('/orders') || window.location.pathname.includes('/kitchen')) {
                        setTimeout(() => window.location.reload(), 2000);
                    }
                }

                lastPendingCount = data.count;
                lastWaiterCallCount = data.waiter_calls;
                isFirstLoad = false;
            } catch (error) {
                console.error('Order check failed:', error);
            }
        }

        function playGlobalNotification() {
            const audio = document.getElementById('orderNotificationSound');
            if (audio) {
                audio.play().catch(e => {
                    const context = new(window.AudioContext || window.webkitAudioContext)();
                    const osc = context.createOscillator();
                    const gain = context.createGain();
                    osc.connect(gain);
                    gain.connect(context.destination);
                    osc.start();
                    osc.stop(context.currentTime + 0.5);
                });
            }
        }

        function showOrderToast(type = 'order') {
            const toast = document.createElement('div');

            if (type === 'order') {
                toast.className =
                    'fixed bottom-10 right-10 bg-black text-white px-8 py-5 rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] z-[9999] flex items-center gap-6 animate-bounce border border-white/10 backdrop-blur-xl';
                toast.innerHTML = `
                    <div class="relative">
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
                        <div class="bg-white/10 p-3 rounded-2xl">
                            <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-lg font-black tracking-tight">NEW ORDER RECEIVED!</h4>
                        <p class="text-sm text-gray-400 font-medium font-mono">Check incoming orders now</p>
                    </div>
                    <a href="/orders" class="bg-white text-black px-4 py-2 rounded-xl font-bold text-sm hover:scale-105 transition-transform">VIEW</a>
                `;
            } else {
                toast.className =
                    'fixed bottom-10 right-10 bg-red-600 text-white px-8 py-5 rounded-3xl shadow-[0_20px_50px_rgba(220,38,38,0.5)] z-[9999] flex items-center gap-6 animate-pulse border border-white/10';
                toast.innerHTML = `
                    <div class="bg-white/20 p-3 rounded-2xl">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-black tracking-tight uppercase">Waiter Called!</h4>
                        <p class="text-sm text-white/80 font-medium">A table needs assistance</p>
                    </div>
                    <a href="/orders" class="bg-white text-red-600 px-4 py-2 rounded-xl font-bold text-sm hover:scale-105 transition-transform">GO</a>
                `;
            }

            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 10000);
        }

        // Initialize polling
        checkGlobalOrders();
        setInterval(checkGlobalOrders, 15000);
    </script>

    @stack('scripts')
</body>

</html>
