<?php
// app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',              // Professional/project owner
        'client_user_id',       // Client from users table (account_status='client')
        'company',
        'phone',
        'order_value',
        'currency',
        'payment_terms',
        'payment_status',
        'portal_access',
        'can_comment',
        'special_requirements',
        'billing_address',
        'contract_signed_at',
    ];

    protected $casts = [
        'order_value' => 'decimal:2',
        'portal_access' => 'boolean',
        'can_comment' => 'boolean',
        'billing_address' => 'array',
        'contract_signed_at' => 'datetime',
    ];

    /**
     * The professional/project owner
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The client user (from users table where account_status='client')
     */
    public function clientUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }


 

}