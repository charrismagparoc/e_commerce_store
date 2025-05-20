<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get counts for dashboard cards
        $orderCount = Order::count();
        $productCount = Product::count();
        $categoryCount = Category::count();
        $userCount = User::count();
        
        // Get recent orders for the table
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'orderCount',
            'productCount',
            'categoryCount',
            'userCount',
            'recentOrders'
        ));
    }
}