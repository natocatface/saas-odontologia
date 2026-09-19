<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $hoy = Carbon::today();

        $totalPacientes = Paciente::where('activo', true)->count();
        $citasHoy = Cita::whereDate('fecha', $hoy)->count();
        $pendientes = Cita::where('estado', 'pendiente')->count();
        $citasMes = Cita::whereMonth('fecha', $hoy->month)->whereYear('fecha', $hoy->year)->count();
        $completadasMes = Cita::where('estado', 'completada')
            ->whereMonth('fecha', $hoy->month)->whereYear('fecha', $hoy->year)->count();

        // Ingresos del mes (suma de pagos registrados).
        $ingresosMes = (float) \App\Models\Pago::whereMonth('fecha', $hoy->month)
            ->whereYear('fecha', $hoy->year)->sum('monto');

        // Tendencias para los KPI.
        $mesAnterior = $hoy->copy()->subMonthNoOverflow();
        $ingresosMesAnterior = (float) \App\Models\Pago::whereMonth('fecha', $mesAnterior->month)
            ->whereYear('fecha', $mesAnterior->year)->sum('monto');
        $deltaIngresos = $ingresosMesAnterior > 0
            ? round((($ingresosMes - $ingresosMesAnterior) / $ingresosMesAnterior) * 100)
            : ($ingresosMes > 0 ? 100 : 0);

        $pacientesNuevosMes = Paciente::whereMonth('created_at', $hoy->month)
            ->whereYear('created_at', $hoy->year)->count();
        $citasConfirmadasHoy = Cita::whereDate('fecha', $hoy)->where('estado', 'confirmada')->count();

        $totalCitas = max(Cita::count(), 1);
        $tasaPendientes = round(($pendientes / $totalCitas) * 100);

        // Citas de hoy con sus relaciones.
        $citasDelDia = Cita::with(['paciente', 'doctor'])
            ->whereDate('fecha', $hoy)
            ->orderBy('hora')
            ->take(8)
            ->get();

        // Tendencia de citas: ultimos 6 meses.
        $chartMeses = [];
        $chartCitasMes = [];
        $chartCompletadasMes = [];
        for ($i = 5; $i >= 0; $i--) {
            $ref = $hoy->copy()->startOfMonth()->subMonths($i);
            $chartMeses[] = ucfirst($ref->translatedFormat('M'));
            $chartCitasMes[] = Cita::whereYear('fecha', $ref->year)->whereMonth('fecha', $ref->month)->count();
            $chartCompletadasMes[] = Cita::where('estado', 'completada')
                ->whereYear('fecha', $ref->year)->whereMonth('fecha', $ref->month)->count();
        }

        // Distribucion de citas por estado.
        $estadosCount = Cita::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')->pluck('total', 'estado');
        $chartEstados = [
            'Pendiente' => (int) ($estadosCount['pendiente'] ?? 0),
            'Confirmada' => (int) ($estadosCount['confirmada'] ?? 0),
            'Completada' => (int) ($estadosCount['completada'] ?? 0),
            'Cancelada' => (int) ($estadosCount['cancelada'] ?? 0),
        ];

        // Rendimiento por doctor.
        $rendimiento = User::where('rol', 'doctor')
            ->where('activo', true)
            ->withCount([
                'citas as total_citas',
                'citas as completadas' => fn ($q) => $q->where('estado', 'completada'),
                'citas as pendientes_count' => fn ($q) => $q->where('estado', 'pendiente'),
            ])
            ->orderByDesc('completadas')
            ->get();

        return view('dashboard', compact(
            'totalPacientes',
            'citasHoy',
            'pendientes',
            'ingresosMes',
            'citasMes',
            'completadasMes',
            'tasaPendientes',
            'deltaIngresos',
            'pacientesNuevosMes',
            'citasConfirmadasHoy',
            'citasDelDia',
            'rendimiento',
            'chartMeses',
            'chartCitasMes',
            'chartCompletadasMes',
            'chartEstados',
        ));
    }
}
