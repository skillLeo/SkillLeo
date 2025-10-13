<?php 


// app/Models/UserSoftSkill.php  (optional but handy)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSoftSkill extends Model
{
    protected $table = 'user_soft_skills';

    protected $fillable = ['user_id', 'soft_skill_id', 'level', 'position'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function softSkill(): BelongsTo
    {
        return $this->belongsTo(SoftSkill::class);
    }
}
