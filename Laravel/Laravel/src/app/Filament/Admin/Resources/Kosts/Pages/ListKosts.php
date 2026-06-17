<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Kosts\Pages;

use App\Filament\Admin\Resources\Kosts\KostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListKosts extends ListRecords
{
    protected static string $resource = KostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
