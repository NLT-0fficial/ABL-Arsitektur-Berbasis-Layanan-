<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    public const CATEGORIES = ['A', 'B', 'C'];

    protected $fillable = [
        'code',
        'qr_token',
        'qr_image_url',
        'category',
        'floor',
        'rent_price',
        'is_occupied',
        'tenant_name',
        'tenant_phone',
        'occupied_since',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_occupied'    => 'boolean',
            'rent_price'     => 'decimal:2',
            'occupied_since' => 'date',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($room) {
            $token = Str::uuid()->toString();

            // QR hanya encode token, bukan full URL
            // supaya tidak bergantung pada IP/domain
            $room->qr_token     = $token;
            $room->qr_image_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='
                                  . urlencode($token);
        });
    }

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function tenant(): HasOne
    {
        return $this->hasOne(User::class);
    }

    // -------------------------------------------------------
    // Scopes
    // -------------------------------------------------------

    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('is_occupied', true);
    }

    public function scopeVacant(Builder $query): Builder
    {
        return $query->where('is_occupied', false);
    }

    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
    public function checkInLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CheckInLog::class);
    }
}