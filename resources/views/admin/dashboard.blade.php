<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Dashboard')

@section('header', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Orders Summary Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-2">Orders</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $orderCount ?? 0 }}</p>
            <p class="text-gray-500 mt-2">Total Orders</p>
            <a href="{{ route('admin.orders.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">View all orders</a>
        </div>

        <!-- Products Summary Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-2">Products</h3>
            <p class="text-3xl font-bold text-green-600">{{ $productCount ?? 0 }}</p>
            <p class="text-gray-500 mt-2">Total Products</p>
            <a href="{{ route('admin.products.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">Manage products</a>
        </div>

        <!-- Categories Summary Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-2">Categories</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $categoryCount ?? 0 }}</p>
            <p class="text-gray-500 mt-2">Total Categories</p>
            <a href="{{ route('admin.categories.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">Manage categories</a>
        </div>

        <!-- Users Summary Card -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-2">Users</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $userCount ?? 0 }}</p>
            <p class="text-gray-500 mt-2">Registered Users</p>
            <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">View users</a>        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders ?? [] as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($order->total, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                    ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                    'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 hover:underline">
                                <a href="{{ route('admin.orders.show', $order->id) }}">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No recent orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection