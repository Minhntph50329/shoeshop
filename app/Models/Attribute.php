<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'is_variant',
        'is_active',
    ];

    protected $casts = [
        'is_variant' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }
}
