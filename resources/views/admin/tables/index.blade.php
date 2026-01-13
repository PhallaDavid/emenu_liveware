<x-app-layout>
    <div class="h-screen flex flex-col" x-data="{
        selectedTable: null,
        searchQuery: '',
        openDetails(table) {
            this.selectedTable = table;
        },
        createModalOpen: false,
        editModalOpen: false,
        deleteConfirmOpen: false,
        editingTable: { name: '' },
        paymentMethod: 'cash',
        isAddingItem: false,
    
        // Improved addItem to avoid any reload
        async addItemSilently(productId) {
            if (this.isAddingItem) return;
            this.isAddingItem = true;
    
            try {
                const response = await fetch(`/tables/${this.selectedTable.id}/add-item`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                });
    
                const data = await response.json();
                if (data.success) {
                    this.selectedTable.current_order = data.order;
                    // Trigger a refresh of the background grid silently
                    this.refreshGrid();
                }
            } catch (error) {
                console.error('Error adding item:', error);
            } finally {
                this.isAddingItem = false;
            }
        },
    
        async refreshGrid() {
            const response = await fetch(window.location.href);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.querySelector('.tables-grid');
            if (newGrid) {
                document.querySelector('.tables-grid').innerHTML = newGrid.innerHTML;
            }
        }
    }">

        <!-- Create Table Modal -->
        <div x-show="createModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all"
                @click.away="createModalOpen = false">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">{{ __('messages.create_table') }}</h3>
                <form action="{{ route('tables.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.table_name') }}</label>
                        <input type="text" name="name"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:text-white focus:outline-none transition-all"
                            required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="createModalOpen = false"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Table Modal -->
        <div x-show="editModalOpen" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all"
                @click.away="editModalOpen = false">
                <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">{{ __('messages.edit_table') }}</h3>
                <form :action="`/tables/${selectedTable?.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.table_name') }}</label>
                        <input type="text" name="name" x-model="editingTable.name"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:text-white focus:outline-none transition-all"
                            required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">{{ __('messages.cancel') }}</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30">{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Details Modal (Replaces Sidebar) -->
        <div x-show="selectedTable" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 md:p-10">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform transition-all"
                @click.away="selectedTable = null">

                <template x-if="selectedTable">
                    <div class="flex-1 flex flex-col min-h-0">
                        <!-- Modal Header -->
                        <div
                            class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800 shrink-0">
                            <div>
                                <h3 class="text-2xl font-black text-gray-900 dark:text-white"
                                    x-text="selectedTable.name"></h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="text-xs font-black uppercase px-3 py-1 rounded-full tracking-wider"
                                        :class="selectedTable.status === 'inactive' ?
                                            'bg-gray-200 text-gray-600' :
                                            (selectedTable.current_order ? 'bg-red-100 text-red-600' :
                                                'bg-green-100 text-green-600')"
                                        x-text="selectedTable.status === 'inactive' ? '{{ __('messages.inactive') }}' : (selectedTable.current_order ? '{{ __('messages.occupied') }}' : '{{ __('messages.available') }}')"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button @click="editingTable = { ...selectedTable }; editModalOpen = true"
                                    class="p-3 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button @click="selectedTable = null"
                                    class="p-3 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-2xl transition-all">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex-1 flex overflow-hidden min-h-0">
                            <!-- Left Side: Order Details -->
                            <div
                                class="w-1/2 flex flex-col border-r border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800">
                                <template x-if="selectedTable.current_order">
                                    <div class="flex-1 flex flex-col min-h-0">
                                        <!-- Order Items (Scrollable) -->
                                        <div class="flex-1 overflow-y-auto p-6 space-y-4">
                                            <div class="flex justify-between items-center mb-4">
                                                <h4
                                                    class="font-black text-gray-900 dark:text-white uppercase text-[10px] tracking-[0.2em]">
                                                    {{ __('messages.items_ordered') }}</h4>
                                                <span
                                                    class="text-xs font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-500"
                                                    x-text="'#' + selectedTable.current_order.id"></span>
                                            </div>

                                            <div class="space-y-3">
                                                <template x-for="item in selectedTable.current_order.items">
                                                    <div
                                                        class="flex justify-between items-center p-4 bg-gray-50/50 dark:bg-gray-900/40 rounded-2xl border border-gray-100 dark:border-gray-800/50">
                                                        <div class="flex items-center gap-4">
                                                            <span
                                                                class="w-10 h-10 bg-black text-white rounded-xl flex items-center justify-center text-sm font-black shadow-lg shadow-black/20"
                                                                x-text="item.qty || item.quantity || 1"></span>
                                                            <div>
                                                                <span
                                                                    class="text-sm font-bold text-gray-900 dark:text-white"
                                                                    x-text="item.name"></span>
                                                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5"
                                                                    x-text="'$' + parseFloat(item.price).toFixed(2) + ' each'">
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <span class="text-lg font-black text-gray-900 dark:text-white"
                                                            x-text="'$' + (item.price * (item.qty || item.quantity || 1)).toFixed(2)"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Checkout & Total (Sticky at bottom) -->
                                        <div
                                            class="p-6 bg-gray-50/80 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 space-y-4">
                                            <div
                                                class="p-5 bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl text-white shadow-xl shadow-blue-600/20">
                                                <div class="flex justify-between items-end">
                                                    <div>
                                                        <p
                                                            class="text-[9px] font-black uppercase tracking-[0.2em] mb-1 opacity-70">
                                                            {{ __('messages.total_amount') }}</p>
                                                        <p class="text-2xl font-black"
                                                            x-text="'$' + parseFloat(selectedTable.current_order.total_price).toFixed(2)">
                                                        </p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p
                                                            class="text-[9px] font-black uppercase tracking-[0.2em] mb-1 opacity-70">
                                                            {{ __('messages.pay_method') }}</p>
                                                        <div class="flex gap-2">
                                                            <button @click="paymentMethod = 'cash'"
                                                                :class="paymentMethod === 'cash' ?
                                                                    'bg-white text-blue-600' : 'bg-white/20 text-white'"
                                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase transition-all">{{ __('messages.cash') }}</button>
                                                            <button @click="paymentMethod = 'card'"
                                                                :class="paymentMethod === 'card' ?
                                                                    'bg-white text-blue-600' : 'bg-white/20 text-white'"
                                                                class="px-3 py-1 rounded-lg text-[10px] font-black uppercase transition-all">{{ __('messages.card') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <button
                                                @click="if(confirm('Confirm payment of $' + parseFloat(selectedTable.current_order.total_price).toFixed(2) + ' via ' + paymentMethod + '?')) { 
                                                    const form = document.createElement('form');
                                                    form.method = 'POST';
                                                    form.action = `/tables/${selectedTable.id}/checkout`;
                                                    const csrf = document.createElement('input');
                                                    csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                                                    const pm = document.createElement('input');
                                                    pm.type = 'hidden'; pm.name = 'payment_method'; pm.value = paymentMethod;
                                                    form.appendChild(csrf); form.appendChild(pm);
                                                    document.body.appendChild(form); form.submit();
                                                }"
                                                class="w-full py-5 rounded-2xl font-black text-lg bg-green-500 hover:bg-green-600 text-white shadow-xl shadow-green-500/20 transition-all flex items-center justify-center gap-3 active:scale-95 group">
                                                <span>{{ __('messages.complete_order') }}</span>
                                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!selectedTable.current_order">
                                    <div
                                        class="flex flex-col items-center justify-center h-full p-10 text-center space-y-4">
                                        <div
                                            class="w-24 h-24 bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center text-5xl">
                                            ✨</div>
                                        <div>
                                            <p
                                                class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">
                                                {{ __('messages.table_ready') }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ __('messages.select_items_to_start') }}</p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Right Side: Add Items -->
                            <div class="w-1/2 flex flex-col bg-gray-50 dark:bg-gray-900/40" x-data="{ searchItem: '' }">
                                <div class="p-6 pb-0">
                                    <h4
                                        class="font-black text-gray-900 dark:text-white mb-4 uppercase text-xs tracking-[0.2em] flex items-center gap-2">
                                        <div x-show="isAddingItem"
                                            class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin">
                                        </div>
                                        <svg x-show="!isAddingItem" class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        {{ __('messages.menu_catalog') }}
                                    </h4>
                                    <input type="text" x-model="searchItem"
                                        :placeholder="'{{ __('messages.search_menu') }}'"
                                        class="w-full mb-6 px-4 py-3 rounded-2xl border-none bg-white dark:bg-gray-800 shadow-sm text-sm font-medium focus:ring-2 focus:ring-blue-600 transition-all">
                                </div>

                                <div class="flex-1 overflow-y-auto p-6 pt-0 space-y-6">
                                    @foreach ($categories as $category)
                                        <div
                                            x-show="!searchItem || '{{ strtolower($category->name) }}'.includes(searchItem.toLowerCase()) || $el.querySelectorAll('.product-clickable').length > 0">
                                            <h5
                                                class="text-xs font-black uppercase text-blue-600/80 mb-3 tracking-[0.15em] flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                                {{ $category->name }}
                                            </h5>
                                            <div class="grid grid-cols-1 gap-2">
                                                @foreach ($category->products as $product)
                                                    <div class="product-clickable group bg-white dark:bg-gray-800 p-4 rounded-2xl border border-transparent hover:border-blue-600 cursor-pointer flex justify-between items-center transition-all hover:shadow-lg active:scale-95"
                                                        :class="isAddingItem ? 'opacity-50 pointer-events-none' : ''"
                                                        x-show="!searchItem || '{{ strtolower($product->name) }}'.includes(searchItem.toLowerCase())"
                                                        @click="addItemSilently({{ $product->id }})">

                                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                                            <div
                                                                class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xl shadow-inner ring-4 ring-white dark:ring-gray-800">
                                                                🍔</div>
                                                            <div class="flex-1 min-w-0">
                                                                <p
                                                                    class="font-black text-gray-900 dark:text-white text-sm leading-tight truncate">
                                                                    {{ $product->name }}</p>
                                                                <p class="text-xs font-black text-blue-600 mt-1">
                                                                    ${{ number_format($product->price, 2) }}</p>
                                                            </div>
                                                        </div>
                                                        <div
                                                            class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-all">
                                                            <svg class="w-6 h-6 font-black" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Header -->
        <div
            class="px-8 py-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ __('messages.table_management') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('messages.table_management_desc') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-600"></span>
                        {{ __('messages.available') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span>
                        {{ __('messages.occupied') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-300"></span>
                        {{ __('messages.inactive') }}</span>
                </div>
                <!-- Toggle Status Button -->
                <button @click="createModalOpen = true"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 flex items-center gap-2 transition-all">+
                    {{ __('messages.create_table') }}</button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-hidden">
            <div class="h-full p-8 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6 tables-grid">
                    @foreach ($tables as $table)
                        <div @click="openDetails({{ $table->toJson() }})"
                            class="cursor-pointer group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-2 transition-all duration-200 hover:shadow-xl hover:-translate-y-1
                         {{ $table->status === 'inactive' ? 'border-gray-200 dark:border-gray-700 opacity-75' : ($table->current_order ? 'border-red-500 bg-red-50/50' : 'border-blue-600 hover:bg-blue-50/50') }}">

                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-lg font-black text-gray-800 dark:text-white">{{ $table->name }}</h3>
                                <span
                                    class="w-4 h-4 rounded-full {{ $table->status === 'inactive' ? 'bg-gray-300' : ($table->current_order ? 'bg-red-500 animate-pulse' : 'bg-blue-600') }}"></span>
                            </div>

                            <div class="space-y-2">
                                @if ($table->status === 'inactive')
                                    <p class="text-sm text-gray-400 font-medium">{{ __('messages.inactive') }}</p>
                                @elseif($table->current_order)
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        <p
                                            class="text-xs font-black text-red-600 mb-2 flex items-center gap-1 uppercase tracking-widest leading-none">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-ping"></span>
                                            {{ __('messages.occupied') }}
                                        </p>
                                        <div
                                            class="flex justify-between font-black text-base text-gray-900 dark:text-white">
                                            <span>${{ number_format($table->current_order->total_price, 2) }}</span>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">
                                            {{ __('messages.items_ordered_count', ['count' => count($table->current_order->items ?? [])]) }}
                                        </p>
                                    </div>
                                @else
                                    <p
                                        class="text-sm text-blue-600 font-black flex items-center gap-1 uppercase tracking-tighter">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        {{ __('messages.available') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            setInterval(function() {
                if (!window.location.pathname.includes('/tables') || (window.Alpine && Alpine.$data(document
                        .querySelector('[x-data]'))?.selectedTable)) {
                    return;
                }
                fetch(window.location.href)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const currentGrid = document.querySelector('.tables-grid');
                        const newGrid = doc.querySelector('.tables-grid');
                        if (currentGrid && newGrid && currentGrid.innerHTML !== newGrid.innerHTML) {
                            currentGrid.innerHTML = newGrid.innerHTML;
                        }
                    })
                    .catch(err => console.error('Error refreshing:', err));
            }, 10000);
        </script>
    @endpush
</x-app-layout>
