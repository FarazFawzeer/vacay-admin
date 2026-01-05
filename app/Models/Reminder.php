<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $table = 'reminders';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'attachments', // 👈 added
        'due_date',
        'remind_before_minutes',
        'status',
        'is_notified',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_notified' => 'boolean',
        'attachments' => 'array', // 👈 VERY IMPORTANT
    ];


    /* =========================
     | Relationships
     ========================= */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notifications()
    {
        return $this->hasMany(AdminNotification::class, 'reference_id')
            ->where('type', 'reminder');
    }

    /* =========================
     | Scopes (Very Useful)
     ========================= */

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', Carbon::today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
            ->where('status', 'pending');
    }

    // App\Models\Reminder.php
    public function getComputedStatusAttribute()
    {
        if ($this->status === 'completed') {
            return 'completed';
        }

        if ($this->due_date < now()) {
            return 'overdue';
        }

        return 'pending';
    }
}
