<x-app-layout>
    <div class="h-screen flex flex-col" x-data="{ 
        selectedTable: null, 
        searchQuery: '',
        openSidebar(table) {
            this.selectedTable = table;
        },
        createModalOpen: false,
        editModalOpen: false,
        deleteConfirmOpen: false,
        editingTable: { name: '' },
    }">
        
        <!-- Create Table Modal -->
        <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all" @click.away="createModalOpen = false">
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('messages.create_table') }}</h3>
                <form action="{{ route('tables.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.table_name') }}</label>
                        <input type="text" name="name" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-600" required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="createModalOpen = false" class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Table Modal -->
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-md transform transition-all" @click.away="editModalOpen = false">
                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">{{ __('messages.edit_table') }}</h3>
                <form :action="`/tables/${selectedTable?.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('messages.table_name') }}</label>
                        <input type="text" name="name" x-model="editingTable.name" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-600" required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-gray-500 hover:text-gray-700 font-medium">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/30">{{ __('messages.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Header -->
        <div class="px-8 py-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">{{ __('messages.table_management') }}</h2>
                <p class="text-gray-500 dark:text-gray-400">{{ __('messages.table_management_desc') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-600"></span> {{ __('messages.available') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-red-500"></span> {{ __('messages.occupied') }}</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-gray-300"></span> {{ __('messages.inactive') }}</span>
                </div>
                <!-- Create Table Button -->
                <button @click="createModalOpen = true" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    {{ __('messages.create_table') }}
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-hidden flex">
            <!-- Tables Grid -->
            <div class="flex-1 p-8 overflow-y-auto bg-gray-50 dark:bg-gray-900">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach($tables as $table)
                    <div @click="openSidebar({{ $table->toJson() }})" 
                         class="cursor-pointer group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border-2 transition-all duration-200 hover:shadow-md
                         {{ $table->status === 'inactive' ? 'border-gray-200 dark:border-gray-700 opacity-75' : 
                            ($table->current_order ? 'border-red-500 dark:border-red-500 bg-red-50 dark:bg-red-900/10' : 'border-blue-600 dark:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/10') }}">
                        
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white group-hover:scale-105 transition-transform">{{ $table->name }}</h3>
                            <span class="w-4 h-4 rounded-full {{ $table->status === 'inactive' ? 'bg-gray-300' : ($table->current_order ? 'bg-red-500 animate-pulse' : 'bg-blue-600') }}"></span>
                        </div>

                        <div class="space-y-2">
                            @if($table->status === 'inactive')
                                <p class="text-sm text-gray-400 font-medium">{{ __('messages.inactive') }}</p>
                            @elseif($table->current_order)
                                <div class="text-sm text-gray-600 dark:text-gray-300">
                                    <p class="font-bold flex justify-between">
                                        <span>{{ __('messages.total') }}:</span>
                                        <span>${{ number_format($table->current_order->total_price, 2) }}</span>
                                    </p>
                                    <p class="text-xs mt-1 text-gray-500">{{ count($table->current_order->items ?? []) }} items</p>
                                    <span class="inline-block mt-2 text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded-full uppercase">{{ $table->current_order->status }}</span>
                                </div>
                            @else
                                <p class="text-sm text-blue-600 dark:text-blue-400 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    {{ __('messages.available') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar Drawer -->
            <div x-show="selectedTable" 
                 x-transition:enter="transition transform ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition transform ease-in duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-96 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 shadow-2xl overflow-y-auto flex flex-col relative z-30">
                
                <template x-if="selectedTable">
                    <div class="h-full flex flex-col">
                        <!-- Drawer Header -->
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="selectedTable.name"></h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-full" 
                                          :class="selectedTable.status === 'inactive' ? 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-400' : 
                                                  (selectedTable.current_order ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400')" 
                                          x-text="selectedTable.status === 'inactive' ? '{{ __('messages.inactive') }}' : (selectedTable.current_order ? '{{ __('messages.occupied') }}' : '{{ __('messages.available') }}')"></span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <button @click="editingTable = { ...selectedTable }; editModalOpen = true" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="{{ __('messages.edit_table') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button @click="if(confirm('{{ __('messages.delete_confirm') }}')) document.getElementById('delete-form-' + selectedTable.id).submit()" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="{{ __('messages.delete_table') }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                                <form :id="'delete-form-' + selectedTable.id" :action="`/tables/${selectedTable.id}`" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button @click="selectedTable = null" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-lg transition-colors ml-2">
                                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="p-6 grid grid-cols-2 gap-4">
                            <!-- Toggle Status Form -->
                            <form :action="`/tables/${selectedTable.id}/toggle-status`" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-3 px-4 rounded-xl font-bold text-sm border-2 border-gray-200 dark:border-gray-600 hover:border-blue-600 dark:hover:border-blue-600 transition-all text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-500">
                                    <span x-text="selectedTable.status === 'active' ? '{{ __('messages.set_inactive') }}' : '{{ __('messages.set_active') }}'"></span>
                                </button>
                            </form>
                            
                            <!-- Checkout Form -->
                                <button x-show="selectedTable.current_order" 
                                    @click="if(confirm('Are you sure you want to checkout this table?')) document.getElementById('checkout-form-' + selectedTable.id).submit()" 
                                    class="w-full py-3 px-4 rounded-xl font-bold text-sm bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-600/30 transition-all">
                                    {{ __('messages.checkout') }}
                                </button>
                                <form :id="'checkout-form-' + selectedTable.id" :action="`/tables/${selectedTable.id}/checkout`" method="POST" class="hidden">
                                    @csrf
                                </form>
                        </div>

                        <!-- Order Details -->
                        <div class="px-6 flex-1 overflow-y-auto">
                            <template x-if="selectedTable.current_order">
                                <div class="space-y-6">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-2xl">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold text-gray-900 dark:text-white uppercase text-sm">{{ __('messages.current_order') }}</h4>
                                            <span class="text-xs font-mono bg-gray-200 dark:bg-gray-600 px-2 py-1 rounded" x-text="'#' + selectedTable.current_order.id"></span>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <template x-for="item in selectedTable.current_order.items" :key="item.id">
                                                <div class="flex justify-between items-center text-sm">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-6 h-6 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-xs font-bold" x-text="item.quantity"></span>
                                                        <span class="text-gray-700 dark:text-gray-300" x-text="item.name"></span>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-white" x-text="'$' + (item.price * item.quantity).toFixed(2)"></span>
                                                </div>
                                            </template>
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600 flex justify-between items-center text-lg font-bold">
                                            <span class="text-gray-900 dark:text-white">{{ __('messages.total') }}</span>
                                            <span class="text-blue-600" x-text="'$' + parseFloat(selectedTable.current_order.total_price).toFixed(2)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="!selectedTable.current_order && selectedTable.status === 'active'">
                                <div class="text-center py-12 text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p>{{ __('messages.no_active_order') }}</p>
                                    <p class="text-sm">{{ __('messages.add_items_start') }}</p>
                                </div>
                            </template>
                        </div>

                        <!-- Add Items Section -->
                        <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900" x-data="{ searchItem: '', activeCategory: null }">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                {{ __('messages.add_items') }}
                            </h4>
                            
                            <!-- Search -->
                            <input type="text" x-model="searchItem" placeholder="{{ __('messages.search_products') }}" class="w-full mb-4 px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm focus:ring-2 focus:ring-blue-600 dark:focus:ring-white">

                            <!-- Categories & Products List -->
                            <div class="h-64 overflow-y-auto space-y-4 pr-2">
                                @foreach($categories as $category)
                                <div x-show="!searchItem || '{{ strtolower($category->name) }}'.includes(searchItem.toLowerCase()) || $el.querySelectorAll('.product-item').length > 0">
                                    <h5 class="text-xs font-bold uppercase text-gray-500 mb-2">{{ $category->name }}</h5>
                                    <div class="space-y-2">
                                        @foreach($category->products as $product)
                                        <div class="product-item bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-blue-600 dark:hover:border-blue-500 cursor-pointer group flex justify-between items-center transition-colors"
                                             x-show="!searchItem || '{{ strtolower($product->name) }}'.includes(searchItem.toLowerCase())"
                                             @click="document.getElementById('add-item-form-{{ $product->id }}').submit()">
                                            
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center text-xs">IMG</div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                                    <p class="text-xs text-gray-500">${{ $product->price }}</p>
                                                </div>
                                            </div>
                                            <div class="w-6 h-6 rounded-full bg-gray-100 group-hover:bg-blue-600 group-hover:text-white flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </div>

                                            <form :id="'add-item-form-{{ $product->id }}'" :action="`/tables/${selectedTable.id}/add-item`" method="POST" class="hidden">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </div>
</x-app-layout>
