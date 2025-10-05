<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SignupLinkMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $url
    ) {}

    public function build()
    {
        return $this->subject('Confirm your SkillLeo account')
            ->markdown('emails.signup-link', [
                'name' => $this->name,
                'url'  => $this->url,
            ]);
    }
}
