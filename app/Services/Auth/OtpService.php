<?php

// app/Services/Auth/OtpService.php
namespace App\Services\Auth;

use App\Mail\OtpCodeMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpService
{
    // 120 seconds TTL
    private int $ttlSeconds = 120;

    public function beginLogin(User $user, string $sessionId, string $ip, string $ua): string
    {
        // Invalidate previous challenge on this session
        $prev = Cache::pull("otp:idx:{$sessionId}:login");

        $challengeId = Str::uuid()->toString();
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hash with bcrypt (slow hash, thwarts offline guessing)
        $hash = password_hash($code, PASSWORD_BCRYPT);

        $payload = [
            'user_id'   => $user->id,
            'hash'      => $hash,
            'expires_at'=> now()->addSeconds($this->ttlSeconds)->getTimestamp(),
            'attempts'  => 0,
            'max_attempts' => 5,
            'ip'        => $ip,
            'ua'        => $ua,
            'session'   => $sessionId,
        ];

        Cache::put("otp:login:{$challengeId}", $payload, $this->ttlSeconds + 60);
        Cache::put("otp:idx:{$sessionId}:login", $challengeId, $this->ttlSeconds + 60);

        // Queue email (includes deep-link autofill)
        Mail::to($user->email)->queue(new OtpCodeMail($user->name ?? 'there', $code, $this->ttlSeconds));

        return $challengeId;
    }

    public function resend(User $user, string $sessionId): string
    {
        // rotate challenge
        $oldId = Cache::pull("otp:idx:{$sessionId}:login");
        if ($oldId) Cache::forget("otp:login:{$oldId}");

        return $this->beginLogin($user, $sessionId, request()->ip(), (string) request()->userAgent());
    }

    public function verify(string $challengeId, string $code, string $sessionId, string $ip, string $ua): bool
    {
        $key = "otp:login:{$challengeId}";
        $payload = Cache::get($key);
        if (! $payload) return false;

        // Bind to session & basic risk signals
        if ($payload['session'] !== $sessionId) return false;

        if (time() > $payload['expires_at']) {
            Cache::forget($key);
            return false;
        }

        if ($payload['attempts'] >= $payload['max_attempts']) {
            Cache::forget($key);
            return false;
        }

        $payload['attempts']++;
        Cache::put($key, $payload, $this->ttlSeconds); // persist attempts

        $ok = password_verify($code, $payload['hash']);
        if ($ok) {
            Cache::forget($key);
        }

        return $ok;
    }

    public function remainingSeconds(string $challengeId): int
    {
        $payload = Cache::get("otp:login:{$challengeId}");
        if (! $payload) return 0;
        return max(0, $payload['expires_at'] - time());
    }
}
