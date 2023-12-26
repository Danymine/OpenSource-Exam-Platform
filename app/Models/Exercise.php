<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    public $timestamps = false; // Disabilita il timestamping
    protected $fillable = [
        'name', 'question', 'score', 'difficulty', 'subject', 'type', 'correct_option'
    ];


}



