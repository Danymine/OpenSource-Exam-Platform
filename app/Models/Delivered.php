<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivered extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'practice_id',
        'created_at'
    ];

    public function user() : BelongsTo  //Consegna
    {
        
        return $this->belongsTo(User::class);
    }

    public function practice() : BelongsTo  //Consegna relativa a ...
    {

        return $this->belongsTo(Practice::class);
    }

    public function answers() : HasMany //Contiene N risposte
    {

        return $this->hasMany(Answer::class);
    }
}
