<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageInclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'inclusion_id',
        'heading',
        'points',
        'note',
        'type',
    ];

    protected $casts = [
        'points' => 'array',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function inclusion()
    {
        return $this->belongsTo(Inclusion::class, 'inclusion_id');
    }
}
