<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Products</h2>
        <a href="{{ route('products.create') }}" class="bg-black text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800">
            + New Product
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-100 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 font-semibold">
                    <th class="px-6 py-4">Image</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750">
                    <td class="px-6 py-4">
                        @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100 dark:bg-gray-700">
                        @else
                        <div class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-medium dark:text-gray-200">
                        {{ $product->name }}
                        <div class="text-xs text-gray-400 font-normal truncate w-48">{{ $product->description }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm dark:text-gray-300">{{ $product->category->name }}</td>
                    <td class="px-6 py-4 font-bold dark:text-gray-200">${{ $product->price }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('products.toggle', $product) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="group flex items-center gap-1.5 focus:outline-none">
                                @if($product->is_available)
                                <div class="relative w-9 h-5 bg-green-500 rounded-full transition-colors group-hover:bg-green-600">
                                    <div class="absolute right-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm animate-in slide-in-from-left-4 duration-200"></div>
                                </div>
                                <span class="text-[10px] font-bold text-green-600 uppercase tracking-tighter">In Stock</span>
                                @else
                                <div class="relative w-9 h-5 bg-gray-300 dark:bg-gray-600 rounded-full transition-colors group-hover:bg-gray-400 dark:group-hover:bg-gray-500">
                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm"></div>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Sold Out</span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
