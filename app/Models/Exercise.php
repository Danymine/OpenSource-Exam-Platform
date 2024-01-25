<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Exercise extends Model
{
    use SoftDeletes; // USO IL SOFT_DELETE
    public $timestamps = false; // Disabilita il timestamping
    protected $fillable = [
        'name', 'question', 'score', 'difficulty', 'subject', 'type', 'correct_option'
    ];

    public function practices() : BelongsToMany //L'esercizio compone practice relazione N a N (Vedi Schema)
    {
        
        return $this->belongsToMany(Practice::class);
    }

    public function user() : BelongsTo //L'esercizio viene creato da un user. Relazione (1,1) a user mentre dall'altra parte è (0,N)
    {
        
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}