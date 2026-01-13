<x-app-layout>
    <div
        class="px-8 py-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Role Management</h2>
            <p class="text-gray-500 dark:text-gray-400">Define user roles and their respective permissions</p>
        </div>
        <button
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/30 flex items-center gap-2 transition-all">
            + Create New Role
        </button>
    </div>

    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($roles as $role)
                <div
                    class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all group">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div
                                class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="bg-gray-100 dark:bg-gray-700 text-gray-400 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-lg">ID:
                                #{{ $role->id }}</span>
                        </div>

                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tight">
                            {{ $role->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $role->permissions_count }} Permissions assigned</p>

                        <div class="mt-6 flex gap-2">
                            <a href="{{ route('roles.permissions', $role) }}"
                                class="flex-1 bg-gray-50 dark:bg-gray-900 hover:bg-blue-600 hover:text-white border border-gray-100 dark:border-gray-700 py-3 rounded-2xl text-sm font-bold text-center transition-all flex items-center justify-center gap-2 group/btn">
                                <svg class="w-4 h-4 text-blue-600 group-hover/btn:text-white" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                Permissions
                            </a>
                            <button
                                class="p-3 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 rounded-2xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
