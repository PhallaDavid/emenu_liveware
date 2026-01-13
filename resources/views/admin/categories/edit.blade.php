<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500 transition-colors">← Back to Categories</a>
        <h2 class="text-2xl font-bold mt-2 text-gray-900 dark:text-white">Edit Category</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-2xl">
        <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Category Name</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:text-white focus:outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:text-white focus:outline-none transition-all">
            </div>

            <div>
                 <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Cover Image</label>
                 @if($category->image)
                    <div class="mb-2">
                        <img src="{{ Storage::url($category->image) }}" class="w-20 h-20 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                    </div>
                 @endif
                 <input type="file" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-blue-400 transition-all cursor-pointer">
            </div>

            <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-600/30 transition-all transform active:scale-95">
                Update Category
            </button>
        </form>
    </div>
</x-app-layout>
