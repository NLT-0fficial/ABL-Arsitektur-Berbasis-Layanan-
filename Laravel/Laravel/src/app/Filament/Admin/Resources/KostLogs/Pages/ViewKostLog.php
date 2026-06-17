<?php

namespace App\Filament\Admin\Resources\KostLogs\Pages;

use App\Filament\Admin\Resources\KostLogs\KostLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKostLog extends ViewRecord
{
    protected static string $resource = KostLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
