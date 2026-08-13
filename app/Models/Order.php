<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'code',
        'user_id',
        'payment_id',
        'phone_number',
        'email',
        'fullname',
        'address',
        'note',
        'cancel_reason',
        'cancel_note',
        'cancelled_at',
        'total_amount',
        'discount_amount',
        'shipping_type',
        'shipping_fee',
        'is_paid',
        'coupon_id',
        'img_refunded_money',
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee'    => 'decimal:2',
        'is_paid'         => 'boolean',
        'cancelled_at'    => 'datetime',
    ];

    /* -------------------------------------------------- Relations */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class);
    }

    public function statuses(): BelongsToMany
    {
        return $this->belongsToMany(OrderStatus::class, 'order_order_status')
            ->withPivot(['modified_by', 'note', 'employee_evidence', 'customer_confirmation', 'is_current'])
            ->withTimestamps();
    }

    public function currentStatus(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'current_status_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }


    /* -------------------------------------------------- Helpers */

    /**
     * Get current order status from pivot table
     */
    public function getCurrentStatus(): ?OrderStatus
    {
        return $this->statuses()
            ->where('order_order_status.is_current', true)
            ->first();
    }

    /**
     * Generate unique order code
     */
    public static function generateCode(): string
    {
        do {
            $code = 'OD' . date('Y') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Grand total = total + shipping - discount
     */
    public function getGrandTotalAttribute(): float
    {
        return (float) $this->total_amount + (float) $this->shipping_fee - (float) $this->discount_amount;
    }

    /**
     * Status badge colors
     */
    public static function statusColors(): array
    {
        return [
            1  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-800',   'border' => 'border-amber-200'],
            2  => ['bg' => 'bg-orange-100',  'text' => 'text-orange-800',  'border' => 'border-orange-200'],
            3  => ['bg' => 'bg-blue-100',    'text' => 'text-blue-800',    'border' => 'border-blue-200'],
            4  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
            5  => ['bg' => 'bg-purple-100',  'text' => 'text-purple-800',  'border' => 'border-purple-200'],
            6  => ['bg' => 'bg-pink-100',    'text' => 'text-pink-800',    'border' => 'border-pink-200'],
            7  => ['bg' => 'bg-red-100',     'text' => 'text-red-800',     'border' => 'border-red-200'],
            8  => ['bg' => 'bg-slate-100',   'text' => 'text-slate-600',   'border' => 'border-slate-200'],
            9  => ['bg' => 'bg-sky-100',     'text' => 'text-sky-800',     'border' => 'border-sky-200'],
            10 => ['bg' => 'bg-teal-100',    'text' => 'text-teal-800',    'border' => 'border-teal-200'],
        ];
    }

    /**
     * Map of valid transitions in the order state machine
     */
    public static function getTransitionMap(): array
    {
        return [
            1 => [2, 8],  // Chờ xác nhận -> Chờ lấy hàng, Đã hủy
            2 => [9],     // Chờ lấy hàng -> Gửi hàng
            9 => [3],     // Gửi hàng -> Đang giao
            3 => [4],     // Đang giao -> Giao hàng thành công
            4 => [5, 10], // Giao hàng thành công -> Chờ trả hàng, Nhận hàng thành công
            5 => [6],     // Chờ trả hàng -> Đã trả hàng
            6 => [7],     // Đã trả hàng -> Hoàn tiền
            10 => [5],    // Nhận hàng thành công -> Chờ trả hàng
        ];
    }

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(int $nextStatusId): bool
    {
        $currentStatus = $this->getCurrentStatus();
        $currentStatusId = $currentStatus ? (int) $currentStatus->id : null;

        if (is_null($currentStatusId)) {
            return $nextStatusId === 1;
        }

        if ($currentStatusId === $nextStatusId) {
            return true;
        }

        $allowedNext = self::getTransitionMap()[$currentStatusId] ?? [];
        return in_array($nextStatusId, $allowedNext);
    }
}
