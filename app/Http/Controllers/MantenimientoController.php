<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Pago;
use App\Models\Presupuesto;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MantenimientoController extends Controller
{
    public function index(): View
    {
        $sistema = [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'entorno' => app()->environment(),
            'debug' => config('app.debug') ? 'Activado' : 'Desactivado',
            'base_datos' => config('database.connections.'.config('database.default').'.database'),
            'driver' => config('database.default'),
            'zona' => config('app.timezone'),
        ];

        $conteos = [
            'Pacientes' => Paciente::count(),
            'Citas' => Cita::count(),
            'Tratamientos' => Tratamiento::count(),
            'Presupuestos' => Presupuesto::count(),
            'Pagos' => Pago::count(),
            'Usuarios' => User::count(),
            'Registros de actividad' => Actividad::count(),
        ];

        return view('mantenimiento.index', compact('sistema', 'conteos'));
    }

    /** Limpia las caches de la aplicacion. */
    public function limpiarCache(): RedirectResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo limpiar la cache: '.$e->getMessage());
        }

        return back()->with('status', 'Cache del sistema limpiada correctamente.');
    }

    /** Genera y descarga un respaldo SQL de la base de datos. */
    public function respaldo(): StreamedResponse
    {
        $nombre = 'backup_'.config('database.connections.'.config('database.default').'.database').'_'.now()->format('Ymd_His').'.sql';

        $headers = [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$nombre}\"",
        ];

        return response()->streamDownload(function () {
            $pdo = DB::getPdo();
            echo "-- OdontoCRM backup\n-- Generado: ".now()->toDateTimeString()."\n\n";
            echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach (DB::select('SHOW TABLES') as $row) {
                $tabla = array_values((array) $row)[0];

                $createRow = (array) DB::select("SHOW CREATE TABLE `{$tabla}`")[0];
                $create = $createRow['Create Table'] ?? null;
                if (! $create) {
                    continue;
                }

                echo "DROP TABLE IF EXISTS `{$tabla}`;\n";
                echo $create.";\n\n";

                foreach (DB::table($tabla)->cursor() as $registro) {
                    $valores = array_map(function ($v) use ($pdo) {
                        return $v === null ? 'NULL' : $pdo->quote((string) $v);
                    }, (array) $registro);

                    echo "INSERT INTO `{$tabla}` VALUES (".implode(', ', $valores).");\n";
                }
                echo "\n";
            }

            echo "SET FOREIGN_KEY_CHECKS=1;\n";
        }, $nombre, $headers);
    }

    /** Restaura la base de datos desde un archivo de respaldo SQL. */
    public function restaurar(Request $request): RedirectResponse
    {
        $request->validate([
            'respaldo' => ['required', 'file', 'max:51200'], // 50 MB
        ], [
            'respaldo.required' => 'Selecciona un archivo de respaldo.',
            'respaldo.max' => 'El archivo supera el limite de 50 MB.',
        ]);

        $archivo = $request->file('respaldo');
        $extension = strtolower($archivo->getClientOriginalExtension());

        if ($extension !== 'sql') {
            return back()->with('error', 'El archivo debe tener extension .sql');
        }

        $sql = file_get_contents($archivo->getRealPath());

        if ($sql === false || trim($sql) === '') {
            return back()->with('error', 'El archivo de respaldo esta vacio o no se pudo leer.');
        }

        // Validacion basica: debe parecer un respaldo de esta aplicacion.
        if (! str_contains($sql, 'CREATE TABLE') && ! str_contains($sql, 'INSERT INTO')) {
            return back()->with('error', 'El archivo no parece un respaldo SQL valido.');
        }

        try {
            DB::unprepared("SET FOREIGN_KEY_CHECKS=0;\n".$sql."\nSET FOREIGN_KEY_CHECKS=1;");
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo restaurar el respaldo: '.$e->getMessage());
        }

        Actividad::registrar('actualizado', null, 'Restauro un respaldo de la base de datos');

        return back()->with('status', 'Respaldo restaurado correctamente.');
    }
}
