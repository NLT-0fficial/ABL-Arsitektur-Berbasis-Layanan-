<x-filament-panels::page
    x-data
    x-init="
        const wire = $wire;

        window.processQrScan = async (decodedText) => {
            try {
                console.log('Mengirim QR ke Livewire:', decodedText);

                await wire.processScan(decodedText);

                console.log('Livewire processScan selesai.');

            } catch (e) {

                console.error('Livewire Error:', e);

                throw e;
            }
        };
    "
>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ========================= --}}
        {{-- CAMERA --}}
        {{-- ========================= --}}

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6">

            <h3 class="font-semibold mb-4">
                Kamera Scanner
            </h3>

            <div
                id="qr-reader"
                class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            </div>

            <p class="text-xs text-center text-gray-400 mt-3">
                Arahkan kamera ke QR Code penyewa
            </p>

            <button
                type="button"
                onclick="stopScanner()"
                class="mt-3 text-sm text-red-600 hover:underline">

                Stop Kamera

            </button>

        </div>

        {{-- ========================= --}}
        {{-- HASIL --}}
        {{-- ========================= --}}

        <div class="rounded-xl bg-white dark:bg-gray-800 shadow p-6">

            <h3 class="font-semibold mb-4">
                Hasil Scan
            </h3>

            @if($scanStatus === 'success' && $scannedRoom)

                <div class="space-y-3">

                    <div class="text-green-600 font-semibold">
                        ✅ QR Berhasil Dibaca
                    </div>

                    @foreach([
                        'Kamar' => $scannedRoom['code'],
                        'Kategori' => 'Kategori '.$scannedRoom['category'],
                        'Lantai' => 'Lantai '.$scannedRoom['floor'],
                        'Penghuni' => $scannedRoom['tenant_name'],
                        'No. HP' => $scannedRoom['tenant_phone'],
                        'Harga' => 'Rp '.$scannedRoom['rent_price'].' / bulan',
                        'Tinggal Sejak' => $scannedRoom['occupied_since'],
                        'Status' => ucfirst($scannedRoom['action']),
                    ] as $label => $value)

                        <div class="flex justify-between border-b pb-2">

                            <span class="text-sm text-gray-500">
                                {{ $label }}
                            </span>

                            <span class="font-semibold">
                                {{ $value }}
                            </span>

                        </div>

                    @endforeach

                    <button
                        wire:click="resetScan"
                        class="text-amber-600 hover:underline">

                        Scan Lagi

                    </button>

                </div>

            @elseif($scanStatus === 'error')

                <div class="text-red-600 font-semibold">
                    ❌ QR tidak dikenali
                </div>

                <button
                    wire:click="resetScan"
                    class="text-amber-600 hover:underline">

                    Coba Lagi

                </button>

            @else

                <div class="h-56 flex items-center justify-center text-gray-400">

                    Belum ada QR yang discan

                </div>

            @endif

        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>

        let html5QrCode = null;
        let scannerRunning = false;

        async function callProcessScan(decodedText) {

            try {

                console.log("QR :", decodedText);

                await window.processQrScan(decodedText);

                console.log("processScan berhasil");

            } catch (error) {

                console.error(error);

                alert(
                    "Gagal mengirim hasil scan ke Livewire.\n\n"
                    + (error?.message ?? error)
                );

            }

        }

        async function startScanner() {

            if (scannerRunning)
                return;

            const element = document.getElementById("qr-reader");

            if (!element)
                return;

            html5QrCode = new Html5Qrcode("qr-reader");

            scannerRunning = true;

            try {

                await html5QrCode.start(

                    {
                        facingMode: "environment"
                    },

                    {
                        fps: 10,

                        qrbox: {
                            width: 280,
                            height: 280
                        },

                        aspectRatio: 1.777,

                        videoConstraints: {

                            width: {
                                ideal: 1920
                            },

                            height: {
                                ideal: 1080
                            },

                            facingMode: "environment",

                            advanced: [{
                                focusMode: "continuous"
                            }]
                        }

                    },

                    async function(decodedText) {

                        console.log("QR ditemukan");

                        stopScanner();

                        await callProcessScan(decodedText);

                    },

                    function(){}

                );

            } catch(e){

                console.error(e);

                scannerRunning = false;

            }

        }

        function stopScanner() {

            if (!html5QrCode || !scannerRunning)
                return;

            html5QrCode.stop()

                .then(() => {

                    html5QrCode.clear();

                    scannerRunning = false;

                })

                .catch((e) => {

                    console.error(e);

                    scannerRunning = false;

                });

        }

        document.addEventListener("DOMContentLoaded", () => {

            setTimeout(startScanner,500);

        });

        document.addEventListener("livewire:navigated", () => {

            setTimeout(startScanner,500);

        });

        document.addEventListener("livewire:navigating", () => {

            stopScanner();

        });

    </script>

</x-filament-panels::page>