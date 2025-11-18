<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VehicleDetail;

class VehicleBooking extends Model
{
    protected $fillable = [
        'vehicle_id',
        'full_name',
        'country',
        'email',
        'phone',
        'whatsapp',
        'start_date',
        'end_date',
        'message',
        'status',
    ];

    protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
];

    public function vehicle()
    {
        return $this->belongsTo(VehicleDetail::class);
    }
}
