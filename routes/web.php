<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LoginLogController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {

    $devices = auth()->user()
        ->devices()
        ->latest()
        ->get();

    return view('dashboard', compact('devices'));

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

    Route::get('/login-logs', [LoginLogController::class, 'index'])
        ->name('login.logs');
    Route::get('/devices', [DeviceController::class, 'index'])
        ->name('devices.index');
    Route::get('/otps', [OtpController::class, 'index'])
        ->name('otps.index');
    Route::resource('/users', UserController::class);


    // DEVICE ROUTES
    Route::get('/trust-device', [RegisteredUserController::class, 'trustDevice'])->name('trust.device');
    Route::post('/devices/register', [DeviceController::class, 'register']);


    Route::patch('/devices/{device}/trust', [DeviceController::class, 'trust'])
        ->name('devices.trust');

    Route::patch('/devices/{device}/untrust', [DeviceController::class, 'untrust'])
        ->name('devices.untrust');
});

require __DIR__ . '/auth.php';