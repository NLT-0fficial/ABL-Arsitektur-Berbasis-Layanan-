<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\KostQrToken;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;

class QrCodeController extends Controller
{
    /**
     * Halaman QR Code untuk penyewa (tampilkan QR permanen).
     */
    public function show(int $id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->isKosong()) {
            abort(403, 'Kamar ini belum ada penyewa.');
        }

        // Ambil atau buat token permanen
        $qrToken = KostQrToken::getOrCreateFor($kost->id);

        return view('qr.show', compact('kost', 'qrToken'));
    }

    /**
     * Generate image QR Code (dipanggil sekali saat halaman load).
     */
    public function generate(int $id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->isKosong()) {
            return response()->json(['error' => 'Kamar kosong'], 403);
        }

        $qrToken = KostQrToken::getOrCreateFor($kost->id);

        $scanUrl = route('scan.verify') . '?token=' . $qrToken->token;

        $qrCode = new QrCode(
            data: $scanUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $result = (new PngWriter())->write($qrCode);

        return response($result->getString(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400'); // cache 1 hari, QR tidak berubah
    }

    /**
     * Daftar semua kamar terisi — halaman pilih kamar.
     */
    public function index()
    {
        $kosts = Kost::terisi()
            ->orderBy('lantai')
            ->orderBy('nomor_kamar')
            ->get();

        return view('qr.index', compact('kosts'));
    }
}