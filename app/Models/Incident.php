<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être enregistrés (Mass Assignment)
     */
    protected $fillable = [
        'user_id',
        'resource_id',
        'subject',
        'description',
        'status',
    ];

    /**
     * Relation avec l'utilisateur qui a fait le signalement
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la ressource concernée
     */
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}