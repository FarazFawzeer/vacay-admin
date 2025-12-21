<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'customer_id',
        'booking_ref_no',
        'travel_date',
        'travel_end_date',
        'adults',
        'children',
        'infants',
        'package_price',
        'discount',
        'tax',
        'total_price',
        'currency',
        'special_requirements',
        'invoice_number',
        'invoice_date',
        'amount_paid',
        'advance_paid', // ✅ added
        'payment_status',
        'payment_method',
        'status',
        'created_by'
    ];


    protected $casts = [
        'travel_date' => 'date',
        'travel_end_date' => 'date',
        'invoice_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getBalanceAmountAttribute()
    {
        return $this->total_price - $this->advance_paid;
    }
}
