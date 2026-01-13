<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Kitchen Display</h2>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-500">Auto-refresh active</span>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                <span class="text-xs font-medium uppercase tracking-wider text-gray-400">Live</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 orders-grid">
        @foreach($orders as $order)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden incoming-order-card {{ $order->is_calling_waiter ? 'ring-4 ring-red-500 animate-pulse' : ($order->status === 'pending' ? 'ring-2 ring-yellow-400' : '') }}">
            <div class="p-4 {{ $order->is_calling_waiter ? 'bg-red-600 text-white' : ($order->status === 'pending' ? 'bg-yellow-50 dark:bg-yellow-900/20' : 'bg-gray-50 dark:bg-gray-900/20') }} border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="font-bold {{ $order->is_calling_waiter ? 'text-white' : 'dark:text-white' }}">Table {{ $order->table_number }}</h3>
                    <p class="text-[10px] {{ $order->is_calling_waiter ? 'text-white/80' : 'text-gray-400' }} uppercase tracking-widest">{{ $order->created_at->format('H:i') }} ({{ $order->created_at->diffForHumans() }})</p>
                    @if($order->updated_at->diffInMinutes($order->created_at) > 0 && $order->updated_at->diffInMinutes() < 2)
                    <span class="inline-block mt-1 px-2 py-0.5 text-[9px] rounded-full font-black uppercase bg-orange-500 text-white animate-pulse">
                        ⚡ UPDATED {{ $order->updated_at->diffForHumans() }}
                    </span>
                    @endif
                </div>
                @if($order->is_calling_waiter)
                <div class="flex items-center gap-2">
                    <span class="text-xl">🛎️</span>
                </div>
                @else
                <span class="px-2 py-1 text-[10px] rounded-full font-bold uppercase {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                    {{ $order->status }}
                </span>
                @endif
            </div>

            @if($order->is_calling_waiter)
            <div class="bg-red-500 text-white px-4 py-2 text-center text-[10px] font-black uppercase tracking-tighter">
                Customer calling for assistance!
            </div>
            @endif

            <div class="p-4">
                <ul class="space-y-3">
                    @foreach($order->items as $item)
                    <li class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-black text-white flex items-center justify-center font-bold text-sm">
                            {{ $item['qty'] }}
                        </span>
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 dark:text-white leading-tight">{{ $item['name'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>

                @if($order->special_instructions)
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-100 dark:border-red-900/30">
                    <p class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase mb-1">🔥 Special Instructions</p>
                    <p class="text-sm text-red-800 dark:text-red-300 italic whitespace-pre-wrap">{{ $order->special_instructions }}</p>
                </div>
                @endif
            </div>

            <div class="p-4 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700">
                @if($order->status === 'pending')
                <form action="{{ route('orders.update', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-all active:scale-95 shadow-lg shadow-green-500/30 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Done / Serving
                    </button>
                </form>
                @else
                <div class="text-center py-2">
                    <span class="text-sm font-bold text-green-600 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Ready to Serve
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    @push('scripts')
    <script>
        let previousOrdersHTML = '';
        let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE');
        
        function checkForNewOrders() {
            if (!window.location.pathname.includes('/kitchen')) {
                console.log('Not on kitchen page, skipping check');
                return;
            }
            
            console.log('Checking for new orders...');
            
            fetch(window.location.href)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const grid = document.querySelector('.orders-grid');
                    const newGrid = doc.querySelector('.orders-grid');
                    
                    if (grid && newGrid) {
                        const newHTML = newGrid.innerHTML;
                        
                        console.log('Previous HTML length:', previousOrdersHTML.length);
                        console.log('New HTML length:', newHTML.length);
                        
                        // Check if content changed
                        if (previousOrdersHTML && previousOrdersHTML !== newHTML) {
                            console.log('🔔 CHANGE DETECTED! Triggering alerts...');
                            
                            // Flash the screen
                            document.body.style.backgroundColor = '#fef3c7';
                            setTimeout(() => {
                                document.body.style.backgroundColor = '';
                            }, 300);
                            
                            // Play alert sound
                            audio.play().catch(e => console.log('Audio play failed:', e));
                            
                            // Show notification
                            showNotification('🔔 New Order Update!');
                        } else if (previousOrdersHTML) {
                            console.log('No changes detected');
                        } else {
                            console.log('First load - setting baseline');
                        }
                        
                        previousOrdersHTML = newHTML;
                        grid.innerHTML = newHTML;
                    } else {
                        console.error('Grid not found!', {grid: !!grid, newGrid: !!newGrid});
                    }
                })
                .catch(err => console.error('Error fetching orders:', err));
        }
        
        function showNotification(message) {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-2xl z-50 animate-bounce font-bold';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        }
        
        // Initial load
        console.log('Kitchen display initialized');
        setTimeout(() => {
            const grid = document.querySelector('.orders-grid');
            if (grid) {
                previousOrdersHTML = grid.innerHTML;
                console.log('Baseline set, HTML length:', previousOrdersHTML.length);
            } else {
                console.error('Grid not found on initial load!');
            }
        }, 1000);
        
        // Check every 5 seconds for faster updates
        setInterval(checkForNewOrders, 5000);
        console.log('Auto-refresh started (every 5 seconds)');
    </script>
    @endpush
</x-app-layout>
