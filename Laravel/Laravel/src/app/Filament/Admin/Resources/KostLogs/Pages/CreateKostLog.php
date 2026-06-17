<?php

namespace App\Filament\Admin\Resources\KostLogs\Pages;

use App\Filament\Admin\Resources\KostLogs\KostLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKostLog extends CreateRecord
{
    protected static string $resource = KostLogResource::class;
}
