<?php
namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public function generate(User $user): void
    {
        $otp = random_int(100000, 999999);

        Otp::where('user_id', $user->id)->delete();

        Otp::create([
            'user_id' => $user->id,
            'otp_hash' => Hash::make($otp),
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        Mail::raw("Your OTP is: {$otp}. It expires in 10 minutes.", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your Verification Code');
        });
    }

    public function verify(User $user, string $inputOtp): bool
    {

        $record = Otp::where('user_id', $user->id)->first();

        if (!$record) {
            return false;
        }

        // Check already used
        if ($record->used_at !== null) {
            return false;
        }

        // expired?
        if (now()->greaterThan($record->expires_at)) {
            return false;
        }

        // Check attempts
        if ($record->attempts >= 5) {
            return false;
        }

        // increase attempts
        $record->increment('attempts');

        if (!Hash::check($inputOtp, $record->otp_hash)) {
            return false;
        }

        // Mark as used
        $record->update([
            'used_at' => now(),
        ]);

        // Mark email verified
        $user->update([
            'email_verified_at' => now(),
        ]);

        return true;
    }
}