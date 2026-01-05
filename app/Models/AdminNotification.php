<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications';

    protected $fillable = [
        'user_id',
        'type',
        'reference_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /* =========================
     | Relationships
     ========================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reminder()
    {
        return $this->belongsTo(Reminder::class, 'reference_id');
    }

    /* =========================
     | Scopes
     ========================= */

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
