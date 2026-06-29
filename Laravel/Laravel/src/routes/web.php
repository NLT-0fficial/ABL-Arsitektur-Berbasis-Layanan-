<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckInController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkin/{token}', [CheckInController::class, 'scan'])
     ->name('room.qr-checkin');