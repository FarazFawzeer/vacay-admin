<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'highlight_places',
        'images',
        'description'
    ];

    public function detailItinerary()
    {
        return $this->belongsTo(DetailItinerary::class, 'itinerary_id');
    }
}
