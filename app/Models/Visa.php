<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;

    protected $table = 'visa'; // specify table name

    protected $fillable = [
        'country',
        'visa_type',
        'visa_details',
        'documents',
        'agent_id',
        'note',
          'user_id'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agent_visa');
    }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
