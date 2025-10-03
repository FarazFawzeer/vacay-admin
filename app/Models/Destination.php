<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key_attributes', 'program_points', 'hotels', 'themes']; // Add 'hotels' to fillable

    protected $casts = [
        'key_attributes' => 'array',
        'program_points' => 'array',
        'hotels' => 'array', // Cast 'hotels' column to an array
        'themes' => 'array',
    ];

    public function highlights()
    {
        return $this->hasMany(DestinationHighlight::class, 'destination_id');
    }
}
