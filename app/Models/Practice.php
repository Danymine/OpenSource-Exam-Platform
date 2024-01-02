<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Practice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'subject',
        'total_score',
        'key',
        'user_id',
    ];

    public function user() : BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function exercises() : BelongsToMany
    {
        return $this->belongsToMany(Exercise::class);
    }

    public function answers() : HasMany
    {

        return $this->hasMany(Answer::class);
    }


}
