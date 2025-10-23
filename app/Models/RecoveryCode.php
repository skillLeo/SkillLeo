<?php
// app/Models/RecoveryCode.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecoveryCode extends Model
{
    protected $fillable = ['user_id', 'code', 'plain_code', 'used', 'used_at'];
    
    protected $casts = [
        'used' => 'boolean',
        'used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}