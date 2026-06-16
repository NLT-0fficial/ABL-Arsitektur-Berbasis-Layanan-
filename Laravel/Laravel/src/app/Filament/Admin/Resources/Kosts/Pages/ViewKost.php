<?php

namespace App\Filament\Admin\Resources\Kosts\Pages;

use App\Filament\Admin\Resources\Kosts\KostResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKost extends ViewRecord
{
    protected static string $resource = KostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
