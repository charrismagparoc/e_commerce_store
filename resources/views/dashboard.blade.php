<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-bold mb-4">Welcome back, {{ Auth::user()->name }}!</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                        <!-- Orders Card -->
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">My Orders</h3>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $orderCount ?? 0 }}</p>
                            <a href="{{ route('orders.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">View all orders</a>
                        </div>
                        
                        <!-- Cart Card -->
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">My Cart</h3>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $cartCount ?? 0 }} items</p>
                            <a href="{{ route('cart.index') }}" class="text-blue-500 hover:underline mt-4 inline-block">View cart</a>
                        </div>
                        
                        <!-- Wishlist Card -->
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">Wishlist</h3>
                            <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $wishlistCount ?? 0 }}</p>
                            <a href="#" class="text-blue-500 hover:underline mt-4 inline-block">View wishlist</a>
                        </div>
                        
                        <!-- Profile Card -->
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold mb-2">My Profile</h3>
                            <p class="text-gray-600 dark:text-gray-300">Update your account details</p>
                            <a href="{{ route('profile.edit') }}" class="text-blue-500 hover:underline mt-4 inline-block">Edit profile</a>
                        </div>
                    </div>
                    
                    <div class="mt-10">
                        <h3 class="text-xl font-semibold mb-4">Recent Orders</h3>
                        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                                    @forelse($recentOrders ?? [] as $order)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                                    ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">₱{{ number_format($order->total, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-500 hover:underline">
                                                <a href="{{ route('orders.show', $order->id) }}">View Details</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">You haven't placed any orders yet</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>