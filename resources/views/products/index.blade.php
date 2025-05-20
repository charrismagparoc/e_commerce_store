@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Filter Panel -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 mb-8">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                <select name="category" id="category" class="form-select w-full">
                    <option value="">All Categories</option>
                    @foreach ($categories as $parentCategory)
                        <optgroup label="{{ $parentCategory->name }}">
                            @foreach ($parentCategory->children as $childCategory)
                                <option value="{{ $childCategory->id }}" {{ request('category') == $childCategory->id ? 'selected' : '' }}>
                                    {{ $childCategory->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       class="form-input w-full" placeholder="Search products...">
            </div>

            <!-- Sort -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" id="sort" class="form-select w-full">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>

            <!-- Filter Button -->
            <div class="flex items-end">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1 px-2 rounded-md">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Products</h1>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($products as $product)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition duration-300 overflow-hidden">
                <div class="h-48 bg-gray-100 relative">
                    @if ($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="flex items-center justify-center h-full text-gray-400">
                            No Image
                        </div>
                    @endif
                </div>

                <div class="p-4">
                    <h2 class="text-lg font-semibold text-gray-900 truncate">{{ $product->name }}</h2>
                    <p class="text-indigo-600 font-bold mt-1">₱{{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $product->description }}</p>

                    <div class="mt-4 flex justify-between items-center text-sm">
                        <span class="{{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock_quantity > 0 ? 'In Stock: ' . $product->stock_quantity : 'Out of Stock' }}
                        </span>
                        <div class="flex gap-3">
                            <a href="{{ route('products.show', $product) }}" class="text-indigo-600 hover:text-indigo-800">
                                View
                            </a>
                            @if (auth()->check() && auth()->user()->is_admin)
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 hover:text-yellow-800">
                                    Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-10 text-center text-gray-500 text-lg">
                No products found.
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-10">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection
