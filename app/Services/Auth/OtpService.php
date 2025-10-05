<?php

namespace App\Services\Auth;

use App\Models\EmailOtp;
use App\Models\User;
use App\Notifications\OtpCodeNotification;

class OtpService
{
    public int $ttlMinutes = 10;
    public int $maxAttempts = 5;
    public int $resendCooldownSec = 60;

    public function createAndSend(User $user, ?string $ip = null): EmailOtp
    {
        // Enforce 60s cooldown
        $latest = EmailOtp::where('user_id',$user->id)->latest()->first();
        if ($latest && $latest->created_at->gt(now()->subSeconds($this->resendCooldownSec))) {
            return $latest; // silently ignore, UI should throttle
        }

        // Expire previous actives
        EmailOtp::where('user_id',$user->id)->where('status','active')
            ->update(['status'=>'expired']);

        $otp = EmailOtp::create([
            'user_id' => $user->id,
            'code' => str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes($this->ttlMinutes),
            'ip' => $ip,
            'status' => 'active',
        ]);

        $user->notify(new OtpCodeNotification($otp->code));
        return $otp;
    }

    public function verify(User $user, string $code): bool
    {
        $record = EmailOtp::where('user_id',$user->id)
            ->where('status','active')->latest()->first();

        if (! $record) return false;

        if ($record->expires_at->isPast()) {
            $record->update(['status'=>'expired']);
            return false;
        }

        $record->increment('attempts');
        if ($record->attempts > $this->maxAttempts) {
            $record->update(['status'=>'locked']);
            return false;
        }

        if (! hash_equals($record->code, $code)) {
            return false;
        }

        $record->update(['consumed_at'=>now(),'status'=>'consumed']);
        $user->forceFill(['email_verified_at'=> $user->email_verified_at ?? now()])->save();

        return true;
    }
}
