<x-filament-panels::page>
    <div class="grid grid-cols-1 gap-6">

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                Selamat datang, {{ $user->name }} 👋
            </h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">
                Berikut informasi kamar kost Anda.
            </p>
        </div>

        @if ($room)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-amber-100 dark:bg-amber-900">
                        <x-heroicon-o-home class="w-6 h-6 text-amber-600 dark:text-amber-300"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kamar</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $room->code }}</p>
                    </div>
                </div>

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    @php
                        // Icon & warna berbeda untuk tiap kategori kamar
                        $categoryStyles = [
                            'A' => ['icon' => 'heroicon-o-star', 'bg' => 'bg-emerald-100 dark:bg-emerald-900', 'text' => 'text-emerald-600 dark:text-emerald-300'],
                            'B' => ['icon' => 'heroicon-o-sparkles', 'bg' => 'bg-blue-100 dark:bg-blue-900', 'text' => 'text-blue-600 dark:text-blue-300'],
                            'C' => ['icon' => 'heroicon-o-trophy', 'bg' => 'bg-purple-100 dark:bg-purple-900', 'text' => 'text-purple-600 dark:text-purple-300'],
                        ];
                        $style = $categoryStyles[$room->category] ?? [
                            'icon' => 'heroicon-o-tag', 'bg' => 'bg-gray-100 dark:bg-gray-900', 'text' => 'text-gray-600 dark:text-gray-300',
                        ];
                    @endphp
                    <div class="p-3 rounded-full {{ $style['bg'] }}">
                        <x-dynamic-component :component="$style['icon']" class="w-6 h-6 {{ $style['text'] }}"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Kategori</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">Kategori {{ $room->category }}</p>
                    </div>
                </div>

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <x-heroicon-o-building-office class="w-6 h-6 text-purple-600 dark:text-purple-300"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lantai</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">Lantai {{ $room->floor }}</p>
                    </div>
                </div>

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <x-heroicon-o-phone class="w-6 h-6 text-green-600 dark:text-green-300"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No. HP</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $room->tenant_phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-rose-100 dark:bg-rose-900">
                        <x-heroicon-o-banknotes class="w-6 h-6 text-rose-600 dark:text-rose-300"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Harga Sewa</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">
                            Rp {{ number_format($room->rent_price, 0, ',', '.') }}/bulan
                        </p>
                    </div>
                </div>

                <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex items-center gap-4">
                    <div class="p-3 rounded-full bg-teal-100 dark:bg-teal-900">
                        <x-heroicon-o-calendar class="w-6 h-6 text-teal-600 dark:text-teal-300"/>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tinggal Sejak</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">
                            {{ $room->occupied_since?->translatedFormat('d M Y') ?? '-' }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex flex-col items-center justify-center gap-4">
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">QR Code Kamar</p>

                @if (! $qrImageUrl)
                    <p class="text-gray-400 text-sm text-center">
                        Pilih jenis aktivitas untuk membuat QR Code
                    </p>

                    <div class="flex gap-3">
                        <button
                            wire:click="generateQr('masuk')"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-semibold transition"
                        >
                            Masuk
                        </button>
                        <button
                            wire:click="generateQr('keluar')"
                            type="button"
                            class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold transition"
                        >
                            Keluar
                        </button>
                    </div>
                @else
                    <p class="text-xs font-semibold uppercase tracking-wide
                        {{ $qrAction === 'masuk' ? 'text-green-600' : 'text-rose-600' }}">
                        QR untuk: {{ $qrAction === 'masuk' ? 'Masuk' : 'Keluar' }}
                    </p>

                    <img src="{{ $qrImageUrl }}"
                         alt="QR Code {{ $room->code }} - {{ $qrAction }}"
                         class="w-48 h-48 rounded-lg border border-gray-200 dark:border-gray-700"/>

                    <p class="text-xs text-gray-400 text-center">Tunjukkan QR ini ke admin saat {{ $qrAction }}</p>

                    <a href="{{ $qrImageUrl }}"
                       download="qr-{{ $room->code }}-{{ $qrAction }}.png"
                       class="text-xs text-amber-600 hover:underline">
                        ⬇ Download QR
                    </a>

                    <button
                        wire:click="resetQr"
                        type="button"
                        class="text-xs text-gray-400 hover:underline mt-1"
                    >
                        Buat ulang / ganti aktivitas
                    </button>
                @endif
            </div>

        </div>
        @else
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 text-center text-gray-500">
            Anda belum memiliki kamar yang terdaftar.
        </div>
        @endif

    </div>
</x-filament-panels::page>