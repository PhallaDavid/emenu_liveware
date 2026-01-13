<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">{{ __('messages.orders') }}</h2>
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
            <span class="text-sm text-gray-500">{{ __('messages.auto_refresh_active') }}</span>
        </div>
    </div>

    <div class="orders-container">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden orders-table">
            <table class="w-full text-left">
                <thead>
                    <tr
                        class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                        <th class="px-6 py-4">{{ __('messages.order_hash') }}</th>
                        <th class="px-6 py-4">{{ __('messages.table') }}</th>
                        <th class="px-6 py-4">{{ __('messages.items') }}</th>
                        <th class="px-6 py-4">{{ __('messages.total') }}</th>
                        <th class="px-6 py-4">{{ __('messages.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.time') }}</th>
                        <th class="px-6 py-4">{{ __('messages.special_instructions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($orders as $order)
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-750 {{ $order->is_calling_waiter ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            <td class="px-6 py-4">
                                <div class="font-bold dark:text-gray-200">#{{ $order->id }}</div>
                                @if ($order->is_calling_waiter)
                                    <div class="flex items-center gap-1 mt-1">
                                        <svg class="w-3 h-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span
                                            class="text-[10px] font-bold text-red-600 uppercase">{{ __('messages.calling_waiter') }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-lg dark:text-white">{{ $order->table_number }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if (count($order->items) > 0)
                                    <div class="space-y-1">
                                        @foreach ($order->items as $item)
                                            <div class="text-sm dark:text-gray-300">
                                                <span class="font-bold dark:text-white">{{ $item['qty'] ?? 1 }}x</span>
                                                {{ $item['name'] ?? __('messages.unknown_item') }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 italic">{{ __('messages.no_items') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-lg dark:text-white">
                                    ${{ number_format($order->total_price, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block px-2.5 py-1 text-xs font-bold uppercase rounded-full {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400' : ($order->status === 'completed' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400') }}">
                                    {{ __('messages.' . $order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm dark:text-gray-300">{{ $order->created_at->format('H:i') }}</div>
                                <div class="text-xs text-gray-400">{{ $order->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if ($order->special_instructions)
                                    <div class="text-sm text-red-600 dark:text-red-400 font-medium max-w-xs">
                                        {{ Str::limit($order->special_instructions, 50) }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto-refresh table every 15 seconds
            setInterval(function() {
                if (window.location.pathname.includes('/orders')) {
                    fetch(window.location.href)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            const currentGrid = document.querySelector('.orders-container');
                            const newGrid = doc.querySelector('.orders-container');
                            if (currentGrid && newGrid && currentGrid.innerHTML !== newGrid.innerHTML) {
                                currentGrid.innerHTML = newGrid.innerHTML;
                                console.log('Orders table updated');
                            }
                        })
                        .catch(err => console.error('Error refreshing orders:', err));
                }
            }, 15000);
        </script>
    @endpush
</x-app-layout>
