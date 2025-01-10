<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GeneratePasswordResetToken extends Command
{
    protected $signature = 'user:generate-reset-token {email}';
    protected $description = 'Genera un token para restablecer la contraseña';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error('Usuario no encontrado');
            return;
        }

        $token = Str::random(60);
        $user->password_reset_token = Hash::make($token);
        $user->save();

        $this->info("Token generado: {$token}");
    }
}

