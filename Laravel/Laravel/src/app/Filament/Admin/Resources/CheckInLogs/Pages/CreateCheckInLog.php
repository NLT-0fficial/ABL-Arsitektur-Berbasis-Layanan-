<?php

namespace App\Filament\Admin\Resources\CheckInLogs\Pages;

use App\Filament\Admin\Resources\CheckInLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCheckInLog extends CreateRecord
{
    protected static string $resource = CheckInLogResource::class;
}
