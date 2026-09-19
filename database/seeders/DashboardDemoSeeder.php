<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\Consentimiento;
use App\Models\Cuota;
use App\Models\Evolucion;
use App\Models\Gasto;
use App\Models\Insumo;
use App\Models\MovimientoInventario;
use App\Models\Pago;
use App\Models\Paciente;
use App\Models\Presupuesto;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

/**
 * Reinicia y regenera datos de demostracion LIMPIOS para poblar el
 * dashboard: ~10+ registros por modulo repartidos en los ultimos 6
 * meses e incluyendo registros de HOY, de modo que:
 *   - El panel "Citas de Hoy" (flecha roja) se llene.
 *   - El grafico "Tendencia de Citas" muestre una curva real de 6 meses
 *     con lineas de Totales y Completadas.
 *   - Los KPI (Total Pacientes, Citas Hoy, Pendientes, Ingresos del Mes),
 *     el arqueo de Caja del dia y el resto de modulos tengan datos.
 *
 * IMPORTANTE: este seeder BORRA los datos transaccionales/demo previos
 * (citas, presupuestos, pagos, cuotas, gastos, inventario, evoluciones,
 * consentimientos, actividades y pacientes) y los regenera desde cero.
 * NO toca los usuarios (admin/doctores/recepcion) ni el catalogo de
 * tratamientos ni las configuraciones.
 *
 * Ejecutar:  php artisan db:seed --class=DashboardDemoSeeder
 */
class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        try {
            $faker = fake('es_ES');
        } catch (\Throwable $e) {
            $faker = fake();
        }

        $hoy = Carbon::today();

        // ===============================================================
        // 0) RESET LIMPIO de los datos transaccionales / demo
        // ===============================================================
        $this->limpiar([
            'actividades',
            'cuotas',
            'pagos',
            'presupuesto_items',
            'presupuestos',
            'movimientos_inventario',
            'insumos',
            'evoluciones',
            'consentimientos',
            'archivos',
            'citas',
            'pacientes',
        ]);

        // ===============================================================
        // 1) Catalogo de tratamientos (al menos 10 activos)
        // ===============================================================
        $catalogo = [
            ['Consulta / Diagnostico', 'Diagnostico', 150, 30],
            ['Limpieza dental (profilaxis)', 'Preventiva', 250, 40],
            ['Resina / Obturacion', 'Restauracion', 350, 45],
            ['Endodoncia unirradicular', 'Endodoncia', 900, 60],
            ['Extraccion simple', 'Cirugia', 300, 30],
            ['Ortodoncia (instalacion)', 'Ortodoncia', 2500, 90],
            ['Blanqueamiento dental', 'Estetica', 1200, 60],
            ['Corona de porcelana', 'Protesis', 1800, 60],
            ['Implante dental', 'Cirugia', 4500, 90],
            ['Curetaje / Periodoncia', 'Periodoncia', 600, 45],
        ];
        foreach ($catalogo as [$nombre, $cat, $precio, $dur]) {
            Tratamiento::firstOrCreate(
                ['nombre' => $nombre],
                ['categoria' => $cat, 'precio' => $precio, 'duracion_min' => $dur, 'activo' => true]
            );
        }
        $tratamientos = Tratamiento::where('activo', true)->get();

        // ===============================================================
        // 2) Doctores / staff (con % de comision para el modulo Comisiones)
        // ===============================================================
        $staff = [
            ['Dr. Pedro Salazar', 'doctor', 'Implantologia', 12],
            ['Dra. Carmen Vega', 'doctor', 'Rehabilitacion Oral', 10],
            ['Dra. Lucia Mendez', 'doctor', 'Ortodoncia', 15],
            ['Dr. Andres Rojas', 'doctor', 'Endodoncia', 10],
            ['Jorge Receptor', 'recepcion', null, 0],
        ];
        foreach ($staff as $i => [$nombre, $rol, $esp, $com]) {
            User::updateOrCreate(
                ['email' => 'staff'.($i + 1).'@odontocrm.com'],
                [
                    'name' => $nombre,
                    'password' => Hash::make('password'),
                    'rol' => $rol,
                    'especialidad' => $esp,
                    'comision' => $com,
                    'telefono' => '7'.random_int(1000000, 9999999),
                    'activo' => true,
                ]
            );
        }
        // Asegurar que los doctores existentes tengan un % de comision.
        User::where('rol', 'doctor')->where('comision', 0)->update(['comision' => 10]);
        $doctores = User::where('rol', 'doctor')->where('activo', true)->get();
        $adminUser = User::where('rol', 'admin')->first() ?? $doctores->first();

        // ===============================================================
        // 3) Pacientes (30) con fechas de registro repartidas por mes
        //    (varios en el mes actual -> KPI "+X este mes")
        // ===============================================================
        $enfermedadesCat = ['Hipertension', 'Diabetes', 'Asma', 'Ninguna', 'Gastritis'];
        $alergiasCat = ['Penicilina', 'Latex', 'Ninguna conocida', 'Ibuprofeno', 'Anestesia local'];
        $pacientesNuevos = collect();
        for ($i = 0; $i < 30; $i++) {
            $fechaReg = $this->fechaEnMes($i % 6);
            $p = Paciente::create([
                'nombre' => $faker->firstName(),
                'apellido' => $faker->lastName(),
                'documento' => (string) random_int(1000000, 9999999),
                'telefono' => '7'.random_int(1000000, 9999999),
                'email' => $faker->unique()->safeEmail(),
                'fecha_nacimiento' => $faker->dateTimeBetween('-70 years', '-6 years')->format('Y-m-d'),
                'genero' => ['M', 'F'][array_rand(['M', 'F'])],
                'direccion' => $faker->streetAddress(),
                'alergias' => $alergiasCat[array_rand($alergiasCat)],
                'enfermedades' => [$enfermedadesCat[array_rand($enfermedadesCat)]],
                'observaciones' => 'Paciente demo generado para el dashboard.',
                'activo' => true,
            ]);
            $p->created_at = $fechaReg->copy()->setTime(random_int(8, 18), random_int(0, 59));
            $p->updated_at = $p->created_at;
            $p->saveQuietly();
            $pacientesNuevos->push($p);
        }
        $pacientes = Paciente::where('activo', true)->get();

        $motivos = ['Consulta general', 'Limpieza dental', 'Control de ortodoncia', 'Extraccion',
            'Endodoncia', 'Blanqueamiento', 'Revision', 'Urgencia', 'Colocacion de corona', 'Control post-operatorio'];

        // ===============================================================
        // 4) Citas repartidas en 6 meses - historial para el grafico
        //    "Tendencia de Citas". En lugar de un numero fijo por mes se
        //    usa una CURVA DE CRECIMIENTO (menos citas en los meses mas
        //    antiguos y mas en los recientes), de modo que la linea de
        //    Totales suba mes a mes de forma clara y natural, y la de
        //    Completadas la acompane. La clave es "meses atras" (0 = mes
        //    actual, 5 = hace 6 meses).
        // ===============================================================
        $citasPorMes = [5 => 8, 4 => 11, 3 => 14, 2 => 17, 1 => 20, 0 => 7];
        foreach ($citasPorMes as $m => $cantidadMes) {
            for ($c = 0; $c < $cantidadMes; $c++) {
                $fecha = $this->fechaEnMes($m);
                // Mes actual: mezcla realista. Meses pasados: ~75% completadas
                // (mas confirmadas/canceladas puntuales) para una curva clara.
                $estado = $m === 0
                    ? ['pendiente', 'confirmada', 'completada', 'completada', 'cancelada'][array_rand([0, 1, 2, 3, 4])]
                    : (random_int(1, 100) <= 75 ? 'completada' : ['confirmada', 'cancelada'][array_rand([0, 1])]);

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

        // ===============================================================
        // 5) Citas para HOY (panel "Citas de Hoy" - flecha roja) -> 8
        // ===============================================================
        $horasHoy = ['08:00', '09:00', '10:30', '11:00', '12:30', '15:00', '16:30', '17:00'];
        $estadosHoy = ['confirmada', 'confirmada', 'pendiente', 'completada', 'confirmada', 'pendiente', 'completada', 'confirmada'];
        foreach ($horasHoy as $idx => $h) {
            Cita::create([
                'paciente_id' => $pacientes->random()->id,
                'doctor_id' => $doctores->random()->id,
                'fecha' => $hoy->format('Y-m-d'),
                'hora' => $h.':00',
                'motivo' => $motivos[array_rand($motivos)],
                'estado' => $estadosHoy[$idx],
            ]);
        }

        // ===============================================================
        // 5b) Citas PROXIMAS (proximos 30 dias) -> agenda futura.
        //     Mayoritariamente pendiente/confirmada para que el grafico
        //     "Tasa de Pendientes" (flecha roja) muestre un arco visible
        //     y el modulo de Citas tenga una cartera realista por atender.
        // ===============================================================
        $estadosProximos = [
            'pendiente', 'pendiente', 'pendiente', 'pendiente', 'pendiente',
            'pendiente', 'pendiente', 'confirmada', 'confirmada', 'confirmada',
            'confirmada', 'confirmada', 'confirmada', 'confirmada',
        ];
        foreach ($estadosProximos as $idx => $estado) {
            $fecha = $hoy->copy()->addDays(random_int(1, 30));
            Cita::create([
                'paciente_id' => $pacientes->random()->id,
                'doctor_id' => $doctores->random()->id,
                'fecha' => $fecha->format('Y-m-d'),
                'hora' => sprintf('%02d:%02d:00', random_int(8, 18), [0, 30][array_rand([0, 1])]),
                'motivo' => $motivos[array_rand($motivos)],
                'estado' => $estado,
            ]);
        }

        // ===============================================================
        // 6) Presupuestos (12) con items + pagos + cuotas
        // ===============================================================
        for ($m = 0; $m < 6; $m++) {
            for ($n = 0; $n < 2; $n++) {
                $fecha = $this->fechaEnMes($m);
                $paciente = $pacientes->random();
                $doctor = $doctores->random();

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
                    'doctor_id' => $doctor->id,
                    'fecha' => $fecha->format('Y-m-d'),
                    'estado' => $estado,
                    'subtotal' => $subtotal,
                    'descuento' => $descuento,
                    'total' => $total,
                ]);
                $presupuesto->items()->createMany($items);

                if ($estado === 'aprobado' && $total > 0) {
                    $metodos = array_keys(Pago::METODOS);
                    $pagoCompleto = random_int(1, 100) <= 55;
                    $monto = $pagoCompleto ? $total : round($total * (random_int(40, 70) / 100), 2);

                    Pago::create([
                        'paciente_id' => $paciente->id,
                        'presupuesto_id' => $presupuesto->id,
                        'fecha' => $fecha->copy()->addDays(random_int(0, 5))->format('Y-m-d'),
                        'monto' => $monto,
                        'metodo' => $metodos[array_rand($metodos)],
                        'referencia' => 'REF-'.random_int(10000, 99999),
                    ]);

                    // Plan de cuotas para el saldo pendiente.
                    if (! $pagoCompleto) {
                        $saldo = round($total - $monto, 2);
                        $nCuotas = random_int(2, 3);
                        $montoCuota = round($saldo / $nCuotas, 2);
                        for ($q = 1; $q <= $nCuotas; $q++) {
                            Cuota::create([
                                'presupuesto_id' => $presupuesto->id,
                                'numero' => $q,
                                'monto' => $montoCuota,
                                'vence_el' => $fecha->copy()->addMonths($q)->format('Y-m-d'),
                                'pagada' => false,
                            ]);
                        }
                    }
                }
            }
        }

        // ===============================================================
        // 7) Pagos de HOY (para Caja / Ingresos del dia)
        // ===============================================================
        $presAprobados = Presupuesto::where('estado', 'aprobado')->get();
        $metodos = array_keys(Pago::METODOS);
        for ($i = 0; $i < 3 && $presAprobados->count(); $i++) {
            $pre = $presAprobados->random();
            Pago::create([
                'paciente_id' => $pre->paciente_id,
                'presupuesto_id' => $pre->id,
                'fecha' => $hoy->format('Y-m-d'),
                'monto' => round(random_int(200, 1500), 2),
                'metodo' => $metodos[array_rand($metodos)],
                'referencia' => 'REF-'.random_int(10000, 99999),
            ]);
        }

        // ===============================================================
        // 8) Gastos (10) repartidos + algunos de hoy
        // ===============================================================
        $gastosCat = [
            ['insumos', 'Compra de resinas y anestesia'],
            ['laboratorio', 'Trabajo de laboratorio (corona)'],
            ['sueldos', 'Honorarios asistente dental'],
            ['alquiler', 'Alquiler del consultorio'],
            ['servicios', 'Luz, agua e internet'],
            ['equipos', 'Mantenimiento de compresor'],
            ['marketing', 'Publicidad en redes sociales'],
            ['impuestos', 'Pago de impuestos municipales'],
            ['insumos', 'Guantes y barbijos (bioseguridad)'],
            ['otros', 'Gastos varios de oficina'],
        ];
        $metodosGasto = array_keys(Gasto::METODOS);
        foreach ($gastosCat as $i => [$cat, $desc]) {
            $fecha = $i < 2 ? $hoy->copy() : $this->fechaEnMes($i % 6);
            Gasto::create([
                'fecha' => $fecha->format('Y-m-d'),
                'categoria' => $cat,
                'descripcion' => $desc,
                'monto' => round(random_int(150, 3500), 2),
                'metodo' => $metodosGasto[array_rand($metodosGasto)],
                'user_id' => $adminUser?->id,
            ]);
        }

        // ===============================================================
        // 9) Inventario: insumos (10) + movimientos de stock
        // ===============================================================
        $insumosCat = [
            ['Anestesia lidocaina 2%', 'anestesia', 'caja', 40, 10, 45],
            ['Resina compuesta A2', 'restauracion', 'unidad', 25, 8, 60],
            ['Limas endodoncia K', 'endodoncia', 'paquete', 15, 5, 120],
            ['Guantes de nitrilo', 'proteccion', 'caja', 8, 10, 35],   // bajo stock
            ['Barbijos triple capa', 'proteccion', 'caja', 6, 10, 25], // bajo stock
            ['Algodon dental', 'descartable', 'paquete', 30, 5, 12],
            ['Brackets metalicos', 'ortodoncia', 'paquete', 12, 4, 80],
            ['Hilo de sutura', 'cirugia', 'unidad', 20, 6, 18],
            ['Fluor gel', 'limpieza', 'frasco', 18, 5, 22],
            ['Fresas de diamante', 'instrumental', 'unidad', 5, 8, 15],  // bajo stock
        ];
        foreach ($insumosCat as [$nombre, $cat, $unidad, $stock, $min, $costo]) {
            $insumo = Insumo::create([
                'nombre' => $nombre,
                'categoria' => $cat,
                'unidad' => $unidad,
                'stock' => $stock,
                'stock_minimo' => $min,
                'costo_unitario' => $costo,
                'proveedor' => $faker->company(),
                'activo' => true,
            ]);
            // Movimiento de entrada inicial + una salida.
            MovimientoInventario::create([
                'insumo_id' => $insumo->id,
                'user_id' => $adminUser?->id,
                'tipo' => 'entrada',
                'cantidad' => $stock + 10,
                'stock_resultante' => $stock + 10,
                'motivo' => 'Compra inicial',
                'fecha' => $this->fechaEnMes(random_int(1, 5))->format('Y-m-d'),
            ]);
            MovimientoInventario::create([
                'insumo_id' => $insumo->id,
                'user_id' => $adminUser?->id,
                'tipo' => 'salida',
                'cantidad' => 10,
                'stock_resultante' => $stock,
                'motivo' => 'Consumo en tratamientos',
                'fecha' => $this->fechaEnMes(random_int(0, 2))->format('Y-m-d'),
            ]);
        }

        // ===============================================================
        // 10) Evoluciones clinicas (10) y consentimientos
        // ===============================================================
        $dientesFDI = ['11', '16', '21', '26', '36', '37', '46', '47', '24', '14'];
        $notasEvo = [
            'Se realiza profilaxis y aplicacion de fluor. Paciente tolera bien.',
            'Apertura camara pulpar, se instrumenta conducto. Continua proxima sesion.',
            'Obturacion con resina en cara oclusal. Ajuste de oclusion.',
            'Control de ortodoncia, cambio de ligaduras y activacion.',
            'Extraccion sin complicaciones. Indicaciones post-operatorias.',
            'Toma de impresiones para corona. Cementado provisional.',
            'Curetaje por cuadrante. Se indica enjuague con clorhexidina.',
            'Revision general, sin hallazgos patologicos.',
            'Blanqueamiento en consultorio, primera sesion.',
            'Control post-operatorio, cicatrizacion adecuada.',
        ];
        for ($i = 0; $i < 10; $i++) {
            Evolucion::create([
                'paciente_id' => $pacientes->random()->id,
                'user_id' => $doctores->random()->id,
                'fecha' => $this->fechaEnMes($i % 6)->format('Y-m-d'),
                'diente' => $dientesFDI[array_rand($dientesFDI)],
                'descripcion' => $notasEvo[$i],
            ]);
        }
        // Consentimientos firmados para algunos pacientes.
        foreach ($pacientesNuevos->take(10) as $p) {
            Consentimiento::create([
                'paciente_id' => $p->id,
                'user_id' => $doctores->random()->id,
                'tipo' => 'general',
                'titulo' => 'Consentimiento informado de tratamiento',
                'contenido' => 'El paciente declara haber sido informado de los procedimientos, riesgos y alternativas.',
                'firmado' => true,
                'fecha_firma' => $this->fechaEnMes(random_int(0, 3))->format('Y-m-d'),
                'firmante' => $p->nombre.' '.$p->apellido,
            ]);
        }

        // ===============================================================
        // 11) Bitacora de actividad (modulo "Actividad") -> 12 registros
        // ===============================================================
        if (Schema::hasTable('actividades')) {
            $bitacora = [
                ['acceso', 'Autenticacion', 'Inicio de sesion en el sistema'],
                ['creado', 'Paciente', 'Registro de nuevo paciente'],
                ['creado', 'Cita', 'Agendo una nueva cita'],
                ['actualizado', 'Cita', 'Reprogramo una cita'],
                ['creado', 'Presupuesto', 'Genero un presupuesto'],
                ['actualizado', 'Presupuesto', 'Aprobo un presupuesto'],
                ['creado', 'Pago', 'Registro un pago en caja'],
                ['creado', 'Gasto', 'Registro un gasto operativo'],
                ['actualizado', 'Insumo', 'Actualizo el stock de inventario'],
                ['creado', 'Evolucion', 'Registro una evolucion clinica'],
                ['creado', 'Consentimiento', 'Firmo un consentimiento informado'],
                ['salida', 'Autenticacion', 'Cierre de sesion'],
            ];
            foreach ($bitacora as $i => [$accion, $modulo, $desc]) {
                DB::table('actividades')->insert([
                    'user_id' => $doctores->random()->id ?? $adminUser?->id,
                    'accion' => $accion,
                    'modulo' => $modulo,
                    'modelo_id' => null,
                    'descripcion' => $desc,
                    'ip' => '127.0.0.1',
                    'created_at' => $this->fechaEnMes($i % 6)->setTime(random_int(8, 18), random_int(0, 59)),
                    'updated_at' => $this->fechaEnMes($i % 6),
                ]);
            }
        }

        $this->command?->info('OK: datos reiniciados y regenerados (pacientes, citas de HOY + proximas + 6 meses, presupuestos, pagos, cuotas, gastos, inventario, evoluciones, consentimientos y bitacora de actividad).');
    }

    /**
     * Vacia las tablas indicadas (si existen) desactivando temporalmente
     * las llaves foraneas para poder truncar en cualquier orden.
     */
    private function limpiar(array $tablas): void
    {
        Schema::disableForeignKeyConstraints();
        foreach ($tablas as $tabla) {
            if (Schema::hasTable($tabla)) {
                DB::table($tabla)->truncate();
            }
        }
        Schema::enableForeignKeyConstraints();
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
