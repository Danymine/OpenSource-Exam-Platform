<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'response',
        'assistance_request_id',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function assistance_request() : BelongsTo
    {
        return $this->BelongsTo(AssistanceRequest::class);
    }
}
