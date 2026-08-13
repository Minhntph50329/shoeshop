<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'province',
        'district',
        'ward',
        'street',
        'latidute',
        'longtidute',
        'address_type',
        'email',
        'phone_number',
        'fullname',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full formatted address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->ward,
            $this->district,
            $this->province,
            $this->address,
        ]);
        return implode(', ', $parts) ?: ($this->address ?? '');
    }
}
