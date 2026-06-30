<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CheckInLogs\Pages\CreateCheckInLog;
use App\Filament\Admin\Resources\CheckInLogs\Pages\EditCheckInLog;
use App\Filament\Admin\Resources\CheckInLogs\Pages\ListCheckInLogs;
use App\Filament\Admin\Resources\CheckInLogs\Pages\ViewCheckInLog;
use App\Filament\Admin\Resources\CheckInLogs\Tables\CheckInLogsTable;
use App\Models\CheckInLog;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CheckInLogResource extends Resource
{
    protected static ?string $model = CheckInLog::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen';

    protected static ?string $pluralModelLabel = 'Log Scan QR';

    protected static ?int $navigationSort = 5;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return Heroicon::QrCode;
    }

    public static function table(Table $table): Table
    {
        return CheckInLogsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCheckInLogs::route('/'),
            'create' => CreateCheckInLog::route('/create'),
            'view'   => ViewCheckInLog::route('/{record}'),
            'edit'   => EditCheckInLog::route('/{record}/edit'),
        ];
    }
}