<?php

namespace App\Services\Auth;

use App\Models\EmailOtp;
use App\Models\User;
use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Str;

class OtpService
{
    public function createAndSend(User $user, ?string $ip = null): EmailOtp
    {
        // Invalidate previous active
        EmailOtp::where('user_id',$user->id)->whereNull('consumed_at')->update(['status'=>'expired']);

        $otp = EmailOtp::create([
            'user_id' => $user->id,
            'code' => str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10),
            'ip' => $ip,
            'status' => 'active',
        ]);

        $user->notify(new OtpCodeNotification($otp->code));
        return $otp;
    }

    public function verify(User $user, string $code): bool
    {
        $record = EmailOtp::where('user_id',$user->id)
            ->where('status','active')
            ->latest()->first();

        if (! $record) return false;
        if ($record->isExpired()) { $record->status = 'expired'; $record->save(); return false; }

        $record->increment('attempts');
        if ($record->attempts > 5) { $record->status = 'locked'; $record->save(); return false; }

        if (! hash_equals($record->code, $code)) return false;

        $record->consumed_at = now();
        $record->status = 'consumed';
        $record->save();

        $user->email_verified_at = now();
        $user->save();

        return true;
    }
}
