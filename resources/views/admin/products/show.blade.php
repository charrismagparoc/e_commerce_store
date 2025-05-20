@extends('layouts.admin')

@section('title', 'Product Details')

@section('header', 'Product Details')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Products
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold">Product #{{ $product->id }}: {{ $product->name }}</h3>
            <div class="flex space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                    Edit Product
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm" 
                            onclick="return confirm('Are you sure you want to delete this product?')">
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Product Image -->
                <div class="md:col-span-1">
                    <div class="bg-gray-100 rounded-lg p-4 flex items-center justify-center">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="max-w-full h-auto rounded">
                        @else
                            <div class="h-64 w-full bg-gray-200 flex items-center justify-center rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Product Details -->
                <div class="md:col-span-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-medium text-gray-600">Product Name</h4>
                            <p class="text-gray-900">{{ $product->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-600">Price</h4>
                            <p class="text-gray-900">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-600">Category</h4>
                            <p class="text-gray-900">{{ $product->category->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-600">Stock Quantity</h4>
                            <p class="text-gray-900 flex items-center">
                                <span class="px-2 mr-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->stock_quantity > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                                {{ $product->stock_quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-600">Created At</h4>
                            <p class="text-gray-900">{{ $product->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        
                        <div>
                            <h4 class="font-medium text-gray-600">Last Updated</h4>
                            <p class="text-gray-900">{{ $product->updated_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-600">Description</h4>
                        <div class="bg-gray-50 rounded p-4 mt-2">
                            <p class="text-gray-900">{{ $product->description ?? 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Orders Section (Optional) -->
    @if($product->orderItems->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold">Orders Containing This Product</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($product->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $item->order_id }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->order->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $item->order->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->order->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $item->order->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $item->order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $item->order->status === 'processing' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($item->order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('admin.orders.show', $item->order) }}" class="text-indigo-600 hover:text-indigo-900">View Order</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endsection