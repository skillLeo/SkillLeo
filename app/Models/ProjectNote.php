<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectNote extends Model
{
    use HasFactory;

    protected $table = 'project_notes';

    protected $fillable = [
        'project_id',
        'user_id',
        'body',
        'is_internal',
        'pinned',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'pinned'      => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
