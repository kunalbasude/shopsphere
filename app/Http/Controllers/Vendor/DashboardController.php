<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendor = Auth::user()->vendor;

        $totalEarnings = OrderItem::where('vendor_id', $vendor->id)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->sum('vendor_earning');

        $totalOrders = OrderItem::where('vendor_id', $vendor->id)->distinct('order_id')->count('order_id');
        $totalProducts = $vendor->products()->count();
        $wallet = $vendor->wallet;

        $monthlySales = OrderItem::where('vendor_id', $vendor->id)
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(vendor_earning) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $recentOrders = Order::whereHas('items', fn($q) => $q->where('vendor_id', $vendor->id))
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('vendor.dashboard.index', compact(
            'vendor', 'totalEarnings', 'totalOrders', 'totalProducts',
            'wallet', 'monthlySales', 'recentOrders'
        ));
    }

    public function pending()
    {
        $vendor = Auth::user()->vendor;
        return view('vendor.dashboard.pending', compact('vendor'));
    }
}
