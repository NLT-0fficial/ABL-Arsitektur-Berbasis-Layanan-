<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Scanner Box --}}
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex flex-col gap-4">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200">Kamera Scanner</h3>
            <div id="qr-reader" class="w-full rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700"></div>
            <p class="text-xs text-gray-400 text-center">Arahkan kamera ke QR Code penyewa</p>
            <button
                onclick="stopScanner()"
                class="text-sm text-red-500 hover:underline text-center">
                ⏹ Stop Kamera
            </button>
        </div>

        {{-- Hasil Scan --}}
        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6 flex flex-col gap-4">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200">Hasil Scan</h3>

            @if ($scanStatus === 'success' && $scannedRoom)
                <div class="flex flex-col gap-3">
                    <div class="flex items-center gap-2 text-green-600 dark:text-green-400 font-semibold">
                        <x-heroicon-o-check-circle class="w-5 h-5"/>
                        QR Berhasil Dibaca
                    </div>

                    @foreach ([
                        'Kamar'         => $scannedRoom['code'],
                        'Kategori'      => 'Kategori ' . $scannedRoom['category'],
                        'Lantai'        => 'Lantai ' . $scannedRoom['floor'],
                        'Penghuni'      => $scannedRoom['tenant_name'],
                        'No. HP'        => $scannedRoom['tenant_phone'],
                        'Harga Sewa'    => 'Rp ' . $scannedRoom['rent_price'] . '/bulan',
                        'Tinggal Sejak' => $scannedRoom['occupied_since'],
                    ] as $label => $value)
                    <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $value }}</span>
                    </div>
                    @endforeach

                    <button
                        wire:click="resetScan"
                        class="mt-2 text-sm text-amber-600 hover:underline text-center">
                        🔄 Scan Lagi
                    </button>
                </div>

            @elseif ($scanStatus === 'error')
                <div class="flex items-center gap-2 text-red-500 font-semibold">
                    <x-heroicon-o-x-circle class="w-5 h-5"/>
                    QR tidak dikenali
                </div>
                <button
                    wire:click="resetScan"
                    class="text-sm text-amber-600 hover:underline">
                    🔄 Coba Lagi
                </button>

            @else
                <div class="flex flex-col items-center justify-center h-48 text-gray-400 gap-3">
                    <x-heroicon-o-qr-code class="w-16 h-16 opacity-30"/>
                    <p class="text-sm">Belum ada QR yang discan</p>
                </div>
            @endif
        </div>

    </div>

    {{-- html5-qrcode library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode = null;
        let isScannerRunning = false;

        function startScanner() {
            // Cegah double-start kalau livewire:navigated nembak 2x
            if (isScannerRunning) return;

            html5QrCode = new Html5Qrcode("qr-reader");
            isScannerRunning = true;

            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 280, height: 280 },
                    aspectRatio: 1.7777778, // 16:9, biar sesuai rasio 1080p (1920x1080)

                    // Minta resolusi tinggi ke browser. Webcam 1080p kamu
                    // baru benar2 kepake kalau constraint ini di-set,
                    // kalau tidak, html5-qrcode sering ambil resolusi rendah secara default.
                    videoConstraints: {
                        width: { min: 1280, ideal: 1920, max: 1920 },
                        height: { min: 720, ideal: 1080, max: 1080 },
                        facingMode: "environment",

                        // Minta browser nyalain continuous autofocus kalau didukung device
                        advanced: [{ focusMode: "continuous" }]
                    }
                },
                (decodedText) => {
                    stopScanner();
                    @this.call('processScan', decodedText);
                },
                () => {
                    // error per-frame (QR belum kebaca), biarin aja, jangan spam console
                }
            ).catch(err => {
                console.error('Gagal start scanner:', err);
                isScannerRunning = false;
            });
        }

        function stopScanner() {
            if (html5QrCode && isScannerRunning) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                    isScannerRunning = false;
                }).catch(() => {
                    isScannerRunning = false;
                });
            }
        }

        document.addEventListener('DOMContentLoaded', () => startScanner());
        document.addEventListener('livewire:navigated', () => startScanner());

        // Stop kamera otomatis kalau pindah halaman, biar gak nyala terus di background
        document.addEventListener('livewire:navigating', () => stopScanner());
    </script>
</x-filament-panels::page>