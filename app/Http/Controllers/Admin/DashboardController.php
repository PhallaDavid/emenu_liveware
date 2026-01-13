<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'orders' => Order::whereDate('created_at', today())->count(),
            'revenue' => Order::whereDate('created_at', today())->where('status', '!=', 'cancelled')->sum('total_price'),
            'products' => Product::count(),
            'categories' => Category::count(),
        ];

        // Prepare Chart Data (Sales by Category)
        // This is a simplified logic. In a real app we'd join tables.
        // For now, let's just show dummy distribution based on products count per category
        // or a mock distribution since we don't have order_items-to-category relationship strictly defined in DB for aggregation easily without a join
        
        $categories = Category::withCount('products')->get();
        
        $chartData = [
            'labels' => $categories->pluck('name'),
            'data' => $categories->pluck('products_count'),
        ];

        // Monthly Revenue Chart Data
        $monthlyRevenue = Order::selectRaw('SUM(total_price) as total, MONTH(created_at) as month')
            ->whereYear('created_at', date('Y'))
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [
            __('messages.jan'), __('messages.feb'), __('messages.mar'), __('messages.apr'), 
            __('messages.may'), __('messages.jun'), __('messages.jul'), __('messages.aug'), 
            __('messages.sep'), __('messages.oct'), __('messages.nov'), __('messages.dec')
        ];
        $revenueData = array_fill(0, 12, 0);

        foreach ($monthlyRevenue as $record) {
            $revenueData[$record->month - 1] = $record->total;
        }

        $recentOrders = Order::latest()->take(5)->get();
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'months', 'revenueData', 'recentOrders', 'recentProducts'));
    }
}
