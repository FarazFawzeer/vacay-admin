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

    public function vehicles()
    {
        return $this->hasMany(VehicleDetail::class, 'agent_id');
    }
}
