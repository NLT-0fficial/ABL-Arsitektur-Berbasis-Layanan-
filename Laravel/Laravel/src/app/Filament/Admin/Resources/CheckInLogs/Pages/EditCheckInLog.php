<?php

namespace App\Filament\Admin\Resources\CheckInLogs\Pages;

use App\Filament\Admin\Resources\CheckInLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckInLog extends EditRecord
{
    protected static string $resource = CheckInLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
