<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Pago;
use App\Models\Paciente;
use App\Models\Presupuesto;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    /**
     * Genera datos de demostracion repartidos en los ultimos 6 meses
     * para que el dashboard y los reportes muestren tendencias reales.
     */
    public function run(): void
    {
        try {
            $faker = fake('es_ES');
        } catch (\Throwable $e) {
            $faker = fake();
        }

        // ----- Asegurar catalogo de tratamientos -----
        if (Tratamiento::count() === 0) {
            $this->call(TratamientoSeeder::class);
        }
        $tratamientos = Tratamiento::where('activo', true)->get();

        // ----- Usuarios (staff) hasta tener al menos 10 -----
        $nuevosStaff = [
            ['Dr. Pedro Salazar', 'doctor', 'Implantologia'],
            ['Dra. Carmen Vega', 'doctor', 'Rehabilitacion Oral'],
            ['Dra. Lucia Mendez', 'doctor', 'Ortodoncia'],
            ['Jorge Receptor', 'recepcion', null],
        ];
        foreach ($nuevosStaff as $i => [$nombre, $rol, $esp]) {
            if (User::count() >= 12) {
                break;
            }
            User::updateOrCreate(
                ['email' => 'staff'.($i + 1).'@odontocrm.com'],
                [
                    'name' => $nombre,
                    'password' => Hash::make('password'),
                    'rol' => $rol,
                    'especialidad' => $esp,
                    'telefono' => '7'.random_int(1000000, 9999999),
                    'activo' => true,
                ]
            );
        }
        $doctores = User::where('rol', 'doctor')->where('activo', true)->get();

        // ----- Pacientes nuevos repartidos por mes -----
        $pacientesDemo = collect();
        for ($i = 0; $i < 12; $i++) {
            $fecha = $this->fechaEnMes($i % 6);
            $p = Paciente::create([
                'nombre' => $faker->firstName(),
                'apellido' => $faker->lastName(),
                'documento' => (string) random_int(1000000, 9999999),
                'telefono' => '7'.random_int(1000000, 9999999),
                'email' => $faker->unique()->safeEmail(),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-5 years')->format('Y-m-d'),
                'genero' => ['M', 'F'][array_rand(['M', 'F'])],
                'direccion' => $faker->streetAddress(),
                'activo' => true,
            ]);
            // Forzar fecha de registro (created_at) en el mes correspondiente.
            $p->created_at = $fecha->copy()->setTime(random_int(8, 18), random_int(0, 59));
            $p->updated_at = $p->created_at;
            $p->saveQuietly();

            $pacientesDemo->push($p);
        }

        // Conjunto de pacientes disponible (demo + existentes).
        $pacientes = Paciente::where('activo', true)->get();
        $motivos = ['Consulta general', 'Limpieza dental', 'Control de ortodoncia', 'Extraccion', 'Endodoncia', 'Blanqueamiento', 'Revision', 'Urgencia'];

        // ----- Citas repartidas en 6 meses (3 por mes) -----
        for ($m = 0; $m < 6; $m++) {
            for ($c = 0; $c < 3; $c++) {
                $fecha = $this->fechaEnMes($m);
                // Meses pasados -> mayormente completadas; mes actual -> mixtas.
                $estado = $m === 0
                    ? ['pendiente', 'confirmada', 'completada', 'cancelada'][array_rand([0, 1, 2, 3])]
                    : (random_int(1, 100) <= 70 ? 'completada' : ['confirmada', 'cancelada'][array_rand([0, 1])]);

                Cita::create([
                    'paciente_id' => $pacientes->random()->id,
                    'doctor_id' => $doctores->random()->id,
                    'fecha' => $fecha->format('Y-m-d'),
                    'hora' => sprintf('%02d:%02d:00', random_int(8, 18), [0, 30][array_rand([0, 1])]),
                    'motivo' => $motivos[array_rand($motivos)],
                    'estado' => $estado,
                ]);
            }
        }

        // ----- Citas para HOY (para la tarjeta "Citas Hoy") -----
        for ($c = 0; $c < 4; $c++) {
            Cita::create([
                'paciente_id' => $pacientes->random()->id,
                'doctor_id' => $doctores->random()->id,
                'fecha' => Carbon::today()->format('Y-m-d'),
                'hora' => sprintf('%02d:%02d:00', random_int(8, 18), [0, 30][array_rand([0, 1])]),
                'motivo' => $motivos[array_rand($motivos)],
                'estado' => ['pendiente', 'confirmada'][array_rand([0, 1])],
            ]);
        }

        // ----- Presupuestos (2 por mes) con items + pagos -----
        for ($m = 0; $m < 6; $m++) {
            for ($n = 0; $n < 2; $n++) {
                $fecha = $this->fechaEnMes($m);
                $paciente = $pacientes->random();

                // Construir items.
                $cantItems = random_int(1, 3);
                $subtotal = 0;
                $items = [];
                for ($k = 0; $k < $cantItems; $k++) {
                    $trat = $tratamientos->random();
                    $cantidad = random_int(1, 2);
                    $precio = (float) $trat->precio;
                    $sub = round($cantidad * $precio, 2);
                    $subtotal += $sub;
                    $items[] = [
                        'tratamiento_id' => $trat->id,
                        'descripcion' => $trat->nombre,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precio,
                        'subtotal' => $sub,
                    ];
                }
                $descuento = random_int(0, 1) ? round($subtotal * 0.1, 2) : 0;
                $total = max($subtotal - $descuento, 0);
                $estado = random_int(1, 100) <= 75 ? 'aprobado' : ['borrador', 'rechazado'][array_rand([0, 1])];

                $presupuesto = Presupuesto::create([
                    'codigo' => Presupuesto::nuevoCodigo(),
                    'paciente_id' => $paciente->id,
                    'doctor_id' => $doctores->random()->id,
                    'fecha' => $fecha->format('Y-m-d'),
                    'estado' => $estado,
                    'subtotal' => $subtotal,
                    'descuento' => $descuento,
                    'total' => $total,
                ]);
                $presupuesto->items()->createMany($items);

                // Pagos solo para aprobados.
                if ($estado === 'aprobado' && $total > 0) {
                    $metodos = array_keys(Pago::METODOS);
                    // Pago inicial (total o parcial).
                    $pagoCompleto = random_int(1, 100) <= 60;
                    $monto = $pagoCompleto ? $total : round($total * (random_int(40, 70) / 100), 2);

                    Pago::create([
                        'paciente_id' => $paciente->id,
                        'presupuesto_id' => $presupuesto->id,
                        'fecha' => $fecha->copy()->addDays(random_int(0, 5))->format('Y-m-d'),
                        'monto' => $monto,
                        'metodo' => $metodos[array_rand($metodos)],
                        'referencia' => 'REC-'.random_int(10000, 99999),
                    ]);

                    // Posible segundo pago (saldo).
                    if (! $pagoCompleto && random_int(0, 1)) {
                        Pago::create([
                            'paciente_id' => $paciente->id,
                            'presupuesto_id' => $presupuesto->id,
                            'fecha' => $fecha->copy()->addDays(random_int(6, 20))->format('Y-m-d'),
                            'monto' => round($total - $monto, 2),
                            'metodo' => $metodos[array_rand($metodos)],
                            'referencia' => 'REC-'.random_int(10000, 99999),
                        ]);
                    }
                }
            }
        }

        $this->command?->info('Datos demo generados: pacientes, citas, presupuestos y pagos en los ultimos 6 meses.');
    }

    /** Devuelve una fecha aleatoria dentro del mes indicado (0 = mes actual). */
    private function fechaEnMes(int $mesesAtras): Carbon
    {
        $base = Carbon::today()->subMonthsNoOverflow($mesesAtras);
        $maxDia = $mesesAtras === 0 ? Carbon::today()->day : $base->daysInMonth;
        $dia = random_int(1, max(1, $maxDia));

        return $base->copy()->day($dia);
    }
}
