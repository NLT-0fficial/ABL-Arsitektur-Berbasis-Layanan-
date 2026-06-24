<?php

namespace App\Models;

use Database\Factories\RoomFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Room extends Model
{
    /** @use HasFactory<RoomFactory> */
    use HasFactory;

    /**
     * Kategori kamar yang valid.
     */
    public const CATEGORIES = ['A', 'B', 'C'];

    protected $fillable = [
        'code',
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
            'is_occupied' => 'boolean',
            'rent_price' => 'decimal:2',
            'occupied_since' => 'date',
        ];
    }

    /**
     * Scope: hanya kamar yang terisi penghuni.
     */
    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('is_occupied', true);
    }

    /**
     * Scope: hanya kamar yang masih kosong.
     */
    public function scopeVacant(Builder $query): Builder
    {
        return $query->where('is_occupied', false);
    }

    /**
     * Scope: filter berdasarkan kategori kamar (A/B/C).
     */
    public function scopeCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }
}