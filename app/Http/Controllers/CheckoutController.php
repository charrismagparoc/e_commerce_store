<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index(): View|RedirectResponse
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process the checkout.
     */
    public function process(Request $request): RedirectResponse
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);

        $cartItems = auth()->user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Check if all items are in stock
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->stock_quantity) {
                return redirect()->route('cart.index')->with('error', 
                    "Sorry, '{$cartItem->product->name}' only has {$cartItem->product->stock_quantity} items in stock.");
            }
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'total_amount' => $total,
            'status' => 'pending',
            'shipping_address' => $request->shipping_address,
            'payment_method' => $request->payment_method,
        ]);

        // Create order items and update product stock
        foreach ($cartItems as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
            ]);

            // Update product stock
            $product = $cartItem->product;
            $product->stock_quantity -= $cartItem->quantity;
            $product->save();
        }

        // Clear cart
        auth()->user()->cartItems()->delete();

        return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully.');
    }
}