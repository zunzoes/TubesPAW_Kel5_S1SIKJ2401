<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'has_design',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'has_design' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships

    /**
     * Relasi ke ProductFeedback (Fitur Rating & Ulasan)
     */
    public function feedbacks()
    {
        return $this->hasMany(ProductFeedback::class);
    }

    /**
     * Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * Relasi ke galeri gambar produk
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Relasi khusus untuk mendapatkan satu gambar utama
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Auto generate slug
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    /**
     * ACCESSOR UNTUK RATING (Fitur Feedback)
     * Menghitung rata-rata rating dari feedback yang masuk.
     */
    public function getAverageRatingAttribute()
    {
        return round($this->feedbacks()->avg('rating') ?? 0, 1);
    }

    /**
     * Mendapatkan total ulasan
     */
    public function getFeedbacksCountAttribute()
    {
        return $this->feedbacks()->count();
    }

    /**
     * ACCESSOR UNTUK TAMPILAN GAMBAR
     */
    public function getImageUrlAttribute()
    {
        if ($this->primaryImage) {
            $path = $this->primaryImage->image_path;

            if (str_starts_with($path, 'http')) {
                return $path;
            }

            return asset('storage/' . $path);
        }

        return asset('images/placeholder-product.png'); 
    }

    // Helper methods

    /**
     * Menampilkan total stok dari semua varian
     */
    public function getTotalStockAttribute()
    {
        return $this->variants->sum('stock');
    }

    /**
     * Menghitung harga minimum
     */
    public function getMinPriceAttribute()
    {
        $minAdditional = $this->variants->min('additional_price') ?? 0;
        return $this->base_price + $minAdditional;
    }

    /**
     * Menghitung harga maksimum
     */
    public function getMaxPriceAttribute()
    {
        $maxAdditional = $this->variants->max('additional_price') ?? 0;
        return $this->base_price + $maxAdditional;
    }
}