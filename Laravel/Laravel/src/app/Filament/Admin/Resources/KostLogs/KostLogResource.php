<?php

namespace App\Filament\Admin\Resources\KostLogs;

use App\Filament\Admin\Resources\KostLogs\Pages\ListKostLogs;
use App\Filament\Admin\Resources\KostLogs\Pages\ViewKostLog;
use App\Models\KostLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class KostLogResource extends Resource
{
    protected static ?string $model = KostLog::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $navigationLabel = 'Log Keluar Masuk';
    protected static ?string $modelLabel = 'Log';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scanned_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),

                TextColumn::make('kost.nama_kamar_lengkap')
                    ->label('Kamar')
                    ->sortable(),

                TextColumn::make('nama_penyewa')
                    ->label('Nama Penyewa')
                    ->searchable(),

                TextColumn::make('jenis')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk'  => 'success',
                        'keluar' => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('jenis')
                    ->label('Filter Status')
                    ->options([
                        'masuk'  => 'Masuk',
                        'keluar' => 'Keluar',
                    ]),
            ])
            ->defaultSort('scanned_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKostLogs::route('/'),
            'view'  => ViewKostLog::route('/{record}'),
        ];
    }
}