<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectMedia extends Model
{
    use HasFactory;

    protected $table = 'project_media';

    protected $fillable = [
        'project_id',
        'uploaded_by',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'size_bytes',
        'note',
        'visibility',
        'sort_order',
    ];
    

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isClientVisible(): bool
    {
        return $this->visibility === 'client';
    }
}
