<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-black">← Back to Products</a>
        <h2 class="text-2xl font-bold mt-2">Create Product</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 max-w-2xl">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price ($)</label>
                    <input type="number" step="0.01" name="price" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                <select name="category_id" required class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none">
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:bg-gray-700 dark:text-white dark:focus:ring-white focus:outline-none"></textarea>
            </div>

            <div>
                 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product Image</label>
                 <input type="file" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-200 hover:file:bg-gray-200 dark:hover:file:bg-gray-600">
            </div>

            <div class="space-y-3">
                <div class="flex items-center">
                    <input type="checkbox" name="is_available" id="is_available" value="1" checked class="w-5 h-5 text-black border-gray-300 dark:border-gray-600 rounded focus:ring-black bg-white dark:bg-gray-700">
                    <label for="is_available" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Available for ordering</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_spicy" id="is_spicy" value="1" class="w-5 h-5 text-red-500 border-gray-300 dark:border-gray-600 rounded focus:ring-red-500 bg-white dark:bg-gray-700">
                    <label for="is_spicy" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">🌶️ Spicy</label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_vegetarian" id="is_vegetarian" value="1" class="w-5 h-5 text-green-500 border-gray-300 dark:border-gray-600 rounded focus:ring-green-500 bg-white dark:bg-gray-700">
                    <label for="is_vegetarian" class="ml-2 text-sm text-gray-700 dark:text-gray-300 font-medium">🥬 Vegetarian</label>
                </div>
            </div>

            <button type="submit" class="bg-black dark:bg-white dark:text-black text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-800 dark:hover:bg-gray-200">
                Create Product
            </button>
        </form>
    </div>
</x-app-layout>
