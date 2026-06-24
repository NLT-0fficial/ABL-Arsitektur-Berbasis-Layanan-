<?php

namespace App\Filament\Admin\Resources\Rooms;

use App\Filament\Admin\Resources\Rooms\Pages\CreateRoom;
use App\Filament\Admin\Resources\Rooms\Pages\EditRoom;
use App\Filament\Admin\Resources\Rooms\Pages\ListRooms;
use App\Models\Room;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use UnitEnum;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Kost';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationLabel = 'Kamar Kost';

    protected static ?string $modelLabel = 'Kamar';

    protected static ?string $pluralModelLabel = 'Kamar';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kamar')
                    ->columns(2)
                    ->components([
                        TextInput::make('code')
                            ->label('Kode Kamar')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Contoh: A-101'),

                        Select::make('category')
                            ->label('Kategori')
                            ->options([
                                'A' => 'Kategori A',
                                'B' => 'Kategori B',
                                'C' => 'Kategori C',
                            ])
                            ->required()
                            ->native(false),

                        TextInput::make('floor')
                            ->label('Lantai')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('rent_price')
                            ->label('Harga Sewa / Bulan')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ]),

                Section::make('Status & Penghuni')
                    ->columns(2)
                    ->components([
                        Toggle::make('is_occupied')
                            ->label('Terisi Penghuni')
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('tenant_name')
                            ->label('Nama Penghuni')
                            ->maxLength(255)
                            ->visible(fn ($get) => (bool) $get('is_occupied'))
                            ->required(fn ($get) => (bool) $get('is_occupied')),

                        TextInput::make('tenant_phone')
                            ->label('No. HP Penghuni')
                            ->tel()
                            ->maxLength(20)
                            ->visible(fn ($get) => (bool) $get('is_occupied')),

                        DatePicker::make('occupied_since')
                            ->label('Mulai Menghuni')
                            ->visible(fn ($get) => (bool) $get('is_occupied')),

                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode Kamar')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'warning',
                        'C' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                IconColumn::make('is_occupied')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-user')
                    ->falseIcon('heroicon-o-home')
                    ->trueColor('danger')
                    ->falseColor('success'),

                TextColumn::make('tenant_name')
                    ->label('Penghuni')
                    ->searchable()
                    ->placeholder('— Kosong —'),

                TextColumn::make('tenant_phone')
                    ->label('No. HP')
                    ->placeholder('-'),

                TextColumn::make('rent_price')
                    ->label('Harga Sewa')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('occupied_since')
                    ->label('Sejak')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'A' => 'Kategori A',
                        'B' => 'Kategori B',
                        'C' => 'Kategori C',
                    ]),

                TernaryFilter::make('is_occupied')
                    ->label('Status Penghuni')
                    ->placeholder('Semua')
                    ->trueLabel('Terisi')
                    ->falseLabel('Kosong'),
            ])
            ->defaultSort('code');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRooms::route('/'),
            'create' => CreateRoom::route('/create'),
            'edit' => EditRoom::route('/{record}/edit'),
        ];
    }
}