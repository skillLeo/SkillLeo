<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangeConfirmMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user, public string $url) {}

    public function build()
    {
        return $this->subject('Confirm your password change')
            ->markdown('emails.auth.password-change-confirm', [
                'user' => $this->user,
                'url'  => $this->url,
            ]);
    }
}
