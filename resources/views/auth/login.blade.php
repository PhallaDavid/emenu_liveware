<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full border border-gray-100">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">Admin Login</h1>
                <p class="text-gray-500">Access the dashboard</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-black transition-all">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required 
                           class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-black transition-all">
                </div>

                <button type="submit" class="w-full bg-black text-white font-bold py-3 rounded-xl shadow-lg hover:scale-[1.02] transition-transform">
                    Sign In
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
