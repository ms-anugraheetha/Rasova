<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_minor');
        
        // Last 7 days of order counts, for the chart
        $ordersPerDay = collect(range(6, 0))->map(function ($daysAgo) {
            $date = Carbon::today()->subDays($daysAgo);
            return [
                'date' => $date->format('M j'),
                'count' => Order::whereDate('created_at', $date)->count(),
            ];
        });

        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'pendingOrders', 'totalRevenue', 'ordersPerDay', 'recentOrders'
        ));
    }
}