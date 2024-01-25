<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Answer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'response',
        'delivered_id',
        'exercise_id'
    ];
    
    public function delivered() : BelongsTo
    {
        
        return $this->belongsTo(Delivered::class);
    }
    
    public function exercise() : BelongsTo
    {

        return $this->belongsTo(Exercise::class);
    }

    /*
    public function user() : BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function practice() : BelongsTo
    {

        return $this->belongsTo(Practice::class);
    }
    */
}
