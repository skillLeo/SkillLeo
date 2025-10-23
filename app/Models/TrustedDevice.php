<?php
// app/Models/TrustedDevice.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrustedDevice extends Model
{
    protected $fillable = [
        'user_id', 'device_name', 'device_type', 'browser', 
        'platform', 'ip_address', 'user_agent', 'device_token', 
        'last_used_at', 'expires_at'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}