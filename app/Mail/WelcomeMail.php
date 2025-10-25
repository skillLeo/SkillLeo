<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public array $registrationDetails = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . config('app.name') . '!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.welcome',
            with: [
                'user' => $this->user,
                'ip' => $this->registrationDetails['ip'] ?? request()->ip(),
                'device' => $this->registrationDetails['device'] ?? 'Unknown Device',
                'timestamp' => $this->registrationDetails['timestamp'] ?? now(),
            ],
        );
    }
}