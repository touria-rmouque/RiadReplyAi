<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'current_establishment_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Tous les établissements de l'utilisateur.
     */
   public function establishments(): HasMany
{
    return $this->hasMany(Establishment::class);
}

    /**
     * Établissement actuellement sélectionné.
     */
   public function currentEstablishment(): BelongsTo
{
    return $this->belongsTo(
        Establishment::class,
        'current_establishment_id'
    );
}
}