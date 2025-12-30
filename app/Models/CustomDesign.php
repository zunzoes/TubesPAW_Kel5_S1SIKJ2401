<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomDesign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id', // Tambahkan product_id agar desain terikat ke produk dasar
        'design_file',
        'design_notes',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Product (Kaos polos yang dipilih user)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    
    /**
     * Mendapatkan URL gambar desain yang mendukung storage lokal
     */
    public function getDesignFileUrlAttribute()
    {
        if (!$this->design_file) return null;
        return asset('storage/' . $this->design_file);
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    // Helper methods
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    // Model Events
    protected static function boot()
    {
        parent::boot();

        // Otomatis hapus file fisik saat record di database dihapus
        static::deleting(function ($design) {
            if ($design->design_file && Storage::disk('public')->exists($design->design_file)) {
                Storage::disk('public')->delete($design->design_file);
            }
        });
    }
}