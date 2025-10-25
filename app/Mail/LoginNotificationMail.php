<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public array $loginDetails
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Sign-In to Your Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.login-notification',
            with: [
                'user' => $this->user,
                'device' => $this->loginDetails['device'] ?? 'Unknown Device',
                'browser' => $this->loginDetails['browser'] ?? 'Unknown Browser',
                'platform' => $this->loginDetails['platform'] ?? 'Unknown Platform',
                'ip' => $this->loginDetails['ip'] ?? 'Unknown IP',
                'location' => $this->loginDetails['location'] ?? 'Unknown Location',
                'timestamp' => $this->loginDetails['timestamp'] ?? now(),
            ],
        );
    }
}