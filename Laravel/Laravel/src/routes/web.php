<?php

declare(strict_types=1);

use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

// ============================================================
// Root — redirect ke login Filament
// ============================================================
Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

// ============================================================
// Login — handle redirect berdasarkan role
// ============================================================
Route::get('/login', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('penyewa')) {
            return redirect()->route('penyewa.dashboard');
        }
        return redirect('/admin');
    }
    return redirect()->route('filament.admin.auth.login');
})->name('login');

// ============================================================
// QR Code — dibuka di HP penyewa (route lama, tetap ada)
// ============================================================
Route::get('/qr', [QrCodeController::class, 'index'])->name('qr.index');
Route::get('/qr/{id}', [QrCodeController::class, 'show'])->name('qr.show');
Route::get('/qr/{id}/generate', [QrCodeController::class, 'generate'])->name('qr.generate');

// ============================================================
// Scanner — dibuka di PC / kamera pintu (admin/petugas)
// ============================================================
Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
Route::get('/scan/verify', [ScanController::class, 'verify'])->name('scan.verify');

// ============================================================
// Penyewa — harus login & punya role "penyewa"
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/penyewa/dashboard', [PenyewaController::class, 'dashboard'])
        ->name('penyewa.dashboard');
    Route::get('/penyewa/qr/generate', [PenyewaController::class, 'generateQr'])
        ->name('penyewa.qr.generate');
});