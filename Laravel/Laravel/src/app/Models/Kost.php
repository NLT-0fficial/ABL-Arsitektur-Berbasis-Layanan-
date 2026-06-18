<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class Kost extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kosts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',      // <-- TAMBAHAN
        'lantai',
        'nomor_kamar',
        'nama_penyewa',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'nama_kamar_lengkap',
        'status_text',
    ];

    // ============ RELATIONS ============

    /**
     * Get the user (penyewa) associated with this kost.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR token associated with this kost.
     */
    public function qrToken(): HasOne
    {
        return $this->hasOne(KostQrToken::class);
    }

    // ============ STATIC HELPERS ============

    /**
     * Get all penyewa names.
     */
    public static function getAllPenyewa(): array
    {
        return self::terisi()->pluck('nama_penyewa')->toArray();
    }

    /**
     * Get statistic per lantai.
     */
    public static function getStatistik(): array
    {
        $lantai = ['A', 'B', 'C'];
        $statistik = [];

        foreach ($lantai as $l) {
            $total = self::byLantai($l)->count();
            $terisi = self::byLantai($l)->terisi()->count();
            $kosong = self::byLantai($l)->kosong()->count();

            $statistik[$l] = [
                'total' => $total,
                'terisi' => $terisi,
                'kosong' => $kosong,
                'persentase_terisi' => $total > 0 ? round(($terisi / $total) * 100, 2) : 0,
            ];
        }

        return $statistik;
    }

    /**
     * Get total statistic.
     */
    public static function getTotalStatistik(): array
    {
        $total = self::count();
        $terisi = self::terisi()->count();
        $kosong = self::kosong()->count();

        return [
            'total_kamar' => $total,
            'total_terisi' => $terisi,
            'total_kosong' => $kosong,
            'persentase_terisi' => $total > 0 ? round(($terisi / $total) * 100, 2) : 0,
        ];
    }

    // ============ SCOPES ============

    /**
     * Scope a query to only include kost by lantai.
     */
    public function scopeByLantai(Builder $query, string $lantai): Builder
    {
        return $query->where('lantai', mb_strtoupper($lantai));
    }

    /**
     * Scope a query to only include kost that are terisi.
     */
    public function scopeTerisi(Builder $query): Builder
    {
        return $query->where('status', 'terisi');
    }

    /**
     * Scope a query to only include kost that are kosong.
     */
    public function scopeKosong(Builder $query): Builder
    {
        return $query->where('status', 'kosong');
    }

    /**
     * Scope a query to search by nama penyewa.
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where('nama_penyewa', 'LIKE', "%{$search}%");
    }

    // ============ ACCESSORS ============

    /**
     * Get the nama kamar lengkap attribute.
     */
    public function getNamaKamarLengkapAttribute(): string
    {
        return $this->lantai.$this->nomor_kamar;
    }

    /**
     * Get the status text attribute.
     */
    public function getStatusTextAttribute(): string
    {
        return $this->status === 'terisi' ? 'Terisi' : 'Kosong';
    }

    /**
     * Get the lantai text attribute.
     */
    public function getLantaiTextAttribute(): string
    {
        $map = [
            'A' => 'Lantai 1',
            'B' => 'Lantai 2',
            'C' => 'Lantai 3',
        ];

        return $map[$this->lantai] ?? $this->lantai;
    }

    // ============ MUTATORS ============

    /**
     * Set the lantai attribute.
     */
    public function setLantaiAttribute(string $value): void
    {
        $this->attributes['lantai'] = mb_strtoupper($value);
    }

    /**
     * Set the nomor_kamar attribute.
     */
    public function setNomorKamarAttribute(string $value): void
    {
        $this->attributes['nomor_kamar'] = mb_strtoupper($value);
    }

    // ============ HELPER METHODS ============

    /**
     * Check if kamar is terisi.
     */
    public function isTerisi(): bool
    {
        return $this->status === 'terisi';
    }

    /**
     * Check if kamar is kosong.
     */
    public function isKosong(): bool
    {
        return $this->status === 'kosong';
    }
}