<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\KostQrToken;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    /**
     * Tampilkan halaman QR Code untuk HP penghuni.
     */
    public function show(int $id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->isKosong()) {
            abort(403, 'Kamar ini belum ada penyewa.');
        }

        return view('qr.show', compact('kost'));
    }

    /**
     * Generate QR Code image (dipanggil via AJAX tiap 30 detik).
     */
    public function generate(int $id)
    {
        $kost = Kost::findOrFail($id);

        if ($kost->isKosong()) {
            return response()->json(['error' => 'Kamar kosong'], 403);
        }

        // Generate token baru
        $qrToken = KostQrToken::generateFor($kost->id);

        // Buat URL yang akan di-encode ke QR
        $scanUrl = route('scan.verify') . '?token=' . $qrToken->token;

        // Generate QR Code
        $qrCode = new QrCode(
            data: $scanUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            foregroundColor: new Color(0, 0, 0),
            backgroundColor: new Color(255, 255, 255),
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return response($result->getString(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Expires', '0');
    }

    /**
     * Tampilkan semua kamar yang terisi (untuk pilih kamar di HP).
     */
    public function index()
    {
        $kosts = Kost::terisi()->orderBy('lantai')->orderBy('nomor_kamar')->get();
        return view('qr.index', compact('kosts'));
    }
}