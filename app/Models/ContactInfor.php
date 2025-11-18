<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInfor extends Model
{
    use HasFactory;

    // If the table name doesn't follow Laravel's plural convention, define it:
    protected $table = 'contact_infor';

    // Allow mass assignment for these fields
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'service',
        'message',
        'status',
    ];
}
