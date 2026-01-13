<x-guest-layout>
    <div class="min-h-screen flex flex-col lg:flex-row bg-white">
        <!-- Left side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-24 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-primary/5 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary/5 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>

            <div class="max-w-md w-full relative z-10">
                <!-- Logo/Brand -->
                <div class="mb-12 text-center lg:text-left">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary/10 text-primary mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-3">Welcome back!</h1>
                    <p class="text-gray-500 text-lg">Please enter your details to sign in.</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-gray-700 ml-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="block w-full pl-11 pr-4 py-4 bg-gray-50 border-0 ring-1 ring-gray-200 focus:ring-2 focus:ring-primary rounded-2xl text-gray-900 transition-all placeholder:text-gray-400"
                                   placeholder="name@company.com">
                        </div>
                        @error('email')
                            <p class="text-sm text-red-500 mt-2 ml-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between px-1">
                            <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                            <a href="https://t.me/youradminusername" target="_blank" class="text-sm font-semibold text-primary hover:text-primary/80 transition-colors">
                                Forgot password? Contact Admin
                            </a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" type="password" name="password" required
                                   class="block w-full pl-11 pr-4 py-4 bg-gray-50 border-0 ring-1 ring-gray-200 focus:ring-2 focus:ring-primary rounded-2xl text-gray-900 transition-all placeholder:text-gray-400"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-sm text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex items-center justify-center space-x-2 py-4 px-6 bg-primary text-white font-bold rounded-2xl shadow-lg shadow-primary/25 hover:bg-primary/90 hover:scale-[1.01] active:scale-[0.98] transition-all">
                        <span>Sign into Account</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Extra Info -->
                    <p class="text-center text-gray-400 text-sm mt-8">
                        &copy; {{ date('Y') }} JuperCoding. All rights reserved.
                    </p>
                </form>
            </div>
        </div>

        <!-- Right side: Visual Content -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden">
            <img src="{{ asset('images/login-bg.png') }}" alt="Restaurant Experience" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-black/60 backdrop-blur-[1px]"></div>
            
            <!-- Floating Content on Image -->
            <div class="absolute inset-0 flex flex-col items-center justify-end p-20 text-white">
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 max-w-lg">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center text-[10px] font-bold">JD</div>
                            <div class="w-8 h-8 rounded-full bg-green-500 border-2 border-white flex items-center justify-center text-[10px] font-bold">AS</div>
                            <div class="w-8 h-8 rounded-full bg-purple-500 border-2 border-white flex items-center justify-center text-[10px] font-bold">MP</div>
                        </div>
                        <span class="text-sm font-medium">Join 2,000+ restaurant owners</span>
                    </div>
                    <h2 class="text-3xl font-bold mb-4 italic">"The most intuitive E-Menu platform we've ever used. Digitizing our menu was a breeze!"</h2>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-bold text-lg">Marco Rossi</p>
                            <p class="text-white/70 text-sm">Owner, Bella Italia</p>
                        </div>
                        <div class="flex space-x-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
