<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundItem extends Model
{
    protected $table = 'refund_items';

    protected $fillable = [
        'refund_id',
        'product_id',
        'variant_id',
        'name',
        'name_variant',
        'quantity',
        'price',
        'price_variant',
        'quantity_variant',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_variant' => 'decimal:2',
        'quantity' => 'integer',
        'quantity_variant' => 'integer',
    ];

    public function refund(): BelongsTo
    {
        return $this->belongsTo(Refund::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price_variant ?? $this->price);
    }

    public function getLineTotalAttribute(): float
    {
        return $this->effective_price * $this->quantity;
    }
}
