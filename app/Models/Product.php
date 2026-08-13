<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'brand_id',
        'name',
        'slug',
        'image',
        'gallery',
        'price',
        'stock',
        'discount',
        'discount_start',
        'discount_end',
        'status',
        'description',
        'short_description',
        'sku',
        'views',
    ];

    protected $casts = [
        'gallery' => 'array',
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_start' => 'datetime',
        'discount_end' => 'datetime',
        'stock' => 'integer',
        'views' => 'integer',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Dynamic attribute: calculate final price considering discount and valid dates
    public function getFinalPriceAttribute()
    {
        $now = now();
        if ($this->discount > 0) {
            $startValid = !$this->discount_start || $this->discount_start <= $now;
            $endValid = !$this->discount_end || $this->discount_end >= $now;
            if ($startValid && $endValid) {
                return max(0, $this->price - $this->discount);
            }
        }
        return $this->price;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->whereNull('review_id');
    }

    public function activeReviews()
    {
        return $this->reviews()->where('is_active', true);
    }

    public function getAverageRatingAttribute()
    {
        $avg = $this->activeReviews()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    public function getReviewsCountAttribute()
    {
        return $this->activeReviews()->count();
    }
}

