<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssistanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'description',
        'user_id',
        'admin_id',
        'status'
    ];

    public function user() : BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function responses() : HasMany
    {
        return $this->HasMany(Response::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
