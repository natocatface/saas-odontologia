<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Pago;
use App\Models\Paciente;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(Request $request): View
    {
        // Rango de fechas (por defecto, ultimos 6 meses).
        try {
            $hasta = $request->filled('hasta') ? Carbon::parse($request->query('hasta')) : Carbon::today();
            $desde = $request->filled('desde') ? Carbon::parse($request->query('desde')) : Carbon::today()->startOfMonth()->subMonths(5);
        } catch (\Throwable $e) {
            $desde = Carbon::today()->startOfMonth()->subMonths(5);
            $hasta = Carbon::today();
        }
        if ($desde->gt($hasta)) {
            [$desde, $hasta] = [$hasta, $desde];
        }

        $desdeF = $desde->copy()->startOfDay();
        $hastaF = $hasta->copy()->endOfDay();

        // ----- KPIs -----
        $ingresos = (float) Pago::whereBetween('fecha', [$desdeF, $hastaF])->sum('monto');
        $numPagos = Pago::whereBetween('fecha', [$desdeF, $hastaF])->count();
        $numCitas = Cita::whereBetween('fecha', [$desdeF, $hastaF])->count();
        $pacientesNuevos = Paciente::whereBetween('created_at', [$desdeF, $hastaF])->count();
        $montoAprobado = (float) Presupuesto::where('estado', 'aprobado')->whereBetween('fecha', [$desdeF, $hastaF])->sum('total');

        // ----- Ingresos por mes -----
        $labelsMes = [];
        $ingresosMes = [];
        $cursor = $desde->copy()->startOfMonth();
        $fin = $hasta->copy()->startOfMonth();
        $guardia = 0;
        while ($cursor->lte($fin) && $guardia < 36) {
            $labelsMes[] = ucfirst($cursor->translatedFormat('M Y'));
            $ingresosMes[] = (float) Pago::whereYear('fecha', $cursor->year)->whereMonth('fecha', $cursor->month)->sum('monto');
            $cursor->addMonth();
            $guardia++;
        }

        // ----- Ingresos por metodo -----
        $porMetodoRaw = Pago::whereBetween('fecha', [$desdeF, $hastaF])
            ->select('metodo', DB::raw('SUM(monto) as total'))->groupBy('metodo')->pluck('total', 'metodo');
        $porMetodo = [];
        foreach (Pago::METODOS as $k => $v) {
            $porMetodo[$v] = (float) ($porMetodoRaw[$k] ?? 0);
        }

        // ----- Citas por estado -----
        $citasEstadoRaw = Cita::whereBetween('fecha', [$desdeF, $hastaF])
            ->select('estado', DB::raw('COUNT(*) as total'))->groupBy('estado')->pluck('total', 'estado');
        $citasEstado = [];
        foreach (Cita::ESTADOS as $k => $v) {
            $citasEstado[$v] = (int) ($citasEstadoRaw[$k] ?? 0);
        }

        // ----- Citas completadas por doctor -----
        $porDoctor = Cita::whereBetween('fecha', [$desdeF, $hastaF])
            ->where('estado', 'completada')
            ->whereNotNull('doctor_id')
            ->select('doctor_id', DB::raw('COUNT(*) as total'))
            ->groupBy('doctor_id')->with('doctor')
            ->get()
            ->map(fn ($r) => ['nombre' => $r->doctor->name ?? 'Sin doctor', 'total' => (int) $r->total])
            ->sortByDesc('total')->values();

        // ----- Top tratamientos -----
        $topTratamientos = DB::table('presupuesto_items')
            ->join('presupuestos', 'presupuestos.id', '=', 'presupuesto_items.presupuesto_id')
            ->whereBetween('presupuestos.fecha', [$desdeF, $hastaF])
            ->select('presupuesto_items.descripcion', DB::raw('SUM(presupuesto_items.cantidad) as veces'), DB::raw('SUM(presupuesto_items.subtotal) as importe'))
            ->groupBy('presupuesto_items.descripcion')
            ->orderByDesc('importe')
            ->limit(10)->get();

        return view('reportes.index', [
            'desde' => $desde->format('Y-m-d'),
            'hasta' => $hasta->format('Y-m-d'),
            'kpis' => compact('ingresos', 'numPagos', 'numCitas', 'pacientesNuevos', 'montoAprobado'),
            'labelsMes' => $labelsMes,
            'ingresosMes' => $ingresosMes,
            'porMetodo' => $porMetodo,
            'citasEstado' => $citasEstado,
            'porDoctor' => $porDoctor,
            'topTratamientos' => $topTratamientos,
        ]);
    }
}
