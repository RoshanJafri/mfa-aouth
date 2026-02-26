<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'otp.verified'])->name('dashboard');



// OTP ROUTES
Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verify']);
Route::get('/resend-otp', [OtpController::class, 'resend'])->name('otp.resend');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // DEVICE ROUTES
    Route::get('/trust-device', [RegisteredUserController::class, 'trustDevice'])->name('trust.device');
    Route::post('/devices/register', [DeviceController::class, 'register']);
});

require __DIR__ . '/auth.php';