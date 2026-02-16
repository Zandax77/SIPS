<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ResetPasswordRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $petugas;
    public $requestTime;

    /**
     * Create a new notification instance.
     */
    public function __construct($petugas)
    {
        $this->petugas = $petugas;
        $this->requestTime = now();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): \Illuminate\Notifications\Messages\MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Permintaan Reset Password - SIPS')
            ->greeting('Halo Super Admin,')
            ->line('Ada permintaan reset password dari petugas berikut:')
            ->line('**Nama:** ' . $this->petugas->name)
            ->line('**Email:** ' . $this->petugas->email)
            ->line('**Jabatan:** ' . $this->petugas->jabatan)
            ->line('**Waktu Permintaan:** ' . $this->requestTime->format('d/m/Y H:i:s'))
            ->action('Reset Password', route('admin.petugas.index'))
            ->line('Silakan login ke panel admin untuk mereset password petugas tersebut.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Permintaan Reset Password',
            'message' => 'Petugas ' . $this->petugas->name . ' (' . $this->petugas->email . ') meminta reset password.',
            'petugas_id' => $this->petugas->id,
            'petugas_name' => $this->petugas->name,
            'petugas_email' => $this->petugas->email,
            'petugas_jabatan' => $this->petugas->jabatan,
            'request_time' => $this->requestTime->toIso8601String(),
            'type' => 'password_reset_request',
        ];
    }
}

