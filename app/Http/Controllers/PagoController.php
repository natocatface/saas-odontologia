<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Paciente;
use App\Models\Presupuesto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $metodo = $request->query('metodo', '');

        $pagos = Pago::query()
            ->with(['paciente', 'presupuesto'])
            ->when($buscar !== '', fn ($q) => $q->whereHas('paciente', fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%")))
            ->when($metodo !== '', fn ($q) => $q->where('metodo', $metodo))
            ->orderByDesc('fecha')->orderByDesc('id')
            ->paginate(15)->withQueryString();

        $stats = [
            'hoy' => (float) Pago::whereDate('fecha', today())->sum('monto'),
            'mes' => (float) Pago::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('monto'),
            'registros' => Pago::count(),
        ];

        return view('pagos.index', compact('pagos', 'stats', 'buscar', 'metodo'));
    }

    public function create(Request $request): View
    {
        $presupuesto = null;
        $pago = new Pago(['fecha' => today()->format('Y-m-d'), 'metodo' => 'efectivo']);

        if ($request->filled('presupuesto')) {
            $presupuesto = Presupuesto::with('pagos')->find($request->query('presupuesto'));
            if ($presupuesto) {
                $pago->paciente_id = $presupuesto->paciente_id;
                $pago->presupuesto_id = $presupuesto->id;
                $pago->monto = $presupuesto->saldo;
            }
        }

        return view('pagos.create', [
            'pago' => $pago,
            'presupuestoSel' => $presupuesto,
            'pacientes' => Paciente::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'apellido']),
            'metodos' => Pago::METODOS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'presupuesto_id' => ['nullable', 'exists:presupuestos,id'],
            'fecha' => ['required', 'date'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'metodo' => ['required', Rule::in(array_keys(Pago::METODOS))],
            'referencia' => ['nullable', 'string', 'max:100'],
            'notas' => ['nullable', 'string', 'max:1000'],
        ], [
            'paciente_id.required' => 'Selecciona un paciente.',
            'monto.required' => 'Ingresa el monto.',
            'monto.min' => 'El monto debe ser mayor a cero.',
        ]);

        $pago = Pago::create($data);

        $mensaje = 'Pago registrado correctamente.';

        // Emision automatica del comprobante electronico (si esta habilitado).
        // Nunca debe bloquear el registro del pago: cualquier fallo se informa
        // como aviso pero el pago ya quedo guardado.
        try {
            $emisor = app(\App\Services\EmisionComprobantes::class);
            if ($emisor->debeEmitirAuto()) {
                $resultado = $emisor->emitirDesdePago($pago);
                if ($resultado !== null) {
                    $mensaje .= $resultado->exito
                        ? ' Comprobante emitido: '.($resultado->referenciaExterna ?: 'aceptado por SUNAT').'.'
                        : ' Aviso facturacion: '.($resultado->mensaje ?: 'no se pudo emitir el comprobante').'.';
                }
            }
        } catch (\Throwable $e) {
            $mensaje .= ' (No se pudo emitir el comprobante: '.$e->getMessage().')';
        }

        if ($pago->presupuesto_id) {
            return redirect()->route('presupuestos.show', $pago->presupuesto_id)
                ->with('status', $mensaje);
        }

        return redirect()->route('pagos.index')->with('status', $mensaje);
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('pagos.index')->with('status', 'Pago eliminado.');
    }

    /** Recibo imprimible del pago (apto para guardar como PDF). */
    public function recibo(Pago $pago): View
    {
        $pago->load(['paciente', 'presupuesto']);

        return view('pagos.recibo', [
            'pago' => $pago,
            'config' => \App\Models\Configuracion::todas(),
        ]);
    }
}
