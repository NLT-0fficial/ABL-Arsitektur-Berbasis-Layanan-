<?php

namespace App\Filament\Admin\Resources\Kosts;

use App\Filament\Admin\Resources\Kosts\Pages\CreateKost;
use App\Filament\Admin\Resources\Kosts\Pages\EditKost;
use App\Filament\Admin\Resources\Kosts\Pages\ListKosts;
use App\Filament\Admin\Resources\Kosts\Pages\ViewKost;
use App\Filament\Admin\Resources\Kosts\Schemas\KostForm;
use App\Filament\Admin\Resources\Kosts\Schemas\KostInfolist;
use App\Filament\Admin\Resources\Kosts\Tables\KostsTable;
use App\Models\Kost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KostResource extends Resource
{
    protected static ?string $model = Kost::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nomor_kamar';

    public static function form(Schema $schema): Schema
    {
        return KostForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KostInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KostsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKosts::route('/'),
            'create' => CreateKost::route('/create'),
            'view' => ViewKost::route('/{record}'),
            'edit' => EditKost::route('/{record}/edit'),
        ];
    }
}
