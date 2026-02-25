<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $totalVendors = Vendor::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $pendingVendors = Vendor::where('status', 'pending')->count();

        // Monthly sales for chart (last 12 months)
        $monthlySales = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Top 5 products by order count
        $topProducts = Product::withCount('orderItems')
            ->orderByDesc('order_items_count')
            ->take(5)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(10)->get();

        return view('admin.dashboard.index', compact(
            'totalRevenue', 'totalOrders', 'totalVendors', 'totalCustomers',
            'pendingVendors', 'monthlySales', 'topProducts', 'recentOrders'
        ));
    }
}
