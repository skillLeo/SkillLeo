<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserReason extends Model
{
    use HasFactory;

    protected $table = 'user_reasons';

    protected $fillable = ['user_id', 'text', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
