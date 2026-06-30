<?php

namespace App\Models;

use Database\Factories\CheckInLogFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckInLog extends Model
{
    /** @use HasFactory<CheckInLogFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'scanned_by',
        'type',
        'scanned_at',
        'notes',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}