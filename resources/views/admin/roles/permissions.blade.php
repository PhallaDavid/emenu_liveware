<x-app-layout>
    <div class="h-full flex flex-col" x-data="{
        selectedCount: {{ count($rolePermissions) }},
        init() {
            // Initial count is already set
        }
    }">
        <!-- Header -->
        <div
            class="px-8 py-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center sticky top-0 z-10 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="{{ route('roles.index') }}"
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                    <svg class="w-6 h-6 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Permissions for <span class="text-blue-600">{{ $role->name }}</span>
                    </h2>
                    <p class="text-sm text-gray-500">Manage what this role is allowed to do</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div
                    class="bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600">
                    <span
                        class="text-xs font-bold text-gray-400 uppercase tracking-wider block leading-none mb-1">Active</span>
                    <span class="text-lg font-black text-gray-900 dark:text-white tabular-nums"><span
                            x-text="selectedCount"></span> / {{ $permissions->flatten()->count() }}</span>
                </div>
                <button type="submit" form="permission-form"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 transition-all flex items-center gap-2">
                    Save Permissions
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-8">
            <div class="max-w-4xl mx-auto">
                <form id="permission-form" action="{{ route('roles.permissions.update', $role) }}" method="POST">
                    @csrf
                    <div
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden">

                        @foreach ($permissions as $group => $groupPermissions)
                            <div class="p-0">
                                <!-- Group Header -->
                                <div class="bg-gray-50/50 dark:bg-gray-900/30 px-6 py-4 flex items-center gap-3">
                                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                                    <h3
                                        class="text-sm font-black text-gray-900 dark:text-white uppercase tracking-widest">
                                        {{ $group ?: 'General Access' }}
                                    </h3>
                                </div>

                                <!-- Permission List -->
                                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($groupPermissions as $permission)
                                        <div
                                            class="px-6 py-5 flex items-center justify-between hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors group">
                                            <div class="flex items-center gap-5">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-gray-900 flex items-center justify-center text-gray-400 group-hover:text-blue-600 transition-colors">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-base font-bold text-gray-900 dark:text-white">
                                                        {{ $permission->name }}</p>
                                                    <p class="text-xs font-mono text-gray-400">{{ $permission->slug }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Toggle Switch -->
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" name="permissions[]"
                                                    value="{{ $permission->id }}" class="sr-only peer"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                    @change="$el.checked ? selectedCount++ : selectedCount--">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
