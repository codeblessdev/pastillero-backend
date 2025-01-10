<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Treatment;
use App\Notifications\ExpirationNotification;
use Carbon\Carbon;

class NotifyExpiringTreatments extends Command
{
    protected $signature = 'notify:expiring-treatments';
    protected $description = 'Enviar notificaciones para tratamientos próximos a caducar';

    public function handle()
    {
        $now = Carbon::now();
        $threeDaysFromNow = $now->copy()->addDays(3);

        $treatments = Treatment::where('expiration_date', '<=', $threeDaysFromNow)
            ->where('notify_expiration', true)
            ->get();

        foreach ($treatments as $treatment) {
            $user = $treatment->user;

            if ($user) {
                $user->notify(new ExpirationNotification($treatment));
                $this->info("Notificación enviada a {$user->email} para el tratamiento '{$treatment->name}'.");
            }
        }

        return 0;
    }
}

