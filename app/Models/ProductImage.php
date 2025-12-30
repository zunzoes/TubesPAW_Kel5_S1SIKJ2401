<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        return Storage::url($this->image_path);
    }

    // Delete image file when model is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            if (Storage::exists($image->image_path)) {
                Storage::delete($image->image_path);
            }
        });
    }
}