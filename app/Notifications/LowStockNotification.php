<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
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
            ->subject('Aviso: Stock bajo')
            ->line('El tratamiento "' . $this->treatment->name . '" tiene un stock bajo.')
            ->line('Quedan ' . $this->treatment->units_available . ' unidades disponibles.')
            ->action('Revisar tratamiento', url('/treatments/' . $this->treatment->id))
            ->line('Gracias por usar nuestra aplicación.');
    }
}
