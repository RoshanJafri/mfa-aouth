<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureMfaChallenge;
use App\Http\Middleware\EnsureOtpIsVerified;
use App\Http\Controllers\DeviceFingerprinting;
use App\Http\Controllers\Auth\GithubController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('', function () {
    return redirect(route('login'));
});

Route::middleware([EnsureOtpIsVerified::class])->group(function () {

    Route::get('/dashboard', function () {
        $users = User::select('id','name', 'email', 'last_login_at','geolocation_history', 'last_login_ip','trusted_devices')->get();

        return view('dashboard', compact('users'));
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::view('/device-check', 'fingerprinting.check')->name('device.check.form');
        Route::view('/oauth-check', 'fingerprinting.oauth-check')->name('device.oauth-check.form');
        Route::post('/oauth-check', [DeviceController::class, 'ouathcheck'])->name('device.ouathcheck');
        Route::post('/device-check', [DeviceController::class, 'check'])->name('device.check')->middleware('auth');
        Route::post('/trust-device', [DeviceController::class, 'trust'])->name('device.trust')->middleware('auth');
        Route::post('/remove-device', [DeviceController::class, 'removeTrustedDevice'])->middleware('auth');
        Route::get('/notify', [DeviceController::class, 'notify'])->name('device.notify')->middleware('auth');
    });
});

Route::get('/verify-otp', [OtpController::class, 'showForm'])->name('verify.otp.form');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('verify.otp');


Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/auth/github', [GithubController::class, 'redirectToGithub']);
Route::get('/auth/github/callback', [GithubController::class, 'handleGithubCallback']);

require __DIR__ . '/auth.php';
