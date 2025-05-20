@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex text-sm">
            <li class="mr-2">
                <a href="{{ route('shop.index') }}" class="text-indigo-600 hover:text-indigo-800">Home</a>
            </li>
            <li class="mr-2">/</li>
            <li class="mr-2">
                <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800">Products</a>
            </li>
            <li class="mr-2">/</li>
            <li class="text-gray-700">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Product Image -->
            <div class="md:w-1/2 p-6">
                @if ($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                         class="w-full h-auto object-cover rounded">
                @else
                    <div class="flex items-center justify-center h-96 bg-gray-200 rounded text-gray-500">
                        <span>No Image Available</span>
                    </div>
                @endif
            </div>
            
            <!-- Product Info -->
            <div class="md:w-1/2 p-6">
                <div class="mb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center mb-4">
                        <span class="mr-2 text-sm text-gray-600">Category:</span>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded">
                            {{ $product->category->name }}
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-indigo-600">₱{{ number_format($product->price, 2) }}</p>
                </div>
                
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">Description</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>
                
                <div class="mb-6">
                    <div class="flex items-center">
                        <span class="mr-2 text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                            <span class="font-medium">Availability:</span> 
                            {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                        </span>
                        @if ($product->stock_quantity > 0)
                            <span class="text-sm text-gray-600">({{ $product->stock_quantity }} available)</span>
                        @endif
                    </div>
                </div>
                
                <!-- Show Add to Cart only for non-admin users -->
                @if ($product->stock_quantity > 0)
                    @if (auth()->check() && !auth()->user()->is_admin)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="flex items-center space-x-4 mb-4">
                                <label for="quantity" class="text-sm font-medium text-gray-700">Quantity:</label>
                                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}"
                                       class="w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </div>
                            
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition-colors duration-150">
                                Add to Cart
                            </button>
                        </form>
                    @elseif (!auth()->check())
                        <div class="mb-6">
                            <a href="{{ route('auth.options') }}" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded transition-colors duration-150 text-center block">
                                Register or Log In to Add to Cart
                            </a>
                        </div>
                    @endif
                @else
                    <div class="mb-6">
                        <button disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded cursor-not-allowed">
                            Out of Stock
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if ($relatedProducts->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Related Products</h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="bg-white rounded-lg shadow overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                    <div class="h-48 bg-gray-200 relative overflow-hidden">
                        @if ($relatedProduct->image_path)
                            <img src="{{ asset('storage/' . $relatedProduct->image_path) }}" alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full bg-gray-200 text-gray-500">
                                <span>No Image</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-lg font-bold mb-2 text-gray-800">{{ $relatedProduct->name }}</h3>
                        <p class="text-indigo-600 font-semibold">₱{{ number_format($relatedProduct->price, 2) }}</p>
                        
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm {{ $relatedProduct->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $relatedProduct->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                            </span>
                            
                            <a href="{{ route('products.show', $relatedProduct) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection