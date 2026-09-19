<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CrearAdmin extends Command
{
    protected $signature = 'odonto:admin
                            {--email=admin@odontocrm.com : Correo del administrador}
                            {--password=password : Contrasena del administrador}
                            {--name=Admin Sistema : Nombre del administrador}';

    protected $description = 'Crea o restablece el usuario administrador de OdontoCRM';

    public function handle(): int
    {
        $email = $this->option('email');
        $password = $this->option('password');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $this->option('name'),
                'password' => Hash::make($password),
                'rol' => 'admin',
                'activo' => true,
            ]
        );

        $this->newLine();
        $this->info('Administrador listo.');
        $this->line('  Correo:     '.$user->email);
        $this->line('  Contrasena: '.$password);
        $this->newLine();

        return self::SUCCESS;
    }
}
