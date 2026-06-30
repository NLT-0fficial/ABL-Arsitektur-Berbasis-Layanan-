<?php

namespace App\Filament\Admin\Resources\CheckInLogs\Pages;

use App\Filament\Admin\Resources\CheckInLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCheckInLog extends ViewRecord
{
    protected static string $resource = CheckInLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
