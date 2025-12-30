<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * RELASI UTAMA
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * RELASI ALIAS (UNTUK FIX ERROR image_9f6187.png)
     * Menambahkan alias 'items' agar DashboardController tidak error 
     * saat memanggil ->with(['items...']).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // Auto generate order number
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(uniqid());
            }
        });
    }

    // Helper methods
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'processing' => 'Sedang Dikemas',
            'shipping' => 'Sedang Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'info',
            'processing' => 'primary',
            'shipping' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function isPaid()
    {
        return in_array($this->status, ['paid', 'processing', 'shipping', 'completed']);
    }

    /**
     * Cek apakah pesanan dapat dibatalkan.
     * UPDATE: Hanya status 'pending' yang diizinkan untuk dibatalkan.
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }
}