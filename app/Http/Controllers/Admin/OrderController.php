<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function kitchen()
    {
        $orders = Order::whereIn('status', ['pending', 'completed'])->latest()->get();
        return view('admin.kitchen.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        $order->update([
            'status' => $newStatus,
            'is_calling_waiter' => false // Clear call when status updated
        ]);

        return back()->with('success', 'Order status updated.');
    }

    public function dismissWaiter(Order $order)
    {
        $order->update(['is_calling_waiter' => false]);
        return back()->with('success', 'Waiter call dismissed.');
    }

    public function print(Order $order)
    {
        return view('admin.orders.print', compact('order'));
    }
}
