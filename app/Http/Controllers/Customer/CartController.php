<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        // Eager loading relasi untuk performa dan menghindari N+1 query
        $cart = Cart::with(['cartItems.product.primaryImage', 'cartItems.productVariant', 'cartItems.customDesign'])
            ->firstOrCreate(['user_id' => auth()->id()]);

        return view('customer.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'custom_design_id' => ['nullable', 'exists:custom_designs,id'],
        ], [
            'variant_id.required' => 'Silakan pilih ukuran terlebih dahulu.'
        ]);

        // Menangani nilai null untuk custom_design_id agar tidak menyebabkan error array key
        $customDesignId = $validated['custom_design_id'] ?? null;

        // 2. Dapatkan atau buat Keranjang
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // 3. Ambil data Produk dan Varian
        $product = Product::findOrFail($validated['product_id']);
        $variant = ProductVariant::findOrFail($validated['variant_id']);

        // 4. Validasi Stok
        if ($variant->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // 5. Kalkulasi Harga (Harga Dasar + Tambahan Harga Varian)
        $price = $product->base_price + $variant->additional_price;

        // 6. Cek Duplikasi Item di Keranjang
        // Kita memisahkan item berdasarkan product_id, variant_id, DAN custom_design_id
        $existingItem = $cart->cartItems()
            ->where('product_id', $validated['product_id'])
            ->where('product_variant_id', $validated['variant_id'])
            ->where('custom_design_id', $customDesignId)
            ->first();

        if ($existingItem) {
            // Jika item sudah ada (misal ukuran dan desain sama), update quantity
            $newQuantity = $existingItem->quantity + $validated['quantity'];
            
            if ($variant->stock < $newQuantity) {
                return back()->with('error', 'Total jumlah di keranjang melebihi stok tersedia.');
            }

            $existingItem->update(['quantity' => $newQuantity]);
        } else {
            // Jika item baru (atau desain berbeda), buat record baru
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $validated['product_id'],
                'product_variant_id' => $validated['variant_id'],
                'quantity' => $validated['quantity'],
                'price' => $price,
                'custom_design_id' => $customDesignId,
            ]);
        }

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        // Proteksi Keamanan: Pastikan user hanya mengedit keranjang miliknya
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Cek Stok saat update quantity
        if ($cartItem->productVariant->stock < $validated['quantity']) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(CartItem $cartItem)
    {
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}