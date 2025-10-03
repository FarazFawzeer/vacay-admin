<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'day',
        'city',
        'theme',
        'key_attributes',
        'images',
    ];

  
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
