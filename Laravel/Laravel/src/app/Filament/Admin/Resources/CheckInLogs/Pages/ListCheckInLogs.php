<?php

namespace App\Filament\Admin\Resources\CheckInLogs\Pages;

use App\Filament\Admin\Resources\CheckInLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCheckInLogs extends ListRecords
{
    protected static string $resource = CheckInLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
