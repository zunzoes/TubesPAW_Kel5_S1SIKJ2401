<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'product_name',
        'variant_details',
        'quantity',
        'price',
        'subtotal',
        'custom_design_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function customDesign()
    {
        return $this->belongsTo(CustomDesign::class);
    }

    // Helper methods
    public function getVariantDetailsArrayAttribute()
    {
        return json_decode($this->variant_details, true);
    }

    public function hasCustomDesign()
    {
        return !is_null($this->custom_design_id);
    }
}