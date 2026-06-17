<?php

namespace App\Http\Controllers;

use App\Models\KostLog;
use App\Models\KostQrToken;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    /**
     * Tampilkan halaman scanner webcam (di PC).
     */
    public function index()
    {
        $logsHariIni = KostLog::with('kost')
            ->hariIni()
            ->latest('scanned_at')
            ->take(20)
            ->get();

        return view('scan.index', compact('logsHariIni'));
    }

    /**
     * Verifikasi token dari QR yang di-scan.
     */
    public function verify(Request $request)
    {
        $token = $request->query('token') ?? $request->input('token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan.',
            ], 400);
        }

        // Cari token yang valid
        $qrToken = KostQrToken::findValid($token);

        if (!$qrToken) {
            return response()->json([
                'success' => false,
                'message' => '❌ QR Code sudah expired atau tidak valid. Minta penghuni refresh QR.',
            ], 422);
        }

        $kost = $qrToken->kost;

        if (!$kost || $kost->isKosong()) {
            return response()->json([
                'success' => false,
                'message' => 'Kamar tidak ditemukan atau kosong.',
            ], 404);
        }

        // Tentukan jenis log (masuk/keluar)
        $jenis = KostLog::jenisBerikutnya($kost->id);

        // Simpan log
        $log = KostLog::create([
            'kost_id'      => $kost->id,
            'nama_penyewa' => $kost->nama_penyewa,
            'jenis'        => $jenis,
            'token_used'   => $token,
            'scanned_at'   => now(),
        ]);

        // Hapus token yang sudah dipakai
        $qrToken->delete();

        return response()->json([
            'success'      => true,
            'jenis'        => $jenis,
            'nama_penyewa' => $kost->nama_penyewa,
            'nomor_kamar'  => $kost->nama_kamar_lengkap,
            'scanned_at'   => $log->scanned_at->format('H:i:s'),
            'message'      => $jenis === 'masuk'
                ? "✅ {$kost->nama_penyewa} - {$kost->nama_kamar_lengkap} MASUK"
                : "🚪 {$kost->nama_penyewa} - {$kost->nama_kamar_lengkap} KELUAR",
        ]);
    }
}