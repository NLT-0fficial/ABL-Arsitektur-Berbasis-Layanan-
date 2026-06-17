<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Kosts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class KostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('lantai'),
                TextEntry::make('nomor_kamar'),
                TextEntry::make('nama_penyewa')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
