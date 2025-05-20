@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6">Order Details</h2>

        <!-- Success message banner (if needed) -->
        @if(session('success'))
        <div class="mb-4 bg-green-500 text-white p-3 rounded flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Customer Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-3">Customer Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Name:</strong> {{ $order->user?->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Email:</strong> {{ $order->user?->email ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Phone:</strong> {{ $order->user?->phone ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i A') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Order ID:</strong> #{{ $order->id }}</p>
            </div>

            <!-- Shipping Details -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h3 class="text-lg font-medium mb-3">Shipping Details</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Address:</strong> {{ $order->shipping_address }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300"><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    <strong>Order Status:</strong> 
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ $order->status }}
                    </span>
                </p>
            </div>
        </div>

        <!-- Order Status Flow with Icons -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-3">Order Progress</h3>
            @php
                $statuses = ['To Pay', 'To Ship', 'To Receive', 'Completed'];
                $currentIndex = array_search($order->status, $statuses);
            @endphp
            <div class="flex items-center space-x-4 overflow-x-auto">
                @foreach ($statuses as $index => $status)
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full 
                                {{ $index <= $currentIndex ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }} 
                                text-white flex items-center justify-center">
                                <!-- Icons for each status -->
                                @switch($status)
                                    @case('To Pay')
                                        <i class="fas fa-credit-card"></i>
                                        @break
                                    @case('To Ship')
                                        <i class="fas fa-box"></i>
                                        @break
                                    @case('To Receive')
                                        <i class="fas fa-truck"></i>
                                        @break
                                    @case('Completed')
                                        <i class="fas fa-check-circle"></i>
                                        @break
                                @endswitch
                            </div>
                            <span class="text-xs mt-1 text-center w-16">{{ $status }}</span>
                        </div>
                        @if (!$loop->last)
                            <div class="w-6 h-1 bg-gray-300 dark:bg-gray-600 mx-2 
                                {{ $index < $currentIndex ? 'bg-blue-500' : '' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Items Table with Product Image -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-3">Order Items</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 border">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-300">
                        <tr>
                            <th scope="col" class="px-4 py-2">Product</th>
                            <th scope="col" class="px-4 py-2">Quantity</th>
                            <th scope="col" class="px-4 py-2">Price</th>
                            <th scope="col" class="px-4 py-2">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="px-4 py-2 flex items-center">
                                    <img src="{{ asset('storage/'.$item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-12 h-12 object-cover mr-4">
                                    {{ $item->product->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                <td class="px-4 py-2">₱{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-2">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col items-end mt-6">
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg w-full md:w-64">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>₱{{ number_format($order->total, 2) }}</span>
                </div>
                <div class="flex justify-between font-semibold text-lg">
                    <span>Total:</span>
                    <span>₱{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="mt-8 flex justify-between">
            <a href="{{ route('orders.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Orders
            </a>
            
            <!-- Only show if status is "To Receive" -->
            @if($order->status === 'To Receive')
            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Confirm Receipt
            </button>
            @endif
            
            <a href="#" onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print / Save PDF
            </a>
        </div>
    </div>
</div>
@endsection