<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{

    protected $table = 'educations';

    protected $fillable = [
        'user_id',
        'institution_id',
        'school',
        'degree',
        'field',
        'start_year',
        'end_year',
        'is_current',
        'position',
    ];

    protected $casts = [
        'is_current' => 'bool',
        'start_year' => 'int',
        'end_year'   => 'int',
        'position'   => 'int',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
