<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'total_revenue'    => Order::whereHas('payment', fn($q) => $q->where('status', 'confirmed'))->sum('total_amount'),
            'total_products'   => Product::where('is_active', true)->count(),
            'total_users'      => User::where('role', 'user')->count(),
            'low_stock'        => Product::where('stock', '<=', 5)->where('is_active', true)->count(),
        ];

        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
