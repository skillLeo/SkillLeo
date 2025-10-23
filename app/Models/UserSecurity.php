<?php
// app/Models/UserSecurity.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurity extends Model
{
    protected $table = 'user_security';
    
    protected $fillable = [
        'user_id',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_email',
        'two_factor_phone',
        'phone_number',
        'require_2fa_new_location',
        'require_2fa_sensitive_actions',
        'login_notifications',
        'last_verified_at',
    ];

    protected $casts = [
        'two_factor_enabled' => 'boolean',
        'two_factor_email' => 'boolean',
        'two_factor_phone' => 'boolean',
        'require_2fa_new_location' => 'boolean',
        'require_2fa_sensitive_actions' => 'boolean',
        'login_notifications' => 'boolean',
        'last_verified_at' => 'datetime',
    ];

    protected $hidden = ['two_factor_secret'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}