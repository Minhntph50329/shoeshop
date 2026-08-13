<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'message',
        'reply_message',
        'replied_at',
        'status',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
