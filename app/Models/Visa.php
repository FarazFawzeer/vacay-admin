<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    use HasFactory;

    protected $table = 'visas';

    protected $fillable = [
        'from_country',
        'to_country',
        'visa_type',
        'custom_visa_type',
        'documents', // JSON
        'agent_id',
        'auth_id',
        'checklist',
        'status',
        'note',
    ];

    protected $casts = [
        'documents' => 'array', // automatically casts JSON to array
        'checklist' => 'array',
    ];

    // One Visa has many Visa Categories
    public function categories()
    {
        return $this->hasMany(VisaCategory::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function agents()
    {
        return $this->belongsToMany(
            Agent::class,
            'agent_visa',
            'visa_id',
            'agent_id'
        );
    }

    // Visa belongs to a User (creator)
    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }
}
