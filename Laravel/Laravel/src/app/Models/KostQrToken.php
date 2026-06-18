<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KostQrToken extends Model
{
    protected $fillable = [
        'kost_id',
        'token',
        'expires_at',
        'used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public static function getOrCreateFor(int $kostId): self
    {
        $token = static::where('kost_id', $kostId)
            ->whereNull('used_at')
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (! $token) {
            $token = static::create([
                'kost_id'    => $kostId,
                'token'      => (string) Str::uuid(),
                'expires_at' => now()->addYear(),
            ]);
        }

        return $token;
    }
}