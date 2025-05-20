<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the user's shopping cart.
     */
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity,
        ]);

        // Check if product is already in cart
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();
            
        if ($cartItem) {
            // Update quantity if product already in cart
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check if new quantity exceeds available stock
            if ($newQuantity > $product->stock_quantity) {
                return redirect()->back()->with('error', 'Requested quantity exceeds available stock.');
            }
            
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            // Add new cart item
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }
        return redirect()->route('cart.index')->with('success', "{$product->name} was successfully added to your cart.");
        
    }

    /**
     * Update the specified cart item quantity.
     */
    public function update(Request $request, $id)
{
    // Retrieve the cart item for the authenticated user
    $cartItem = CartItem::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    $request->validate([
        'quantity' => 'required|integer|min:1|max:' . $cartItem->product->stock_quantity,
    ]);

    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
}
    /**
     * Remove the specified cart item.
     */
    public function remove($id)
{
    // Retrieve the cart item for the authenticated user
    $cartItem = CartItem::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

    $cartItem->delete();

    return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
}
}