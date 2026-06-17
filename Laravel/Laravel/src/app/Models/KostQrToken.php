<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class KostQrToken extends Model
{
    use HasFactory;

    protected $table = 'kost_qr_tokens';

    protected $fillable = [
        'kost_id',
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class);
    }

    // ============ HELPERS ============

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Generate token baru untuk kost tertentu.
     * Hapus token lama, buat yang baru dengan expire 30 detik.
     */
    public static function generateFor(int $kostId): self
    {
        // Hapus token lama untuk kamar ini
        self::where('kost_id', $kostId)->delete();

        return self::create([
            'kost_id'    => $kostId,
            'token'      => Str::random(32),
            'expires_at' => now()->addSeconds(30),
        ]);
    }

    /**
     * Cari token yang masih valid.
     */
    public static function findValid(string $token): ?self
    {
        return self::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();
    }
}