<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use App\Models\Pago;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class PortalController extends Controller
{
    private function paciente()
    {
        return auth('paciente')->user();
    }

    public function dashboard(): View
    {
        $paciente = $this->paciente();

        $proximas = $paciente->citas()
            ->with('doctor')
            ->whereDate('fecha', '>=', Carbon::today())
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->orderBy('fecha')->orderBy('hora')
            ->limit(5)->get();

        $paciente->load(['presupuestos', 'pagos']);

        return view('portal.dashboard', [
            'paciente' => $paciente,
            'proximas' => $proximas,
            'config' => Configuracion::todas(),
        ]);
    }

    public function citas(): View
    {
        $paciente = $this->paciente();

        $citas = $paciente->citas()->with('doctor')
            ->orderByDesc('fecha')->orderByDesc('hora')->paginate(15);

        return view('portal.citas', compact('paciente', 'citas'));
    }

    public function presupuestos(): View
    {
        $paciente = $this->paciente();

        $presupuestos = $paciente->presupuestos()->with('pagos')->get();

        return view('portal.presupuestos', compact('paciente', 'presupuestos'));
    }

    public function estadoCuenta(): View
    {
        $paciente = $this->paciente();
        $paciente->load(['presupuestos.cuotas', 'pagos.presupuesto']);

        return view('portal.estado-cuenta', [
            'paciente' => $paciente,
            'config' => Configuracion::todas(),
        ]);
    }

    /** Recibo de un pago, solo si pertenece al paciente autenticado. */
    public function recibo(Pago $pago): View
    {
        abort_unless($pago->paciente_id === $this->paciente()->id, 403);

        $pago->load(['paciente', 'presupuesto']);

        return view('pagos.recibo', [
            'pago' => $pago,
            'config' => Configuracion::todas(),
        ]);
    }
}
