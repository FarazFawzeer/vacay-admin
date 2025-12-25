<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'passport_id',
        'visa_id',
        'visa_category_id',
        'agent_id',
        'currency',
        'base_price',
        'additional_price',
        'discount',
        'total_amount',
        'advanced_paid',
        'balance',
        'visa_issue_date',
        'visa_expiry_date',
        'status',
        'payment_status',
    ];


    public function passport()
    {
        return $this->belongsTo(Passport::class);
    }

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function visaCategory()
    {
        return $this->belongsTo(VisaCategory::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
    public function customer()
    {
        return $this->hasOneThrough(
            Customer::class,
            Passport::class,
            'id',          // Passport PK
            'id',          // Customer PK
            'passport_id', // VisaBooking FK
            'customer_id'  // Passport FK
        );
    }
}
