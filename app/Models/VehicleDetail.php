<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicle_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'make',
        'model',
        'condition',
        'seats',
        'max_seating_capacity',
        'luggage_space',
        'air_conditioned',
        'helmet',
        'first_aid_kit',
        'transmission',
        'milage',
        'price',
        'currency',
        'label',
        'name',
        'availability',
        'vehicle_image',
        'sub_image',
        'type',
        'status',
        'fuel_type',
        'insurance_type',
        'agent_id',
    ];


    protected $casts = [
        'sub_image' => 'array',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
}
