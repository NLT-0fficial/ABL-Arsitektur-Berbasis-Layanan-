<?php

namespace App\Filament\Admin\Resources\Kosts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('lantai')
                    ->required(),
                TextInput::make('nomor_kamar')
                    ->required(),
                TextInput::make('nama_penyewa')
                    ->default(null),
                Select::make('status')
                    ->options(['kosong' => 'Kosong', 'terisi' => 'Terisi'])
                    ->default('kosong')
                    ->required(),
            ]);
    }
}
