<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', 
        'category',
        'cpu',
        'ram',
        'storage_capacity',
        'storage_type',
        'bandwidth',
        'os',
        'location',
        'status', // disponible, maintenance, désactivée
        'manager_id',
        'maintenance_start', // NOUVEAU : Pour point 4.4
        'maintenance_end'    // NOUVEAU : Pour point 4.4
    ];

    protected $casts = [
        'maintenance_start' => 'datetime',
        'maintenance_end' => 'datetime',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}