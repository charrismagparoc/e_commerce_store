<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Get the order that owns the item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product associated with the item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor for the product image URL.
     * This assumes that the 'image' attribute in the product table contains the relative file path.
     */
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->product->image);
    }

    /**
     * Optionally, store the price at the time of order (if you want to keep the price constant even if it changes later).
     */
    public function getPriceAtTimeOfOrder()
    {
        return $this->price; // assuming 'price' is already stored correctly in the order item
    }
}
