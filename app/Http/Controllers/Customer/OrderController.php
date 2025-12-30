<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductFeedback; // Pastikan baris ini ada agar model feedback dikenali
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        /**
         * EAGER LOADING: Menggunakan 'orderItems' sesuai dengan model Order.
         * Memuat produk dan primaryImage untuk menampilkan gambar di daftar pesanan.
         */
        $query = Order::with(['orderItems.product.primaryImage', 'payment'])
            ->where('user_id', auth()->id())
            ->latest();

        // Filter berdasarkan status (All, Pending, Processing, dll)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Verifikasi kepemilikan agar user tidak bisa melihat order orang lain
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        /**
         * Memuat detail item pesanan menggunakan relasi 'orderItems'.
         */
        $order->load([
            'orderItems.product.primaryImage', 
            'orderItems.productVariant', 
            'orderItems.customDesign', 
            'payment'
        ]);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Fitur Baru: Menyimpan feedback/ulasan pelanggan untuk produk yang telah selesai.
     */
    public function storeFeedback(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'rating' => 'required|array',
            'rating.*' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|array',
            'comment.*' => 'nullable|string',
        ]);

        // Verifikasi kepemilikan pesanan
        $order = Order::findOrFail($request->order_id);
        if ($order->user_id !== auth()->id() || $order->status !== 'completed') {
            return back()->with('error', 'Akses ditolak atau pesanan belum selesai.');
        }

        // Loop untuk menyimpan ulasan per produk dalam satu pesanan
        foreach ($request->product_id as $index => $productId) {
            ProductFeedback::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'product_id' => $productId,
                ],
                [
                    'rating' => $request->rating[$index],
                    'comment' => $request->comment[$index] ?? null,
                ]
            );
        }

        return back()->with('success', 'Terima kasih! Ulasan Anda sangat berarti bagi kami.');
    }

    /**
     * Membatalkan pesanan pelanggan.
     * Hanya mengizinkan pembatalan jika status 'pending'.
     */
    public function cancel(Order $order)
    {
        // Verifikasi kepemilikan
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Validasi apakah pesanan memenuhi syarat untuk dibatalkan
        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Maaf, pesanan ini tidak dapat dibatalkan karena sudah masuk tahap proses.');
        }

        // Update status menjadi cancelled
        $order->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('customer.orders.index')
            ->with('success', 'Pesanan #' . $order->order_number . ' telah berhasil dibatalkan.');
    }
}