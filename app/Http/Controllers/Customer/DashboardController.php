<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        /**
         * Mengambil Produk Terpopuler dengan Eager Loading 'primaryImage'.
         * Hal ini memastikan foto produk muncul dengan benar di dashboard.
         */
        $data = [
            'featuredProducts' => Product::with(['primaryImage', 'variants', 'category'])
                ->where('is_active', true)
                ->latest()
                ->take(8)
                ->get(),

            // Section 'categories' dihapus sesuai permintaan
            // Section 'myOrders' dihapus sesuai permintaan

            /**
             * Statistik Pesanan tetap dipertahankan untuk mengisi Stats Cards 
             * di bagian atas dashboard.
             */
            
            // 1. Benar-benar Pending (Belum Bayar)
            'pendingOrders' => Order::where('user_id', $userId)
                ->where('status', 'pending')
                ->count(),

            // 2. Sedang Diproses/Dikirim (Sudah Bayar)
            'shippingOrders' => Order::where('user_id', $userId)
                ->whereIn('status', ['paid', 'processing', 'shipping'])
                ->count(),

            // 3. Selesai
            'completedOrders' => Order::where('user_id', $userId)
                ->where('status', 'completed')
                ->count(),

            // 4. Total semua order
            'totalOrders' => Order::where('user_id', $userId)->count(),
        ];

        return view('customer.dashboard', $data);
    }
}