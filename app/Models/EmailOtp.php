<?php

// app/Models/EmailOtp.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtp extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
        'consumed_at',
        'attempts',
        'status',
        'ip',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'consumed_at' => 'datetime',
        'attempts' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }

    public function isConsumed(): bool
    {
        return !is_null($this->consumed_at);
    }

    public function canAttempt(): bool
    {
        return $this->attempts < 5 && !$this->isExpired() && !$this->isConsumed();
    }
}
