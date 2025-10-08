<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name','wikidata_qid','country','country_code','city','domains','website','logo_url','aliases'
    ];

    protected $casts = [
        'domains' => 'array',
        'aliases' => 'array',
    ];
}
