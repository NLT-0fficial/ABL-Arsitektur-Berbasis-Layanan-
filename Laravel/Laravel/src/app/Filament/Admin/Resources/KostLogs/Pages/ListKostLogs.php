<?php

namespace App\Filament\Admin\Resources\KostLogs\Pages;

use App\Filament\Admin\Resources\KostLogs\KostLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKostLogs extends ListRecords
{
    protected static string $resource = KostLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
