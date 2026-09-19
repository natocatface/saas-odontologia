<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Gasto;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CajaController extends Controller
{
    /** Arqueo de caja de un dia: ingresos por metodo, egresos por categoria y neto. */
    public function index(Request $request): View
    {
        $fecha = $request->date('fecha') ?? Carbon::today();

        $pagos = Pago::with('paciente')->whereDate('fecha', $fecha)->orderBy('id')->get();
        $gastos = Gasto::with('user')->whereDate('fecha', $fecha)->orderBy('id')->get();

        $ingresosPorMetodo = collect(Pago::METODOS)->map(fn ($label, $k) => [
            'label' => $label,
            'total' => (float) $pagos->where('metodo', $k)->sum('monto'),
        ])->filter(fn ($r) => $r['total'] > 0)->values();

        $egresosPorCategoria = $gastos->groupBy('categoria')->map(fn ($g, $k) => [
            'label' => Gasto::CATEGORIAS[$k] ?? ucfirst($k),
            'total' => (float) $g->sum('monto'),
        ])->values();

        $totalIngresos = (float) $pagos->sum('monto');
        $totalEgresos = (float) $gastos->sum('monto');

        return view('caja.index', [
            'fecha' => $fecha,
            'pagos' => $pagos,
            'gastos' => $gastos,
            'ingresosPorMetodo' => $ingresosPorMetodo,
            'egresosPorCategoria' => $egresosPorCategoria,
            'totalIngresos' => $totalIngresos,
            'totalEgresos' => $totalEgresos,
            'neto' => $totalIngresos - $totalEgresos,
            'config' => Configuracion::todas(),
        ]);
    }
}
