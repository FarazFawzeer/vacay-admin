<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestinationHighlight extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'place_name', 'description', 'image'];

    /**
     * Define the relationship with the Destination model.
     * A destination highlight belongs to a destination.
     */
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
}
