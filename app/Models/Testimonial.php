<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    // Table name (optional if matches plural form)
    protected $table = 'testimonials';



    // Fillable fields for mass assignment
    protected $fillable = [
        'name',
        'image',
        'source',
        'rating',
        'message',
        'link',
        'postedate',
        'status',
    ];

    // Casts for automatic type conversion
    protected $casts = [
        'rating' => 'integer',
        'postedate' => 'date',
    ];
}
