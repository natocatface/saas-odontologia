<?php

namespace App\Http\Controllers;

use App\Models\Cuota;
use App\Models\Pago;
use App\Models\Presupuesto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CuotaController extends Controller
{
    /** Genera un plan de pago dividiendo el saldo en N cuotas. */
    public function generar(Request $request, Presupuesto $presupuesto): RedirectResponse
    {
        $data = $request->validate([
            'numero_cuotas' => ['required', 'integer', 'min:1', 'max:60'],
            'fecha_inicio' => ['required', 'date'],
            'frecuencia' => ['required', 'in:semanal,quincenal,mensual'],
        ], [
            'numero_cuotas.required' => 'Indica el numero de cuotas.',
            'numero_cuotas.max' => 'El maximo es 60 cuotas.',
            'fecha_inicio.required' => 'Indica la fecha de la primera cuota.',
        ]);

        // Solo se planifica el saldo pendiente.
        $saldo = round($presupuesto->saldo, 2);

        if ($saldo <= 0) {
            return back()->with('error', 'Este presupuesto no tiene saldo pendiente para planificar.');
        }

        $n = (int) $data['numero_cuotas'];
        $base = floor(($saldo / $n) * 100) / 100; // redondeo hacia abajo a 2 decimales
        $inicio = Carbon::parse($data['fecha_inicio']);

        DB::transaction(function () use ($presupuesto, $n, $base, $saldo, $inicio, $data) {
            // Reemplaza cualquier plan previo aun no pagado.
            $presupuesto->cuotas()->where('pagada', false)->delete();

            $existentes = $presupuesto->cuotas()->count();
            $acumulado = 0;

            for ($i = 1; $i <= $n; $i++) {
                // La ultima cuota absorbe la diferencia por redondeo.
                $monto = $i === $n ? round($saldo - $acumulado, 2) : $base;
                $acumulado += $monto;

                $vence = match ($data['frecuencia']) {
                    'semanal' => $inicio->copy()->addWeeks($i - 1),
                    'quincenal' => $inicio->copy()->addDays(15 * ($i - 1)),
                    default => $inicio->copy()->addMonthsNoOverflow($i - 1),
                };

                Cuota::create([
                    'presupuesto_id' => $presupuesto->id,
                    'numero' => $existentes + $i,
                    'monto' => $monto,
                    'vence_el' => $vence->toDateString(),
                    'pagada' => false,
                ]);
            }
        });

        return redirect()->route('presupuestos.show', $presupuesto)
            ->with('status', "Plan de {$n} cuotas generado correctamente.");
    }

    /** Registra el pago de una cuota (crea el pago y la marca como pagada). */
    public function pagar(Request $request, Cuota $cuota): RedirectResponse
    {
        if ($cuota->pagada) {
            return back()->with('error', 'Esta cuota ya esta pagada.');
        }

        $presupuesto = $cuota->presupuesto;

        $metodo = $request->validate([
            'metodo' => ['nullable', 'in:'.implode(',', array_keys(Pago::METODOS))],
        ])['metodo'] ?? 'efectivo';

        DB::transaction(function () use ($cuota, $presupuesto, $metodo) {
            $pago = Pago::create([
                'paciente_id' => $presupuesto->paciente_id,
                'presupuesto_id' => $presupuesto->id,
                'fecha' => Carbon::today()->toDateString(),
                'monto' => $cuota->monto,
                'metodo' => $metodo,
                'notas' => "Pago de cuota #{$cuota->numero}",
            ]);

            $cuota->update([
                'pagada' => true,
                'pago_id' => $pago->id,
            ]);
        });

        return back()->with('status', "Cuota #{$cuota->numero} registrada como pagada.");
    }

    /** Elimina el plan de cuotas no pagadas del presupuesto. */
    public function destroyPlan(Presupuesto $presupuesto): RedirectResponse
    {
        $presupuesto->cuotas()->where('pagada', false)->delete();

        return back()->with('status', 'Plan de cuotas pendiente eliminado.');
    }
}
