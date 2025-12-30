<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\ProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\DesignController;
use App\Http\Controllers\Customer\ChatController;

Route::prefix('customer')->name('customer.')->middleware(['auth', 'customer'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Cart
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('cart/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    
    // Checkout
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Orders
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    /**
     * FITUR BARU: Ulasan Produk (Feedback)
     * Mengarahkan ke storeFeedback di OrderController agar ringkas.
     */
    Route::post('orders/feedback', [OrderController::class, 'storeFeedback'])->name('orders.feedback');
    
    // Payment
    Route::get('payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('payment/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::post('payment/{order}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payment.uploadProof');
    
    // Custom Design
    Route::get('design/create', [DesignController::class, 'create'])->name('design.create');
    Route::post('design', [DesignController::class, 'store'])->name('design.store');
    
    // Chat Support
    Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('chat/send', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::post('chat/start', [ChatController::class, 'start'])->name('chat.start');
});