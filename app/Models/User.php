<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'phone_number',
        'email',
        'password',
        'fullname',
        'avatar',
        'gender',
        'birthday',
        'role',
        'status',
        'bank_name',
        'user_bank_name',
        'bank_account',
        'reason_lock',
        'is_change_password',
        'remember_token',
        'email_verified_at',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'is_change_password' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function activeCart()
    {
        return $this->hasOne(Cart::class)->where('status', 'active');
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
}
