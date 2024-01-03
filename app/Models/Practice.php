<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Practice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'subject',
        'total_score',
        'key',
        'user_id',
        'feedback_enabled',
        'randomize_questions',
        'generated_at', // Aggiungi il campo per la data di generazione
        'allowed'
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

