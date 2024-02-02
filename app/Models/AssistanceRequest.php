<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceRequest extends Model
{
    use HasFactory;
    protected $table = 'requests';
   
    protected $fillable = [
        'name',
        'roles',
        'description',
    ];
}
