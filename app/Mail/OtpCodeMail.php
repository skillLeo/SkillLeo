<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $name, public string $code, public int $ttlSeconds) {}

    public function build()
    {
        // Optional deep-link to prefill code on same device
        $url = url('/otp?code=' . $this->code);

        return $this->subject('Your SkillLeo sign-in code')
            ->markdown('emails.auth.otp', [
                'name' => $this->name,
                'code' => $this->code,
                'ttl'  => $this->ttlSeconds,
                'url'  => $url,
            ])
            ->text('emails.auth.otp_plain', [ // plaintext fallback
                'name' => $this->name,
                'code' => $this->code,
                'ttl'  => $this->ttlSeconds,
                'url'  => $url,
            ]);
    }
}
