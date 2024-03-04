<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes; 

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes; // Aggiunto SoftDeletes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'date_birth',
        'email',
        'password',
        'roles',
        'img_profile',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function practices() : HasMany   //Crea una practices (Vedi schema)
    {
        return $this->hasMany(Practice::class);
    }

    public function waitingroom() : BelongsToMany   //Partecipare vedi schema
    {
        return $this->belongsToMany(Practice::class, 'waiting_rooms', 'user_id', 'practice_id')->withPivot('status');
    }

    public function delivereds() : HasMany  // Relazione fra User e Consegna vedi schema
    {
        return $this->hasMany(Delivered::class);
    }

    public function exercises() : HasMany //Relazione tra esercizi di un utente
    {
        return $this->hasMany(Exercise::class);
    }

    public function assistanceRequest() : HasMany
    {
        return $this->hasMany(AssistanceRequest::class);
    }
}
