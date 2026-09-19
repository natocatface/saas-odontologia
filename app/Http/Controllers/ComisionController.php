<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ComisionController extends Controller
{
    /** Comisiones por doctor en un mes: produccion cobrada y comision segun %. */
    public function index(Request $request): View
    {
        // Mes en formato YYYY-MM; por defecto el mes actual.
        $mesParam = (string) $request->query('mes', Carbon::today()->format('Y-m'));
        try {
            $mes = Carbon::createFromFormat('Y-m', $mesParam)->startOfMonth();
        } catch (\Throwable $e) {
            $mes = Carbon::today()->startOfMonth();
        }

        $inicio = $mes->copy()->startOfMonth()->toDateString();
        $fin = $mes->copy()->endOfMonth()->toDateString();

        $doctores = User::where('rol', 'doctor')->orderBy('name')->get();

        $filas = $doctores->map(function (User $doc) use ($inicio, $fin) {
            $produccion = (float) Pago::query()
                ->whereBetween('fecha', [$inicio, $fin])
                ->whereHas('presupuesto', fn ($q) => $q->where('doctor_id', $doc->id))
                ->sum('monto');

            $porcentaje = (float) $doc->comision;
            $comision = round($produccion * $porcentaje / 100, 2);

            return [
                'doctor' => $doc,
                'produccion' => $produccion,
                'porcentaje' => $porcentaje,
                'comision' => $comision,
            ];
        });

        return view('comisiones.index', [
            'mes' => $mes,
            'mesParam' => $mes->format('Y-m'),
            'filas' => $filas,
            'totalProduccion' => (float) $filas->sum('produccion'),
            'totalComision' => (float) $filas->sum('comision'),
        ]);
    }
}
