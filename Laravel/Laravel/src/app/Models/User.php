<?php

declare(strict_types=1);

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'room_id',
        'avatar_url',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // -------------------------------------------------------
    // Relasi
    // -------------------------------------------------------

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // -------------------------------------------------------
    // Filament
    // -------------------------------------------------------

    public function getFilamentAvatarUrl(): string
    {
        if ($this->avatar_url) {
            return asset('storage/' . $this->avatar_url);
        }

        $hash = md5(mb_strtolower(mb_trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $hash . '?d=mp&r=g&s=250';
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->hasRole('super_admin');
        }

        if ($panel->getId() === 'tenant') {
            return $this->hasRole('tenant');
        }

        return false;
    }

    // -------------------------------------------------------
    // Casts
    // -------------------------------------------------------

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function checkInLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CheckInLog::class, 'user_id');
    }
}