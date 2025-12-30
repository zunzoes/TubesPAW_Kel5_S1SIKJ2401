<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status',
        'payment_proof',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Helper methods
    public function getPaymentDetailsArrayAttribute()
    {
        return json_decode($this->payment_details, true);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'info',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? \Storage::url($this->payment_proof) : null;
    }
}