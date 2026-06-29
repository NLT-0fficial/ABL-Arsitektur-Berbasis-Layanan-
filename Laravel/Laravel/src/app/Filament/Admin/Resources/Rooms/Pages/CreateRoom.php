<?php

namespace App\Filament\Admin\Resources\Rooms\Pages;

use App\Filament\Admin\Resources\Rooms\RoomResource;
use App\Models\Room;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    public function mount(): void
    {
        parent::mount();

        // Ambil semua kamar yang masih kosong, urutkan per kode
        $vacantRooms = Room::where('is_occupied', false)
            ->orderBy('code')
            ->pluck('code')
            ->toArray();

        if (!empty($vacantRooms)) {
            $roomList = implode(', ', $vacantRooms);

            Notification::make()
                ->warning()
                ->title('Kamar Kosong Tersedia')
                ->body("Kamar berikut masih kosong: {$roomList}. Pertimbangkan untuk mengisi kamar tersebut terlebih dahulu.")
                ->persistent()
                ->send();
        }
    }
}