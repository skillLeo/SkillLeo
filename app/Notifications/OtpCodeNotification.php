<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OtpCodeNotification extends Notification
{
    use Queueable;

    public function __construct(public string $code) {}

    public function via($notifiable): array {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Your verification code')
            ->greeting('Hi '.$notifiable->name)
            ->line('Use this code to verify your email:')
            ->line('**'.$this->code.'**')
            ->line('This code expires in 10 minutes.');
    }
}
