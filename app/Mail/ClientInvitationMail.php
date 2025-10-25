<?php
// app/Mail/ClientInvitationMail.php

namespace App\Mail;

use App\Models\ClientInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ClientInvitation $invitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You\'ve been invited as a Client - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        $acceptUrl = route('client.accept-invitation', ['token' => $this->invitation->token]);
        
        return new Content(
            view: 'emails.client-invitation',
            with: [
                'invitation' => $this->invitation,
                'inviterName' => $this->invitation->inviter->name ?? 'Our Team',
                'inviterEmail' => $this->invitation->inviter->email ?? '',
                'clientName' => $this->invitation->name,
                'acceptUrl' => $acceptUrl,
                'expiresAt' => $this->invitation->expires_at->format('F d, Y'),
                'customMessage' => $this->invitation->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}