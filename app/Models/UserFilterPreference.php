<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFilterPreference extends Model
{
    protected $fillable = ['user_id', 'visible_tags', 'max_visible'];

    protected $casts = [
        'visible_tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        if (empty($this->visible_tags)) {
            return collect();
        }

        return PortfolioTag::whereIn('id', $this->visible_tags)
            ->orderByRaw('FIELD(id, ' . implode(',', $this->visible_tags) . ')')
            ->get();
    }
}