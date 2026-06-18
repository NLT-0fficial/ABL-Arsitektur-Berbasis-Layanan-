<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\KostQrToken;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PenyewaController extends Controller
{
    /**
     * Dashboard penyewa — tampilkan info kamar + QR milik sendiri.
     */
    public function dashboard(Request $request): \Illuminate\View\View
    {
        $user = $request->user();
        $kost = $user->kost;

        // Kalau user belum punya kamar atau kamar berstatus kosong
        if (! $kost || $kost->isKosong()) {
            return view('penyewa.dashboard', [
                'kost'    => null,
                'qrToken' => null,
            ]);
        }

        $qrToken = KostQrToken::getOrCreateFor($kost->id);

        return view('penyewa.dashboard', compact('kost', 'qrToken'));
    }

    /**
     * Generate gambar QR Code untuk penyewa yang sedang login.
     * Endpoint ini dipanggil oleh <img src="..."> di blade.
     */
    public function generateQr(Request $request): Response
    {
        $user = $request->user();
        $kost = $user->kost;

        if (! $kost || $kost->isKosong()) {
            abort(403, 'Kamu belum memiliki kamar yang terdaftar.');
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
            ->header('Cache-Control', 'public, max-age=86400'); // cache 1 hari
    }
}