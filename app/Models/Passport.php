<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'first_name',
        'second_name',
        'passport_number',
        'passport_expire_date',
        'nationality',
        'dob',
        'sex',
        'issue_date',
        'id_number',
        'id_photo',
    ];

    protected $casts = [
        'id_photo' => 'array', // cast JSON to array
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
