<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with(['cartItems.product.primaryImage', 'cartItems.productVariant', 'cartItems.customDesign'])
            ->where('user_id', auth()->id())
            ->first();

        // Menggunakan count() karena firstOrCreate mengembalikan objek, bukan collection
        if (!$cart || $cart->cartItems->count() === 0) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        return view('customer.checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        // 1. Validasi disesuaikan dengan atribut 'name' di form (full_name & phone_number)
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'shipping_method' => ['required', 'in:regular,express,same_day'],
            'notes' => ['nullable', 'string'],
        ]);

        $cart = Cart::with(['cartItems.product', 'cartItems.productVariant'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$cart || $cart->cartItems->count() === 0) {
            return redirect()->route('customer.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // 2. Logika Biaya Pengiriman Dinamis
            $shippingRates = [
                'regular' => 15000,
                'express' => 25000,
                'same_day' => 50000
            ];
            $shippingCost = $shippingRates[$request->shipping_method];

            // 3. Hitung Totals
            $subtotal = $cart->cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $total = $subtotal + $shippingCost;

            // 4. Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'shipping_name' => $validated['full_name'], // Mapping dari full_name
                'shipping_phone' => $validated['phone_number'], // Mapping dari phone_number
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes'],
            ]);

            // 5. Create order items
            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'product_name' => $cartItem->product->name,
                    'variant_details' => json_encode([
                        'size' => $cartItem->productVariant->size,
                        'color' => $cartItem->productVariant->color,
                    ]),
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->price * $cartItem->quantity,
                    'custom_design_id' => $cartItem->custom_design_id,
                ]);

                // Reduce stock
                $cartItem->productVariant->decrement('stock', $cartItem->quantity);
            }

            // 6. Create payment record (Default method: bank_transfer untuk awal)
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'bank_transfer', 
                'amount' => $total,
                'status' => 'pending',
            ]);

            // 7. Bersihkan Keranjang (Hapus CartItems terlebih dahulu)
            $cart->cartItems()->delete();

            DB::commit();

            return redirect()->route('customer.orders.show', $order->id)
                ->with('success', 'Order created successfully! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk mempermudah debugging jika gagal
            \Log::error($e->getMessage());
            return back()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }
}