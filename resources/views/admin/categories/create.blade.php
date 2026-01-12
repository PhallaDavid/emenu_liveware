<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-black">← Back to Categories</a>
        <h2 class="text-2xl font-bold mt-2">Create Category</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
        <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-black focus:outline-none">
            </div>

            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                 <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
            </div>

            <button type="submit" class="bg-black dark:bg-white dark:text-black text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-800 dark:hover:bg-gray-200">
                Create Category
            </button>
        </form>
    </div>
</x-app-layout>
