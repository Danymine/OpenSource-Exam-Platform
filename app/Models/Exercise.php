<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Exercise extends Model
{
    use SoftDeletes; // USO IL SOFT_DELETE
    public $timestamps = false; // Disabilita il timestamping
    protected $fillable = [
        'name', 'question', 'score', 'difficulty', 'subject', 'type', 'correct_option'
    ];

    public function practices(): BelongsToMany
    {
        return $this->belongsToMany(Practice::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}