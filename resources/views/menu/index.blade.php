<x-guest-layout>
    <div x-data="menuApp('{{ $table ?? 1 }}')">
        <!-- Header -->
        <header class="sticky top-0 z-40 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-100">
            <!-- Language Switcher Bar -->
            <div class="bg-gray-50 border-b border-gray-100 px-4 py-1.5 flex justify-end gap-3 max-w-md mx-auto">
                <a href="{{ route('lang.switch', 'km') }}"
                    class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() === 'km' ? 'text-black' : 'text-gray-400 hover:text-black' }}">KH</a>
                <div class="h-3 w-[1px] bg-gray-200 mt-0.5"></div>
                <a href="{{ route('lang.switch', 'en') }}"
                    class="text-[10px] font-black uppercase tracking-widest {{ app()->getLocale() === 'en' ? 'text-black' : 'text-gray-400 hover:text-black' }}">EN</a>
            </div>

            <div class="max-w-md mx-auto px-4 py-3 flex items-center justify-between gap-4">
                <!-- Logo on Left -->
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/Gemini_Generated_Image_eb56d7eb56d7eb56.png') }}" 
                         alt="Logo" 
                         class="h-10 w-10 object-contain">
                </div>

                <!-- Title in Center -->
                <div class="text-center flex-1">
                    <h1 class="font-bold text-base text-gray-800 leading-none">{{ __('messages.menu') }}</h1>
                    <p class="text-[10px] text-gray-500 mt-0.5">{{ __('messages.table') }} <span x-text="table"></span>
                    </p>
                </div>

                <!-- Call Waiter Icon on Right -->
                <div class="flex-shrink-0 relative">
                    <button @click="callWaiter()"
                        class="p-2 rounded-full flex items-center justify-center hover:bg-gray-100 active:scale-95 transition-all relative"
                        :class="waiterCalled ? 'bg-primary text-white' : 'bg-gray-100 text-gray-800'">
                        <svg class="w-5 h-5" :class="waiterCalled ? 'animate-bounce' : ''" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <!-- Notification Dot -->
                        <span x-show="waiterCalled" 
                              class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
                    </button>
                </div>
            </div>

            <!-- Categories Swiper -->
            <div class="max-w-md mx-auto px-4 py-3">
                <div class="swiper categorySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($categories as $category)
                            <div class="swiper-slide !w-auto">
                                <a href="#category-{{ $category->id }}"
                                    class="block px-5 py-2.5 rounded-full text-sm font-medium transition-all duration-200 shadow-sm whitespace-nowrap"
                                    :class="activeCategory === {{ $category->id }} ? 'bg-primary text-white shadow-md' :
                                        'bg-gray-100 text-gray-700 hover:bg-gray-200 active:scale-95'"
                                    @click="activeCategory = {{ $category->id }}">
                                    {{ $category->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Search Bar Below Categories -->
            <div class="max-w-md mx-auto px-4 pb-3">
                <div class="relative">
                    <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" x-model="search" placeholder="{{ __('messages.search') }}..."
                        class="w-full bg-gray-100 rounded-full pl-10 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary/50 transition-all">
                    <button x-show="search.length > 0" @click="search = ''"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Order Status Tracking Banner -->
            <template x-if="lastOrderId && orderStatus !== 'paid'">
                <div class="max-w-md mx-auto px-4 pb-3">
                    <div
                        class="bg-primary text-white p-4 rounded-2xl shadow-xl flex items-center justify-between overflow-hidden relative group">
                        <!-- Animated background accent -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-primary/20 to-transparent opacity-50 group-hover:opacity-75 transition-opacity">
                        </div>

                        <div class="flex items-center gap-4 relative z-10">
                            <div class="relative">
                                <template x-if="orderStatus === 'pending'">
                                    <div class="w-3 h-3 rounded-full bg-yellow-400 animate-ping"></div>
                                </template>
                                <template x-if="orderStatus === 'completed'">
                                    <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                                </template>
                                <div class="absolute inset-0 w-3 h-3 rounded-full"
                                    :class="orderStatus === 'pending' ? 'bg-yellow-400' : (orderStatus === 'completed' ?
                                        'bg-green-500' : 'bg-red-500')">
                                </div>
                            </div>

                            <div>
                                {{-- <h4 class="text-xs font-black uppercase tracking-widest opacity-60 leading-none mb-1">
                                    {{ __('messages.live_update') }}</h4> --}}
                                <p class="font-bold text-sm tracking-tight" x-text="getStatusMessage()"></p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 relative z-10">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <span class="text-[9px] font-black uppercase tracking-tighter opacity-50">Live</span>
                        </div>
                    </div>
                </div>
            </template>
        </header>

        <style>
            .categorySwiper {
                overflow: visible !important;
            }
        </style>

        <!-- Menu Items -->
        <main class="max-w-md mx-auto pb-24 px-4 pt-4 space-y-8">
            @foreach ($categories as $category)
                <section id="category-{{ $category->id }}" class="scroll-mt-32">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">{{ $category->name }}</h2>
                    <div class="space-y-4">
                        @forelse($category->products as $product)
                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 flex gap-4"
                                x-show="matchesSearch('{{ strtolower($product->name) }}')">
                                <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                    @if ($product->image)
                                        <img src="{{ Storage::url($product->image) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-semibold text-gray-900 leading-tight">{{ $product->name }}
                                            </h3>
                                            <div class="flex items-center gap-1">
                                                @if ($product->is_spicy)
                                                    <span class="text-[10px]"
                                                        title="{{ __('messages.spicy') }}">🌶️</span>
                                                @endif
                                                @if ($product->is_vegetarian)
                                                    <span class="text-[10px]"
                                                        title="{{ __('messages.vegetarian') }}">🥬</span>
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between mt-2">
                                        <span class="font-bold text-primary">${{ $product->price }}</span>

                                        <div class="flex items-center gap-2">
                                            <template x-if="getItemQty({{ $product->id }}) > 0">
                                                <div class="flex items-center gap-2 bg-gray-100 rounded-full px-2 py-1">
                                                    <button @click="removeFromCart({{ $product->id }})"
                                                        class="w-6 h-6 flex items-center justify-center text-gray-500 hover:text-black font-bold">-</button>
                                                    <span class="text-sm font-bold w-4 text-center"
                                                        x-text="getItemQty({{ $product->id }})"></span>
                                                    <button @click="addToCart({{ $product->toJson() }})"
                                                        class="w-6 h-6 flex items-center justify-center text-gray-800 hover:text-black font-bold">+</button>
                                                </div>
                                            </template>

                                            <template x-if="getItemQty({{ $product->id }}) === 0">
                                                <button @click="addToCart({{ $product->toJson() }})"
                                                    class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center hover:scale-105 transition-transform active:scale-95">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-400 text-sm italic">No items available.</div>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </main>

        <!-- Cart Floating Button -->
        <div x-show="cart.length > 0" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="transform translate-y-20 opacity-0"
            x-transition:enter-end="transform translate-y-0 opacity-100"
            class="fixed bottom-6 left-0 right-0 px-4 z-50 flex justify-center pointer-events-none">
            <button @click="cartOpen = true"
                class="pointer-events-auto bg-primary text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-4 hover:scale-105 transition-transform active:scale-95 relative">
                <!-- Item Count Badge -->
                <div class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold shadow-lg animate-pulse"
                    x-text="cartCount"></div>

                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <div>
                    <div class="text-xs opacity-75">{{ __('messages.view_order') }}</div>
                    <div class="font-bold text-lg" x-text="'$' + cartTotal"></div>
                </div>
            </button>
        </div>

        <!-- Add to Cart Success Toast -->
        <div x-show="showAddedToast"
            class="fixed top-20 left-1/2 -translate-x-1/2 z-50 bg-primary text-white px-6 py-3 rounded-full shadow-xl flex items-center gap-2"
            style="display: none;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="font-medium">{{ __('messages.added_to_cart') }}</span>
        </div>

        <!-- Cart Modal -->
        <div x-show="cartOpen" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center"
            style="display: none;">

            <!-- Backdrop -->
            <div x-show="cartOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" @click="cartOpen = false"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

            <!-- Panel -->
            <div x-show="cartOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="transform translate-y-full" x-transition:enter-end="transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transform translate-y-0" x-transition:leave-end="transform translate-y-full"
                class="relative bg-white w-full max-w-md rounded-t-2xl sm:rounded-2xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">

                <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gray-50">
                    <div class="flex items-center gap-2">
                        <button x-show="checkoutStep === 2" @click="checkoutStep = 1"
                            class="p-1 hover:bg-gray-200 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <h2 class="text-lg font-bold"
                            x-text="checkoutStep === 1 ? '{{ __('messages.view_order') }}' : '{{ __('messages.payment_method') }}'">
                        </h2>
                    </div>
                    <button @click="cartOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-4">
                    <!-- Step 1: Items & Instructions -->
                    <div x-show="checkoutStep === 1" class="space-y-4">
                        <template x-for="(item, index) in cart" :key="index">
                            <div
                                class="flex justify-between items-center bg-white border border-gray-100 p-3 rounded-xl shadow-sm">
                                <div>
                                    <h4 class="font-medium text-gray-900" x-text="item.name"></h4>
                                    <p class="text-sm text-primary font-bold" x-text="'$' + item.price"></p>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 p-1 rounded-full">
                                    <button @click="changeQty(index, -1)"
                                        class="w-8 h-8 rounded-full bg-white shadow-sm flex items-center justify-center hover:bg-gray-100 text-gray-500 transition-colors">-</button>
                                    <span x-text="item.qty" class="font-bold w-4 text-center text-sm"></span>
                                    <button @click="changeQty(index, 1)"
                                        class="w-8 h-8 rounded-full bg-primary text-white shadow-md flex items-center justify-center hover:opacity-90 transition-opacity font-bold">+</button>
                                </div>
                            </div>
                        </template>
                        <div x-show="cart.length === 0" class="text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">{{ __('messages.cart_is_empty') }}</p>
                        </div>

                        <div class="mt-6">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">{{ __('messages.special_instructions') }}</label>
                            <textarea x-model="specialInstructions" rows="3" placeholder="{{ __('messages.instructions_placeholder') }}"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none resize-none transition-all"></textarea>
                        </div>
                    </div>

                    <!-- Step 2: Service Type & Payment Method -->
                    <div x-show="checkoutStep === 2" class="space-y-6 py-2">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">{{ __('messages.order_type') }}</label>
                            <div class="relative group">
                                <select x-model="orderType"
                                    class="w-full pl-4 pr-10 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none appearance-none cursor-pointer transition-all hover:bg-gray-100">
                                    <option value="dine_in">{{ __('messages.dine_in') }}</option>
                                    <option value="delivery">{{ __('messages.delivery') }}</option>
                                </select>
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300 group-focus-within:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">{{ __('messages.payment_method') }}</label>
                            <div class="relative group">
                                <select x-model="paymentMethod"
                                    class="w-full pl-4 pr-10 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-primary/20 focus:border-primary focus:outline-none appearance-none cursor-pointer transition-all hover:bg-gray-100">
                                    <option value="cash">{{ __('messages.cash') }}</option>
                                    <option value="pay_now">{{ __('messages.pay_now') }}</option>
                                    <option value="aba_payway">ABA PayWay (Online)</option>
                                </select>
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-300 group-focus-within:text-primary transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100">
                            <div class="flex justify-between items-center mb-2">
                                <span
                                    class="text-xs text-gray-400 font-bold uppercase tracking-wider">{{ __('messages.total') }}</span>
                                <span class="text-2xl font-black text-gray-900" x-text="'$' + cartTotal"></span>
                            </div>
                            <div
                                class="flex items-center gap-2 text-[10px] text-primary font-bold uppercase tracking-tighter">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                    </path>
                                </svg>
                                Secure checkout
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 border-t border-gray-100">
                    <!-- Navigation Buttons -->
                    <template x-if="checkoutStep === 1">
                        <button @click="checkoutStep = 2" :disabled="cart.length === 0"
                            class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:opacity-90 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            {{ __('messages.next') }}
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </template>

                    <template x-if="checkoutStep === 2">
                        <button @click="placeOrder" :disabled="loading"
                            class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary/30 hover:opacity-90 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <span x-show="!loading">{{ __('messages.place_order') }}</span>
                            <span x-show="loading" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                ...
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="orderSuccess" style="display: none;"
            class="fixed inset-0 z-[60] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center">
                <div
                    class="w-16 h-16 bg-blue-50 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ __('messages.order_placed') }}</h3>
                <p class="text-gray-500 mb-6">{{ __('messages.kitchen_preparing') }}</p>
                <button @click="orderSuccess = false; cartOpen = false;"
                    class="bg-primary text-white px-6 py-2 rounded-lg font-medium">{{ __('messages.okay') }}</button>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function menuApp(tableInit) {
                return {
                    table: tableInit || 1,
                    cart: JSON.parse(localStorage.getItem('qr_menu_cart')) || [],
                    cartOpen: false,
                    search: '',
                    activeCategory: null,
                    loading: false,
                    orderSuccess: false,
                    showAddedToast: false,
                    specialInstructions: '',
                    lastOrderId: localStorage.getItem('last_order_id'),
                    orderStatus: 'pending',
                    waiterCalled: localStorage.getItem('waiter_called') === 'true',
                    orderType: 'dine_in',
                    paymentMethod: 'cash',
                    checkoutStep: 1,

                    init() {
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.has('table')) {
                            this.table = urlParams.get('table');
                        }
                        this.$watch('cart', value => {
                            localStorage.setItem('qr_menu_cart', JSON.stringify(value));
                        });

                        if (this.lastOrderId) {
                            this.checkOrderStatus();
                            setInterval(() => this.checkOrderStatus(), 10000);
                        }
                    },

                    checkOrderStatus() {
                        if (!this.lastOrderId) return;
                        fetch(`/order/${this.lastOrderId}/status`)
                            .then(res => res.json())
                            .then(data => {
                                this.orderStatus = data.status;
                                if (data.status === 'paid') {
                                    localStorage.removeItem('last_order_id');
                                    this.lastOrderId = null;
                                }
                            })
                            .catch(err => console.error('Error checking status', err));
                    },

                    getStatusMessage() {
                        const messages = {
                            'pending': '{{ __('messages.preparing') }}',
                            'completed': '{{ __('messages.ready') }}',
                            'cancelled': '{{ __('messages.cancelled') }}',
                            'paid': '{{ __('messages.thank_you') }}'
                        };
                        return messages[this.orderStatus] || '🔍 Checking status...';
                    },

                    callWaiter() {
                        if (this.waiterCalled) return;

                        fetch('{{ route('order.call-waiter') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    table_number: this.table
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.waiterCalled = true;
                                    localStorage.setItem('waiter_called', 'true');

                                    // Visual feedback toast
                                    const toast = document.createElement('div');
                                    toast.className =
                                        'fixed bottom-24 left-1/2 -translate-x-1/2 bg-black text-white px-6 py-3 rounded-2xl shadow-xl z-[100] animate-bounce text-sm font-bold';
                                    toast.innerText = '{{ __('messages.waiter_on_way') }}';
                                    document.body.appendChild(toast);
                                    setTimeout(() => toast.remove(), 3000);
                                }
                            });
                    },

                    matchesSearch(name) {
                        if (this.search === '') return true;
                        return name.includes(this.search.toLowerCase());
                    },

                    getItemQty(productId) {
                        const item = this.cart.find(i => i.id === productId);
                        return item ? item.qty : 0;
                    },

                    removeFromCart(productId) {
                        const index = this.cart.findIndex(i => i.id === productId);
                        if (index !== -1) {
                            this.changeQty(index, -1);
                        }
                    },

                    addToCart(product) {
                        const existing = this.cart.find(item => item.id === product.id);
                        if (existing) {
                            existing.qty++;
                        } else {
                            // Ensure price is a number
                            product.price = parseFloat(product.price);
                            this.cart.push({
                                ...product,
                                qty: 1
                            });
                        }

                        // Show success toast
                        this.showAddedToast = true;
                        setTimeout(() => {
                            this.showAddedToast = false;
                        }, 2000);
                    },

                    changeQty(index, delta) {
                        this.cart[index].qty += delta;
                        if (this.cart[index].qty <= 0) {
                            this.cart.splice(index, 1);
                        }
                    },

                    get cartCount() {
                        return this.cart.reduce((acc, item) => acc + item.qty, 0);
                    },

                    get cartTotal() {
                        return this.cart.reduce((acc, item) => acc + (item.price * item.qty), 0).toFixed(2);
                    },

                    placeOrder() {
                        this.loading = true;
                        fetch('{{ route('order.store') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                        'content')
                                },
                                body: JSON.stringify({
                                    table_number: this.table,
                                    items: this.cart,
                                    total_price: this.cartTotal,
                                    special_instructions: this.specialInstructions,
                                    order_type: this.orderType,
                                    payment_method: this.paymentMethod
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.loading = false;
                                if (data.success) {
                                    if (data.redirect_url) {
                                        window.location.href = data.redirect_url;
                                        return;
                                    }
                                    this.cart = []; // clear cart
                                    this.specialInstructions = ''; // clear instructions
                                    this.checkoutStep = 1; // reset step
                                    localStorage.removeItem('qr_menu_cart');
                                    this.orderSuccess = true;

                                    // Track order status
                                    this.lastOrderId = data.order_id;
                                    localStorage.setItem('last_order_id', data.order_id);
                                    this.orderStatus = 'pending';

                                    // Start polling if not already started
                                    if (!window.statusInterval) {
                                        window.statusInterval = setInterval(() => this.checkOrderStatus(), 10000);
                                    }
                                    this.checkOrderStatus();
                                } else {
                                    alert('{{ __('messages.error_something_wrong') }}');
                                }
                            })
                            .catch(err => {
                                this.loading = false;
                                alert('{{ __('messages.error_placing_order') }}');
                                console.error(err);
                            });
                    }
                }
            }

            // Initialize Swiper
            document.addEventListener('DOMContentLoaded', function() {
                new Swiper('.categorySwiper', {
                    slidesPerView: 'auto',
                    spaceBetween: 8,
                    freeMode: true,
                    grabCursor: true,
                });
            });
        </script>
    @endpush
</x-guest-layout>
