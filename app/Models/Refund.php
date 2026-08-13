<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Refund extends Model
{
    protected $table = 'refunds';

    protected $fillable = [
        'order_id',
        'user_id',
        'total_amount',
        'bank_account',
        'user_bank_name',
        'bank_name',
        'reason',
        'aadmin_reason',
        'reason_image',
        'status',
        'is_send_money',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'is_send_money' => 'boolean',
    ];

    // Refund Statuses
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_COMPLETED = 'completed';

    public static function statusLabels(): array
    {
        return [
            self::STATUS_PENDING   => 'Chờ xử lý',
            self::STATUS_APPROVED  => 'Chờ trả hàng',
            self::STATUS_REJECTED  => 'Bị từ chối',
            self::STATUS_COMPLETED => 'Thành công (Đã hoàn tiền)',
        ];
    }

    public static function statusColors(): array
    {
        return [
            self::STATUS_PENDING   => ['bg' => 'bg-amber-100',   'text' => 'text-amber-800',   'border' => 'border-amber-200'],
            self::STATUS_APPROVED  => ['bg' => 'bg-blue-100',    'text' => 'text-blue-800',    'border' => 'border-blue-200'],
            self::STATUS_REJECTED  => ['bg' => 'bg-red-100',     'text' => 'text-red-800',     'border' => 'border-red-200'],
            self::STATUS_COMPLETED => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200'],
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RefundItem::class);
    }

    // State machine transitions
    public static function getTransitionMap(): array
    {
        return [
            self::STATUS_PENDING  => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED => [self::STATUS_COMPLETED],
            self::STATUS_REJECTED => [],
            self::STATUS_COMPLETED => [],
        ];
    }

    public function canTransitionTo(string $nextStatus): bool
    {
        if ($this->status === $nextStatus) {
            return true;
        }

        $allowed = self::getTransitionMap()[$this->status] ?? [];
        return in_array($nextStatus, $allowed);
    }
}
