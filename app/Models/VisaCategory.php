<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'visa_id',
        'visa_type',
        'state',
        'days',
        'visa_validity',
        'how_many_days',
        'price',
        'currency',
        'processing_time',
    ];

    // Each category belongs to a Visa
    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }
}
