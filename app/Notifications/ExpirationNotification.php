<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpirationNotification extends Notification
{
    use Queueable;

    private $treatment;

    public function __construct($treatment)
    {
        $this->treatment = $treatment;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Aviso: Tratamiento Próximo a Caducar')
            ->line('El tratamiento "' . $this->treatment->name . '" está próximo a caducar.')
            ->line('Fecha de caducidad: ' . $this->treatment->expiration_date->format('d/m/Y'))
            ->action('Revisar tratamiento', url('/treatments/' . $this->treatment->id))
            ->line('Por favor, toma las medidas necesarias.')
            ->line('Gracias por usar nuestra aplicación.');
    }
}

class TreatmentExpirationNotification extends Notification
{
    public function via($notifiable)
    {
        return ['fcm'];
    }

    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setNotification([
                'title' => 'Tratamiento por caducar',
                'body' => 'Uno de tus tratamientos está cerca de caducar.',
            ]);
    }
}
