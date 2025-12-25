<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AirlineInvBooking extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'airline_inv_bookings';

    // Fillable fields for mass assignment
    protected $fillable = [
        'invoice_no',
        'customer_id',
        'agent',
        'from_country',
        'to_country',
        'departure_datetime',
        'arrival_datetime',
        'airline',
        'currency',
        'base_price',
        'additional_price',
        'discount',
        'total_amount',
        'advanced_paid',
        'balance',
        'status',
        'payment_status',
        'created_by',
    ];

    // Casts
    protected $casts = [
        'departure_datetime' => 'datetime',
        'arrival_datetime'   => 'datetime',
        'base_price'         => 'decimal:2',
        'additional_price'   => 'decimal:2',
        'discount'           => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'advanced_paid'      => 'decimal:2',
        'balance'            => 'decimal:2',
    ];

    // Relationships

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent');
    }

    
}
