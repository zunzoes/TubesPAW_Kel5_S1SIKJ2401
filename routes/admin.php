<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ChatController;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Product management
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/variants', [ProductController::class, 'storeVariant'])->name('products.variants.store');
    
    /**
     * FIX: Menambahkan rute PATCH untuk mengupdate stok dan harga varian.
     * Ini akan memperbaiki masalah input stok yang tidak bisa disimpan di halaman edit.
     */
    Route::patch('products/variants/{variant}', [ProductController::class, 'updateVariant'])->name('products.variants.update');
    
    Route::delete('products/variants/{variant}', [ProductController::class, 'destroyVariant'])->name('products.variants.destroy');
    
    /**
     * Menghapus gambar produk secara spesifik.
     */
    Route::delete('products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    
    // Category management
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Order management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Chat Management
    Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('chats/{chat}/reply', [ChatController::class, 'reply'])->name('chats.reply');
});