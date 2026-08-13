<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price_at_time;
        });
    }

    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }
}
