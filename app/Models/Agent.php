<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $table = 'agents';

    protected $fillable = [
        'name',
        'email',
        'company_name',
        'designation',
        'company_city',
        'company_country',
        'phone',
        'land_line',
        'whatsapp',
        'service',
        'note',
        'status',
    ];

    protected $casts = [
        'service' => 'array',
    ];

    // Vehicles
    public function vehicles()
    {
        return $this->hasMany(VehicleDetail::class, 'agent_id');
    }

    // ❗ Keep this (primary agent logic)
    public function visas()
    {
        return $this->hasMany(Visa::class, 'agent_id');
    }

    // ✅ NEW: Pivot-based visa relationship
    public function visaPivots()
    {
        return $this->belongsToMany(Visa::class, 'agent_visa');
    }
}
