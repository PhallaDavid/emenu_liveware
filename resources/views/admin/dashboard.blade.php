<x-app-layout>
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.hello') }},
                {{ Auth::user()->name }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('messages.dashboard_desc') }}</p>
        </div>
    </div>

    <!-- Active Orders Alert Banner -->
    <div x-data="{ count: 0 }" x-init="fetch('/api/check-orders').then(r => r.json()).then(d => count = d.count);
    setInterval(() => {
        fetch('/api/check-orders').then(r => r.json()).then(d => count = d.count);
    }, 15000);">
        <template x-if="count > 0">
            <div
                class="mb-8 p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-2xl flex items-center justify-between animate-pulse">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 bg-yellow-400 text-white rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-yellow-900 dark:text-yellow-100">
                            {{ __('messages.pending_orders_detected') }}</h4>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300">
                            {{ __('messages.pending_orders_desc', ['count' => '']) }} <span class="font-bold"
                                x-text="count"></span></p>
                    </div>
                </div>
                <a href="{{ route('orders.index') }}"
                    class="bg-yellow-400 hover:bg-yellow-500 text-yellow-900 px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-md">
                    {{ __('messages.handle_now') }}
                </a>
            </div>
        </template>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1 -->
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p
                    class="text-[10px] text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest leading-none mb-2">
                    {{ __('messages.todays_orders') }}</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ $stats['orders'] }}</h3>
            </div>
            <div
                class="w-10 h-10 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p
                    class="text-[10px] text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest leading-none mb-2">
                    {{ __('messages.todays_revenue') }}</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100">
                    ${{ number_format($stats['revenue'], 2) }}</h3>
            </div>
            <div
                class="w-10 h-10 bg-green-50 dark:bg-green-900/40 text-green-600 dark:text-green-300 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p
                    class="text-[10px] text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest leading-none mb-2">
                    {{ __('messages.total_products') }}</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ $stats['products'] }}</h3>
            </div>
            <div
                class="w-10 h-10 bg-orange-50 dark:bg-orange-900/40 text-orange-600 dark:text-orange-300 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
        </div>

        <!-- Card 4 -->
        <div
            class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <p
                    class="text-[10px] text-gray-400 dark:text-gray-500 font-black uppercase tracking-widest leading-none mb-2">
                    {{ __('messages.active_categories') }}</p>
                <h3 class="text-xl font-black text-gray-900 dark:text-gray-100">{{ $stats['categories'] }}</h3>
            </div>
            <div
                class="w-10 h-10 bg-purple-50 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Recent Orders -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] text-[10px]">
                    {{ __('messages.recent_orders') }}</h3>
                <a href="{{ route('orders.index') }}"
                    class="text-[10px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">{{ __('messages.view_all') }}
                    →</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($recentOrders as $order)
                    <div
                        class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-900/40 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-9 h-9 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center text-[10px] font-black text-gray-500">
                                #{{ $order->id }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate w-32 md:w-48">
                                    {{ $order->customer_name ?: __('messages.guest_order') }}
                                </p>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-0.5">
                                    {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900 dark:text-white">
                                ${{ number_format($order->total_price, 2) }}</p>
                            <span
                                class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full mt-1 inline-block
                                {{ $order->status === 'paid'
                                    ? 'bg-green-100 text-green-600'
                                    : ($order->status === 'cancelled'
                                        ? 'bg-red-100 text-red-600'
                                        : 'bg-blue-100 text-blue-600') }}">
                                {{ __('messages.' . ($order->status ?: 'pending')) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400 text-xs italic">{{ __('messages.no_orders') }}</div>
                @endforelse
            </div>
        </div>

        <!-- Recent Products -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 dark:border-gray-700 flex justify-between items-center">
                <h3 class="font-black text-gray-900 dark:text-white uppercase tracking-[0.2em] text-[10px]">
                    {{ __('messages.recent_products') }}</h3>
                <a href="{{ route('products.index') }}"
                    class="text-[10px] font-black text-blue-600 hover:text-blue-700 uppercase tracking-widest">{{ __('messages.view_catalog') }}
                    →</a>
            </div>
            <div class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($recentProducts as $product)
                    <div
                        class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/50 dark:hover:bg-gray-900/40 transition-colors">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-9 h-9 bg-blue-50 dark:bg-blue-900/20 rounded-xl flex items-center justify-center text-lg shadow-inner">
                                🍔
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate w-32 md:w-48">
                                    {{ $product->name }}
                                </p>
                                <p class="text-[10px] font-black uppercase tracking-widest text-blue-600 mt-0.5">
                                    {{ $product->category->name ?? __('messages.uncategorized') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-gray-900 dark:text-white">
                                ${{ number_format($product->price, 2) }}</p>
                            <p class="text-[9px] font-bold text-gray-400 mt-0.5 uppercase tracking-tighter">
                                {{ $product->is_available ? __('messages.in_stock') : __('messages.out_of_stock') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-gray-400 text-xs italic">{{ __('messages.no_products') }}</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Chart (Doughnut) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 text-gray-400 dark:text-gray-500">
                {{ __('messages.product_distribution') }}</h3>
            <div style="height: 180px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Revenue Chart (Bar) -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-4 text-gray-400 dark:text-gray-500">
                {{ __('messages.monthly_revenue') }}</h3>
            <div style="height: 180px; position: relative;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('products.create') }}"
            class="block p-5 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-center hover:border-blue-600 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all group">
            <div
                class="w-10 h-10 bg-gray-50 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 dark:group-hover:bg-blue-600 transition-colors">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('messages.add_new_product') }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.update_menu_desc') }}</p>
        </a>

        <a href="{{ route('categories.create') }}"
            class="block p-5 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl text-center hover:border-blue-600 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/10 transition-all group">
            <div
                class="w-10 h-10 bg-gray-50 dark:bg-gray-800 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-blue-600 dark:group-hover:bg-blue-600 transition-colors">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('messages.create_category') }}</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ __('messages.organize_menu_desc') }}</p>
        </a>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDarkMode = localStorage.getItem('darkMode') === 'true';
                const textColor = isDarkMode ? '#94a3b8' : '#64748b';
                const gridColor = isDarkMode ? '#334155' : '#f1f5f9';

                // Sales Chart (Doughnut)
                const ctxSales = document.getElementById('salesChart').getContext('2d');
                new Chart(ctxSales, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($chartData['labels']) !!},
                        datasets: [{
                            data: {!! json_encode($chartData['data']) !!},
                            backgroundColor: [
                                '#3b82f6',
                                '#10b981',
                                '#f59e0b',
                                '#8b5cf6',
                                '#ef4444'
                            ],
                            borderWidth: 0,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: textColor,
                                    font: {
                                        size: 10,
                                        weight: 'bold'
                                    },
                                    boxWidth: 10,
                                    padding: 15
                                }
                            }
                        }
                    }
                });

                // Revenue Chart (Bar)
                const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
                new Chart(ctxRevenue, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($months) !!},
                        datasets: [{
                            label: 'Revenue ($)',
                            data: {!! json_encode($revenueData) !!},
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                            barThickness: 12
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 9
                                    },
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: textColor,
                                    font: {
                                        size: 9
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
