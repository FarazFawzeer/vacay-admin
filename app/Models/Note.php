<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'user_id',
        'title',
        'note',
        'attachments', // 👈 added
    ];

    protected $casts = [
        'attachments' => 'array', // 👈 important
    ];

    /* =========================
     | Relationships
     ========================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
