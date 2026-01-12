<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-gray-500 hover:text-black dark:text-gray-400 dark:hover:text-white">← Back to Users</a>
        <h2 class="text-2xl font-bold mt-2 dark:text-white">Edit User</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-w-2xl">
        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                <select name="role" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="kitchen" {{ $user->role === 'kitchen' ? 'selected' : '' }}>Kitchen</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
            </div>

            <button type="submit" class="bg-black dark:bg-white dark:text-black text-white px-6 py-2 rounded-lg font-medium hover:opacity-90">
                Update User
            </button>
        </form>
    </div>
</x-app-layout>
