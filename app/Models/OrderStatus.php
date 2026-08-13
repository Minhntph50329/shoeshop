<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrderStatus extends Model
{
    protected $table = 'order_statuses';
    public $timestamps = false;

    protected $fillable = ['name'];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_order_status')
            ->withPivot(['modified_by', 'note', 'employee_evidence', 'customer_confirmation', 'is_current'])
            ->withTimestamps();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
