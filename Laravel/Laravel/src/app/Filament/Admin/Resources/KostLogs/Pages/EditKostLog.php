<?php

namespace App\Filament\Admin\Resources\KostLogs\Pages;

use App\Filament\Admin\Resources\KostLogs\KostLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKostLog extends EditRecord
{
    protected static string $resource = KostLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
