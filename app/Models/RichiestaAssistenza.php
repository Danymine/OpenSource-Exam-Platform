<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RichiestaAssistenza extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'ruolo',
        'problema',
    ];
}
