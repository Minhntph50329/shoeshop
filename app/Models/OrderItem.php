<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'name',
        'price',
        'old_price',
        'old_price_variant',
        'quantity',
        'name_variant',
        'attributes_variant',
        'price_variant',
        'quantity_variant',
    ];

    protected $casts = [
        'price'             => 'decimal:2',
        'old_price'         => 'decimal:2',
        'old_price_variant' => 'decimal:2',
        'price_variant'     => 'decimal:2',
        'quantity'          => 'integer',
        'quantity_variant'  => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_item_id');
    }

    /**
     * Effective unit price (variant price if set, else product price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price_variant ?? $this->price);
    }


    /**
     * Line total
     */
    public function getLineTotalAttribute(): float
    {
        return $this->effective_price * $this->quantity;
    }
}
