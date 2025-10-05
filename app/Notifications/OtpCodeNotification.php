<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private string $code) {}

    public function via(object $notifiable): array {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Your ProMatch verification code')
            ->greeting('Hi '.$notifiable->name ?: 'there')
            ->line('Use this 6-digit code to finish signing in:')
            ->line('**'.$this->code.'**')
            ->line('This code expires in 10 minutes.')
            ->line('If you didn’t request this, you can ignore this email.');
    }
}
