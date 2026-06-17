<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class KostLog extends Model
{
    use HasFactory;

    protected $table = 'kost_logs';

    protected $fillable = [
        'kost_id',
        'nama_penyewa',
        'jenis',
        'token_used',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'jenis'      => 'string',
    ];

    // ============ RELATIONS ============

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class);
    }

    // ============ SCOPES ============

    public function scopeMasuk(Builder $query): Builder
    {
        return $query->where('jenis', 'masuk');
    }

    public function scopeKeluar(Builder $query): Builder
    {
        return $query->where('jenis', 'keluar');
    }

    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('scanned_at', today());
    }

    public function scopeByKost(Builder $query, int $kostId): Builder
    {
        return $query->where('kost_id', $kostId);
    }

    // ============ HELPERS ============

    public function getJenisTextAttribute(): string
    {
        return $this->jenis === 'masuk' ? '🟢 Masuk' : '🔴 Keluar';
    }

    /**
     * Tentukan jenis log berikutnya berdasarkan log terakhir.
     * Kalau terakhir masuk → berikutnya keluar, dan sebaliknya.
     */
    public static function jenisBerikutnya(int $kostId): string
    {
        $last = self::where('kost_id', $kostId)
            ->latest('scanned_at')
            ->first();

        if (!$last) return 'masuk'; // Default pertama kali = masuk

        return $last->jenis === 'masuk' ? 'keluar' : 'masuk';
    }
}