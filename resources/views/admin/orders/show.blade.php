@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
<div class="max-w-7xl mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Order #{{ $order->id }} Details</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.orders.exportPdf', $order->id) }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </a>
                
                <!-- Simple inline form for status update -->
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="inline-flex items-center">
                    @csrf
                    @method('PUT')
                    <select name="status" class="border border-gray-300 rounded-l px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                        <option value="To Pay" {{ $order->status === 'To Pay' ? 'selected' : '' }}>To Pay</option>
                        <option value="To Ship" {{ $order->status === 'To Ship' ? 'selected' : '' }}>To Ship</option>
                        <option value="To Receive" {{ $order->status === 'To Receive' ? 'selected' : '' }}>To Receive</option>
                        <option value="Completed" {{ $order->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    <button type="submit" class="px-3 py-2 bg-blue-500 text-white rounded-r hover:bg-blue-600 flex items-center">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Customer Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow-sm">
                <h3 class="text-lg font-medium mb-3 border-b pb-2">Customer Information</h3>
                <p class="mb-2"><span class="font-medium">Name:</span> {{ $order->user?->name ?? 'N/A' }}</p>
                <p class="mb-2"><span class="font-medium">Email:</span> {{ $order->user?->email ?? 'N/A' }}</p>
                <p class="mb-2"><span class="font-medium">Phone:</span> {{ $order->phone_number ?? 'N/A' }}</p>
                <p class="mb-2"><span class="font-medium">Order Date:</span> {{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>

            <!-- Shipping Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded shadow-sm">
                <h3 class="text-lg font-medium mb-3 border-b pb-2">Shipping Details</h3>
                <p class="mb-2"><span class="font-medium">Address:</span> {{ $order->shipping_address }}</p>
                <p class="mb-2"><span class="font-medium">Payment Method:</span> {{ $order->payment_method }}</p>
                <p class="mb-2"><span class="font-medium">Order Status:</span> 
                    <span class="px-2 py-1 text-xs rounded 
                        @switch($order->status)
                            @case('To Pay')
                                bg-yellow-100 text-yellow-800
                                @break
                            @case('To Ship')
                                bg-blue-100 text-blue-800
                                @break
                            @case('To Receive')
                                bg-purple-100 text-purple-800
                                @break
                            @case('Completed')
                                bg-green-100 text-green-800
                                @break
                            @default
                                bg-gray-100 text-gray-800
                        @endswitch
                    ">{{ $order->status }}</span>
                </p>
            </div>
        </div>

        <!-- Order Status Flow -->
        <div class="mb-8">
            <h3 class="text-lg font-medium mb-4">Order Progress</h3>
            @php
                $statuses = ['To Pay', 'To Ship', 'To Receive', 'Completed'];
                $currentIndex = array_search($order->status, $statuses);
            @endphp
            <div class="relative">
                <!-- Progress Bar Background -->
                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded-full absolute top-5 start-0 w-full z-0"></div>
                
                <!-- Progress Bar Fill -->
                @if($currentIndex >= 0)
                <div class="h-2 bg-green-500 rounded-full absolute top-5 start-0 z-10" 
                     style="width: {{ ($currentIndex / (count($statuses) - 1)) * 100 }}%"></div>
                @endif
                
                <!-- Status Points -->
                <div class="flex justify-between relative z-20">
                    @foreach($statuses as $index => $status)
                    <div class="flex flex-col items-center w-24">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center
                            @if($index < $currentIndex)
                                bg-green-500 text-white
                            @elseif($index == $currentIndex)
                                bg-blue-500 text-white
                            @else
                                bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-300
                            @endif">
                            @switch($status)
                                @case('To Pay')
                                    <i class="fas fa-credit-card text-lg"></i>
                                    @break
                                @case('To Ship')
                                    <i class="fas fa-box text-lg"></i>
                                    @break
                                @case('To Receive')
                                    <i class="fas fa-shipping-fast text-lg"></i>
                                    @break
                                @case('Completed')
                                    <i class="fas fa-check-circle text-lg"></i>
                                    @break
                            @endswitch
                        </div>
                        <span class="text-xs mt-2 text-center">{{ $status }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Order Items</h3>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-6 py-3">Product</th>
                            <th class="px-6 py-3">Quantity</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $item)
                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 flex items-center">
                                
<img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded mr-4">

<div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $item->product->name ?? 'N/A' }}</p>
                                        @if($item->product && $item->product->sku)
                                            <p class="text-xs text-gray-500">SKU: {{ $item->product->sku }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 font-medium">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-6 py-3 text-right font-medium">Subtotal:</td>
                            <td class="px-6 py-3 font-medium">₱{{ number_format($order->orderItems->sum(function($item) { 
                                return $item->price * $item->quantity; 
                            }), 2) }}</td>
                        </tr>
                        @if($order->shipping_fee)
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-6 py-3 text-right font-medium">Shipping Fee:</td>
                            <td class="px-6 py-3 font-medium">₱{{ number_format($order->shipping_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->discount)
                        <tr class="bg-gray-50 dark:bg-gray-700">
                            <td colspan="3" class="px-6 py-3 text-right font-medium">Discount:</td>
                            <td class="px-6 py-3 font-medium text-red-600">-₱{{ number_format($order->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="bg-gray-100 dark:bg-gray-600">
                            <td colspan="3" class="px-6 py-3 text-right font-bold">Total:</td>
                            <td class="px-6 py-3 font-bold">₱{{ number_format($order->total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Order Notes (if any) -->
        @if($order->notes)
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-2">Order Notes</h3>
            <div class="bg-yellow-50 dark:bg-gray-700 p-4 rounded border border-yellow-200 dark:border-gray-600">
                <p class="text-gray-700 dark:text-gray-300">{{ $order->notes }}</p>
            </div>
        </div>
        @endif

        <!-- Back to Orders List -->
        <div class="mt-6">
            <a href="{{ route('admin.orders.index') }}" class="text-blue-500 hover:underline flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders List
            </a>
        </div>
    </div>
</div>
@endsection