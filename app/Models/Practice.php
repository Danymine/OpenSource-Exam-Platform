<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'subject',
        'total_score',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

}

