<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Incoming Orders</h2>
        <span class="text-sm text-gray-500">Auto-refresh recommended</span>
    </div>

    <div class="grid grid-cols-1 gap-6 orders-grid">
        @foreach($orders as $order)
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 incoming-order-card {{ $order->is_calling_waiter ? 'ring-4 ring-red-500 ring-offset-2 dark:ring-offset-gray-900 animate-pulse' : '' }}">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded-full {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($order->status === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                        {{ $order->status }}
                    </span>
                    <h3 class="text-xl font-extrabold mt-2 dark:text-white">Table {{ $order->table_number }}</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $order->created_at->diffForHumans() }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black dark:text-white">${{ number_format($order->total_price, 2) }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Order #{{ $order->id }}</p>
                </div>
            </div>

            @if($order->is_calling_waiter)
            <div class="mb-4 bg-red-600 text-white p-4 rounded-xl flex items-center justify-between shadow-xl border-2 border-red-500">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-black uppercase text-sm leading-tight">Table Assistance Required</h4>
                        <p class="text-[10px] text-white/80 font-bold">The customer is waiting for you</p>
                    </div>
                </div>
                <form action="{{ route('orders.dismiss-waiter', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-white text-red-600 px-4 py-2 rounded-lg text-xs font-black uppercase hover:bg-gray-100 shadow-md">
                        Done
                    </button>
                </form>
            </div>
            @endif

            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 mb-4">
                <h4 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-2">Items</h4>
                <ul class="space-y-2">
                    @foreach($order->items as $item)
                    <li class="flex justify-between text-sm dark:text-gray-300">
                        <span><span class="font-bold dark:text-white">{{ $item['qty'] }}x</span> {{ $item['name'] }}</span>
                        <span class="text-gray-500 dark:text-gray-400">${{ $item['price'] * $item['qty'] }}</span>
                    </li>
                    @endforeach
                </ul>
                
                @if($order->special_instructions)
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="text-xs font-bold uppercase text-red-500 dark:text-red-400 mb-2 font-bold italic">Special Instructions</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap italic">{{ $order->special_instructions }}</p>
                </div>
                @endif
            </div>

            <div class="flex justify-end gap-3">
                @if($order->status !== 'completed')
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                        Mark Completed
                    </button>
                </form>
                @endif
                
                @if($order->status !== 'paid')
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="paid">
                    <button type="submit" class="bg-gray-800 dark:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-900 dark:hover:bg-gray-600 transition-colors">
                        Mark Paid
                    </button>
                </form>
                @else
                <a href="{{ route('orders.print', $order) }}" target="_blank" class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Invoice
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
        // Specific page logic if needed (e.g. keeping the grid sync without sound)
        setInterval(function() {
            if (window.location.pathname.includes('/orders')) {
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const grid = document.querySelector('.orders-grid');
                        const newGrid = doc.querySelector('.orders-grid');
                        if (grid && newGrid && grid.innerHTML !== newGrid.innerHTML) {
                           grid.innerHTML = newGrid.innerHTML;
                        }
                    });
            }
        }, 15000);
    </script>
    @endpush
</x-app-layout>
