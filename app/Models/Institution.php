<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    protected $fillable = [
        'name','country','country_code','city','domains','website','logo_url','aliases','kind'
    ];

    protected $casts = [
        'domains' => 'array',
        'aliases' => 'array',
    ];
}
