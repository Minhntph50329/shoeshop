<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'usage_limit',
        'usage_count',
        'is_active',
        'is_notified',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'is_active' => 'boolean',
        'is_notified' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;
        
        $now = now();
        if ($this->start_date && $this->start_date > $now) return false;
        if ($this->end_date && $this->end_date < $now) return false;

        return true;
    }
}
