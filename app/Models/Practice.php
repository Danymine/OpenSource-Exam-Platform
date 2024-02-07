<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
        'generated_at', 
        'allowed',
        'practice_date',
        'custom_score',
        'type',
        'public',
    ];

    public function user() : BelongsTo  //Relazione crea fra user e practice 
    {

        return $this->belongsTo(User::class);
    }

    public function exercises() : BelongsToMany //L'esercizio compone practice relazione N a N (Vedi Schema)
    {

        return $this->belongsToMany(Exercise::class)->withPivot('custom_score');
    }

    public function userwaiting() : BelongsToMany   //Lo studente partecipa all'esame. Relazione N a N fra User e Practice. Useremo questa relazione come waiting_rooms
    {

        return $this->belongsToMany(User::class, 'waiting_rooms', 'practice_id', 'user_id',);
    }

    public function delivereds() : HasMany  //Consegne relative ad un practice vedi schema
    {

        return $this->hasMany(Delivered::class);
    }

    /* Non vi è più la necessità di questa relazione.
    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }
    */
}

