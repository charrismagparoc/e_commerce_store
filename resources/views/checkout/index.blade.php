@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Checkout</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-gray-700">Product</th>
                                    <th class="px-4 py-2 text-gray-700">Quantity</th>
                                    <th class="px-4 py-2 text-gray-700">Price</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 mr-3 overflow-hidden">
                                                    @if($item->product->image_path)
                                                        <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                             alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                            <span class="text-gray-500 text-xs">No Image</span>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h3 class="font-medium text-gray-800">{{ $item->product->name }}</h3>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-4 py-3 font-bold" colspan="2">Total</td>
                                    <td class="px-4 py-3 font-bold">${{ number_format($total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Checkout Form -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Shipping & Payment</h2>
                    
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Shipping Address</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" 
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                      required>{{ old('shipping_address') }}</textarea>
                        </div>
                        
                        <div class="mb-6">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="radio" id="credit_card" name="payment_method" value="credit_card" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300" 
                                           checked>
                                    <label for="credit_card" class="ml-2 text-sm text-gray-700">Credit Card</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="paypal" name="payment_method" value="paypal" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="paypal" class="ml-2 text-sm text-gray-700">PayPal</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="cash_on_delivery" name="payment_method" value="cash_on_delivery" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <label for="cash_on_delivery" class="ml-2 text-sm text-gray-700">Cash on Delivery</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition-colors duration-150">
                                Place Order
                            </button>
                            <a href="{{ route('cart.index') }}" class="text-center text-indigo-600 hover:text-indigo-800">
                                Return to Cart
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection