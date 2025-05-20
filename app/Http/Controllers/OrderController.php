<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     */
    public function index(): View
    {
        $orders = auth()->user()->orders()->latest()->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): View|RedirectResponse
    {
        // Ensure order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            return redirect()->route('orders.index')->with('error', 'Unauthorized access.');
        }
        
        $order->load('orderItems.product');
        
        return view('orders.show', compact('order'));
    }

    public function exportPdf(Order $order)
    {
        // Ensure the logged-in user owns the order
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load the Blade view and pass the order data
        $pdf = Pdf::loadView('orders.pdf', compact('order'));

        // Return the generated PDF as a download
        return $pdf->download('order-' . $order->id . '.pdf');
    }
}