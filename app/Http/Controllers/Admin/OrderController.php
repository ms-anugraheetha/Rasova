<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('order_status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'ilike', '%' . $request->input('search') . '%');
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'address', 'payment', 'statusHistory.changedBy']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,stock_issue,failed',
        ]);

        // Never let processing/shipped be set unless payment has actually
        // succeeded — a failed or unpaid order can't move forward until a
        // successful payment is received.
        if (in_array($validated['order_status'], ['processing', 'shipped', 'delivered']) && $order->payment_status !== 'paid') {
            return back()->with('error', 'This order cannot move to that status until payment is confirmed as paid.');
        }

        $order->update(['order_status' => $validated['order_status']]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $validated['order_status'],
            'changed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Order status updated.');
    }
}