<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ScanController;

Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

// QR Code (dibuka di HP)
Route::get('/qr', [QrCodeController::class, 'index'])->name('qr.index');
Route::get('/qr/{id}', [QrCodeController::class, 'show'])->name('qr.show');
Route::get('/qr/{id}/generate', [QrCodeController::class, 'generate'])->name('qr.generate');

// Scanner (dibuka di PC)
Route::get('/scan', [ScanController::class, 'index'])->name('scan.index');
Route::get('/scan/verify', [ScanController::class, 'verify'])->name('scan.verify');