<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's order count
        $orderCount = Order::where('user_id', $user->id)->count();
        
        // Get cart count from session
        $cartCount = 0;
        if (session()->has('cart')) {
            $cartCount = count(session('cart'));
        }
        
        // Get wishlist count - if you have a wishlist feature
        $wishlistCount = 0;
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('dashboard', compact(
            'orderCount',
            'cartCount',
            'wishlistCount',
            'recentOrders'
        ));
    }
}