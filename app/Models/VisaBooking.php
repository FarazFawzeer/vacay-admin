<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaBooking extends Model
{
    use HasFactory;

    protected $table = 'visa_bookings';

    protected $fillable = [
        'inv_no',
        'customer_id',
        'visa_id',
        'passport_number',
        'type',
        'agent',
        'visa_issue_date',
        'visa_expiry_date',
        'status',
    ];

    protected $casts = [
        'visa_issue_date' => 'date',
        'visa_expiry_date' => 'date',
    ];

    // Relations
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }
}
