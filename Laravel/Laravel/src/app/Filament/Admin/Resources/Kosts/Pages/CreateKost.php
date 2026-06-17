<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\Kosts\Pages;

use App\Filament\Admin\Resources\Kosts\KostResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateKost extends CreateRecord
{
    protected static string $resource = KostResource::class;
}
