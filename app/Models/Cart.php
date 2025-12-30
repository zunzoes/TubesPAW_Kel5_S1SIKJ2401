<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Helper methods
    public function getTotalItemsAttribute()
    {
        return $this->cartItems->sum('quantity');
    }

    public function getSubtotalAttribute()
    {
        return $this->cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function isEmpty()
    {
        return $this->cartItems->count() === 0;
    }

    public function clearCart()
    {
        $this->cartItems()->delete();
    }
}