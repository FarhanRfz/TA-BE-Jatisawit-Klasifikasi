<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordCustomNotification extends Notification
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Password Akun Anda')
            ->line('Klik tombol di bawah ini untuk mengatur ulang kata sandi Anda.')
            ->action('Reset Password', $this->url)
            ->line('Jika Anda tidak meminta reset, abaikan email ini.');
    }
}
