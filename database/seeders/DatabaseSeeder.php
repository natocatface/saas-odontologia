<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ----- Usuario administrador principal -----
        $admin = User::updateOrCreate(
            ['email' => 'admin@odontocrm.com'],
            [
                'name' => 'Admin Sistema',
                'password' => Hash::make('password'),
                'rol' => 'admin',
                'telefono' => '70000000',
                'especialidad' => null,
                'activo' => true,
            ]
        );

        // ----- Doctores / odontologos -----
        $doctoresData = [
            ['name' => 'Dr. Carlos Rodriguez', 'especialidad' => 'Ortodoncia'],
            ['name' => 'Dra. Maria Lopez', 'especialidad' => 'Endodoncia'],
            ['name' => 'Dr. Juan Perez', 'especialidad' => 'Cirugia Oral'],
            ['name' => 'Dra. Ana Martinez', 'especialidad' => 'Odontopediatria'],
            ['name' => 'Dr. Luis Gomez', 'especialidad' => 'Periodoncia'],
            ['name' => 'Dra. Sofia Fernandez', 'especialidad' => 'Estetica Dental'],
        ];

        $doctores = [];
        foreach ($doctoresData as $i => $d) {
            $slug = 'doctor'.($i + 1).'@odontocrm.com';
            $doctores[] = User::updateOrCreate(
                ['email' => $slug],
                [
                    'name' => $d['name'],
                    'password' => Hash::make('password'),
                    'rol' => 'doctor',
                    'especialidad' => $d['especialidad'],
                    'telefono' => '7'.random_int(1000000, 9999999),
                    'activo' => true,
                ]
            );
        }

        // Recepcion
        User::updateOrCreate(
            ['email' => 'recepcion@odontocrm.com'],
            [
                'name' => 'Laura Recepcion',
                'password' => Hash::make('password'),
                'rol' => 'recepcion',
                'activo' => true,
            ]
        );

        // ----- Pacientes (80) -----
        if (Paciente::count() === 0) {
            try {
                $faker = fake('es_ES');
            } catch (\Throwable $e) {
                $faker = fake();
            }
            $generos = ['M', 'F'];

            for ($i = 0; $i < 80; $i++) {
                Paciente::create([
                    'nombre' => $faker->firstName(),
                    'apellido' => $faker->lastName(),
                    'documento' => (string) random_int(1000000, 9999999),
                    'telefono' => '7'.random_int(1000000, 9999999),
                    'email' => $faker->unique()->safeEmail(),
                    'fecha_nacimiento' => $faker->dateTimeBetween('-75 years', '-3 years')->format('Y-m-d'),
                    'genero' => $generos[array_rand($generos)],
                    'direccion' => $faker->streetAddress(),
                    'activo' => true,
                ]);
            }
        }

        // ----- Citas demo (proximas y de hoy) -----
        if (Cita::count() === 0 && Paciente::count() > 0) {
            $pacientes = Paciente::pluck('id')->all();
            $motivos = ['Consulta general', 'Limpieza dental', 'Control de ortodoncia', 'Extraccion', 'Endodoncia', 'Blanqueamiento', 'Revision'];
            $estados = ['pendiente', 'confirmada', 'completada', 'cancelada'];

            for ($i = 0; $i < 40; $i++) {
                $fecha = Carbon::today()->addDays(random_int(-10, 15));
                Cita::create([
                    'paciente_id' => $pacientes[array_rand($pacientes)],
                    'doctor_id' => $doctores[array_rand($doctores)]->id,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora' => sprintf('%02d:%02d:00', random_int(8, 18), [0, 30][array_rand([0, 30])]),
                    'motivo' => $motivos[array_rand($motivos)],
                    'estado' => $estados[array_rand($estados)],
                ]);
            }
        }
    }
}
