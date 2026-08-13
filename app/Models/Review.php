<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    use SoftDeletes;

    protected $table = 'reviews';

    protected $fillable = [
        'product_id',
        'order_id',
        'order_item_id',
        'user_id',
        'rating',
        'review_id',
        'review_text',
        'reason',
        'is_active',
        'has_replies',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_replies' => 'boolean',
        'rating' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ReviewImage::class, 'review_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'review_id')->where('is_active', true);
    }

    public function adminReplies(): HasMany
    {
        return $this->hasMany(Review::class, 'review_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
