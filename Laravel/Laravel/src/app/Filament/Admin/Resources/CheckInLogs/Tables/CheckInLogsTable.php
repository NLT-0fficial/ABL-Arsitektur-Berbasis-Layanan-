<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\CheckInLogs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CheckInLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Penyewa')
                    ->searchable(),

                TextColumn::make('room.code')
                    ->label('Kamar')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'masuk',
                        'danger'  => 'keluar',
                    ]),

                TextColumn::make('scanned_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('admin.name')
                    ->label('Diproses oleh'),
            ])
            ->defaultSort('scanned_at', 'desc')
            ->filters([
                SelectFilter::make('type')
                    ->label('Status')
                    ->options([
                        'masuk'  => 'Masuk',
                        'keluar' => 'Keluar',
                    ]),
            ]);
    }
}