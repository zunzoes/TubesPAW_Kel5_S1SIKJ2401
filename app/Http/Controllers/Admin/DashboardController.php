<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalProducts' => Product::count(),
            'totalOrders' => Order::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'totalRevenue' => Order::where('status', 'completed')->sum('total'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'processingOrders' => Order::where('status', 'processing')->count(),
            'shippingOrders' => Order::where('status', 'shipping')->count(),
            'recentOrders' => Order::with('user')->latest()->take(10)->get(),
            'lowStockProducts' => Product::with('variants')
                ->get()
                ->filter(function ($product) {
                    return $product->total_stock < 10;
                })
                ->take(5),
            'topProducts' => Product::withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}