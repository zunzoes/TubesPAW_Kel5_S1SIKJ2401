<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_code',
        'stock',
        'additional_price',
    ];

    protected $casts = [
        'stock' => 'integer',
        'additional_price' => 'decimal:2',
    ];

    // Relationships
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

    // Helper methods
    public function getFinalPriceAttribute()
    {
        return $this->product->base_price + $this->additional_price;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function getVariantNameAttribute()
    {
        return "{$this->size} - {$this->color}";
    }
}