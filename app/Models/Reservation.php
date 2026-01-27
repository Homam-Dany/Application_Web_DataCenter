<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'start_date',
        'end_date',
        'status',
        'justification',
        'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function resource() { return $this->belongsTo(Resource::class); }
    public function user() { return $this->belongsTo(User::class); }

    public function scopeOverlapping($query, $resourceId, $startDate, $endDate)
    {
        return $query->where('resource_id', $resourceId)
                     ->whereIn('status', ['Approuvée', 'en_attente', 'Active'])
                     ->where(function ($q) use ($startDate, $endDate) {
                         $q->where(function ($inner) use ($startDate, $endDate) {
                             $inner->where('start_date', '<', $endDate)
                                   ->where('end_date', '>', $startDate);
                         });
                     });
    }

    public function isExpired() { return $this->end_date->isPast(); }

    public function isActive() {
        return $this->start_date->isPast() && $this->end_date->isFuture() && $this->status === 'Approuvée';
    }
}