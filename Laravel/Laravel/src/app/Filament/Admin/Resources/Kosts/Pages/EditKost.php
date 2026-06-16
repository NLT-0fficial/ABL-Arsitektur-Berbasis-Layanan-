<?php

namespace App\Filament\Admin\Resources\Kosts\Pages;

use App\Filament\Admin\Resources\Kosts\KostResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKost extends EditRecord
{
    protected static string $resource = KostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
