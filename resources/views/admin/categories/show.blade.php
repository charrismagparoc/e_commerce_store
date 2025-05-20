@extends('layouts.admin')

@section('title', 'Category Details')

@section('header', 'Category Details')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center text-blue-600 hover:text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Categories
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800">{{ $category->name }}</h3>
            <div class="flex space-x-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Category Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Category Details</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-medium">{{ $category->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->created_at ? $category->created_at->format('M d, Y \a\t h:i A') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $category->updated_at ? $category->updated_at->format('M d, Y \a\t h:i A') : 'N/A' }}</dd>
                            </div>
                            <div class="md:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($category->description)
                                        {{ $category->description }}
                                    @else
                                        <span class="text-gray-400 italic">No description provided</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Child Categories List -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Subcategories</h4>
                            <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                + Add Subcategory
                            </a>
                        </div>
                        
                        @if($category->children->count() > 0)
                            <div class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('admin.categories.show', $child) }}" class="block hover:bg-gray-50">
                                                <div class="flex items-center justify-between px-4 py-3">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center">
                                                            <p class="text-sm font-medium text-indigo-600 truncate">{{ $child->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4 flex-shrink-0 flex items-center">
                                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $child->products->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $child->products->count() }} {{ Str::plural('product', $child->products->count()) }}
                                                        </span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-center">
                                <p class="text-sm text-gray-500">No subcategories found</p>
                                <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Add First Subcategory
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Products in this Category -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Products in this Category</h4>
                            @if(Route::has('admin.products.create'))
                                <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    + Add Product
                                </a>
                            @endif
                        </div>
                        
                        @if($category->products->count() > 0)
                            <div class="bg-white shadow overflow-hidden border border-gray-200 sm:rounded-md">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($category->products as $product)
                                        <li>
                                            @if(Route::has('admin.products.show'))
                                                <a href="{{ route('admin.products.show', $product) }}" class="block hover:bg-gray-50">
                                            @endif
                                                <div class="flex items-center px-4 py-3">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex items-center">
                                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                                        </div>
                                                        @if(isset($product->price))
                                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                                <span>${{ number_format($product->price, 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    @if(Route::has('admin.products.show'))
                                                        <div class="ml-4 flex-shrink-0">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            @if(Route::has('admin.products.show'))
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-center">
                                <p class="text-sm text-gray-500">No products in this category</p>
                                @if(Route::has('admin.products.create'))
                                    <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="mt-2 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Add First Product
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Parent Category -->
                    <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Parent Category</h3>
                            @if($category->parent)
                                <a href="{{ route('admin.categories.show', $category->parent) }}" class="group block">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 group-hover:text-indigo-600">
                                                {{ $category->parent->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: {{ $category->parent->id }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <div class="text-center py-3">
                                    <p class="text-sm text-gray-500">This is a top-level category</p>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="mt-2 inline-block text-sm text-indigo-600 hover:text-indigo-900">
                                        Change Parent &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Category Stats -->
                    <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Statistics</h3>
                            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div class="bg-gray-50 overflow-hidden rounded-lg p-3">
                                    <dt class="text-xs font-medium text-gray-500 truncate">Subcategories</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $category->children->count() }}</dd>
                                </div>
                                <div class="bg-gray-50 overflow-hidden rounded-lg p-3">
                                    <dt class="text-xs font-medium text-gray-500 truncate">Products</dt>
                                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $category->products->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Quick Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.categories.create') }}?parent_id={{ $category->id }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Subcategory
                                </a>
                                @if(Route::has('admin.products.create'))
                                    <a href="{{ route('admin.products.create') }}?category_id={{ $category->id }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add Product
                                    </a>
                                @endif
                                <a href="{{ route('admin.categories.edit', $category) }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                    Edit Category
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection