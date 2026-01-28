<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Définition des constantes pour les rôles (Professionnalisme et clarté)
    const ROLE_ADMIN = 'admin';
    const ROLE_RESPONSABLE = 'responsable';
    const ROLE_USER = 'user';
    const ROLE_GUEST = 'guest';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'rejection_reason',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Méthodes d'aide pour vérifier les rôles dans les contrôleurs et vues
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isResponsable()
    {
        return $this->role === self::ROLE_RESPONSABLE;
    }
    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }
    public function isGuest()
    {
        return $this->role === self::ROLE_GUEST;
    }

    /**
     * Une utilisateur peut avoir plusieurs réservations.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}