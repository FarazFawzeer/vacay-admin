<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrivingPermitRequest extends Model
{
    //

    protected $fillable = [
        'guest_name',
        'email',
        'license_no',
        'whatsapp',
        'license_front',
        'license_back',
        'selfie',
        'collection_method',
        'status',
    ];
}
