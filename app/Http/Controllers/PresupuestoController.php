<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Presupuesto;
use App\Models\Tratamiento;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PresupuestoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $estado = $request->query('estado', '');

        $presupuestos = Presupuesto::query()
            ->with(['paciente', 'pagos'])
            ->when($buscar !== '', fn ($q) => $q
                ->where('codigo', 'like', "%{$buscar}%")
                ->orWhereHas('paciente', fn ($s) => $s->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%")))
            ->when($estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderByDesc('fecha')->orderByDesc('id')
            ->paginate(12)->withQueryString();

        $stats = [
            'total' => Presupuesto::count(),
            'aprobados' => Presupuesto::where('estado', 'aprobado')->count(),
            'monto_aprobado' => (float) Presupuesto::where('estado', 'aprobado')->sum('total'),
        ];

        return view('presupuestos.index', compact('presupuestos', 'stats', 'buscar', 'estado'));
    }

    public function create(Request $request): View
    {
        $presupuesto = new Presupuesto([
            'fecha' => today()->format('Y-m-d'),
            'estado' => 'borrador',
            'paciente_id' => $request->query('paciente'),
            'descuento' => 0,
        ]);

        return view('presupuestos.create', $this->formData($presupuesto));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        $presupuesto = DB::transaction(function () use ($data) {
            $calc = $this->calcular($data['items'], $data['descuento'] ?? 0);

            $presupuesto = Presupuesto::create([
                'codigo' => Presupuesto::nuevoCodigo(),
                'paciente_id' => $data['paciente_id'],
                'doctor_id' => $data['doctor_id'] ?? null,
                'fecha' => $data['fecha'],
                'estado' => $data['estado'],
                'subtotal' => $calc['subtotal'],
                'descuento' => $calc['descuento'],
                'total' => $calc['total'],
                'notas' => $data['notas'] ?? null,
            ]);

            $presupuesto->items()->createMany($calc['items']);

            return $presupuesto;
        });

        return redirect()->route('presupuestos.show', $presupuesto)
            ->with('status', 'Presupuesto creado correctamente.');
    }

    public function show(Presupuesto $presupuesto): View
    {
        $presupuesto->load(['items.tratamiento', 'paciente', 'doctor', 'pagos', 'cuotas.pago']);

        return view('presupuestos.show', compact('presupuesto'));
    }

    public function edit(Presupuesto $presupuesto): View
    {
        $presupuesto->load('items');

        return view('presupuestos.edit', $this->formData($presupuesto));
    }

    public function update(Request $request, Presupuesto $presupuesto): RedirectResponse
    {
        $data = $this->validar($request);

        DB::transaction(function () use ($data, $presupuesto) {
            $calc = $this->calcular($data['items'], $data['descuento'] ?? 0);

            $presupuesto->update([
                'paciente_id' => $data['paciente_id'],
                'doctor_id' => $data['doctor_id'] ?? null,
                'fecha' => $data['fecha'],
                'estado' => $data['estado'],
                'subtotal' => $calc['subtotal'],
                'descuento' => $calc['descuento'],
                'total' => $calc['total'],
                'notas' => $data['notas'] ?? null,
            ]);

            $presupuesto->items()->delete();
            $presupuesto->items()->createMany($calc['items']);
        });

        return redirect()->route('presupuestos.show', $presupuesto)
            ->with('status', 'Presupuesto actualizado correctamente.');
    }

    public function destroy(Presupuesto $presupuesto): RedirectResponse
    {
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')->with('status', 'Presupuesto eliminado.');
    }

    /** Marca un item del plan como realizado o pendiente. */
    public function toggleItem(\App\Models\PresupuestoItem $item): RedirectResponse
    {
        $item->update([
            'realizado' => ! $item->realizado,
            'realizado_at' => ! $item->realizado ? now() : null,
        ]);

        return back()->with('status', 'Plan de tratamiento actualizado.');
    }

    public function cambiarEstado(Request $request, Presupuesto $presupuesto): RedirectResponse
    {
        $data = $request->validate(['estado' => ['required', Rule::in(array_keys(Presupuesto::ESTADOS))]]);
        $presupuesto->update($data);

        return back()->with('status', 'Estado del presupuesto actualizado.');
    }

    /** Calcula subtotales, total y normaliza items. */
    private function calcular(array $items, float|int|string $descuento): array
    {
        $subtotal = 0;
        $normalizados = [];

        foreach ($items as $item) {
            $cantidad = max((int) ($item['cantidad'] ?? 1), 1);
            $precio = round((float) ($item['precio_unitario'] ?? 0), 2);
            $sub = round($cantidad * $precio, 2);
            $subtotal += $sub;

            $normalizados[] = [
                'tratamiento_id' => $item['tratamiento_id'] ?? null,
                'descripcion' => $item['descripcion'],
                'cantidad' => $cantidad,
                'precio_unitario' => $precio,
                'subtotal' => $sub,
            ];
        }

        $descuento = min(round((float) $descuento, 2), $subtotal);
        $total = max($subtotal - $descuento, 0);

        return ['subtotal' => $subtotal, 'descuento' => $descuento, 'total' => $total, 'items' => $normalizados];
    }

    /** @return array<string, mixed> */
    private function formData(Presupuesto $presupuesto): array
    {
        return [
            'presupuesto' => $presupuesto,
            'pacientes' => Paciente::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'apellido']),
            'doctores' => User::where('rol', 'doctor')->where('activo', true)->orderBy('name')->get(['id', 'name']),
            'tratamientos' => Tratamiento::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'precio']),
            'estados' => Presupuesto::ESTADOS,
        ];
    }

    /** @return array<string, mixed> */
    private function validar(Request $request): array
    {
        return $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', Rule::in(array_keys(Presupuesto::ESTADOS))],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.descripcion' => ['required', 'string', 'max:200'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'items.*.tratamiento_id' => ['nullable', 'exists:tratamientos,id'],
        ], [
            'paciente_id.required' => 'Selecciona un paciente.',
            'items.required' => 'Agrega al menos un tratamiento al presupuesto.',
            'items.min' => 'Agrega al menos un tratamiento al presupuesto.',
            'items.*.descripcion.required' => 'Cada linea necesita una descripcion.',
            'items.*.cantidad.required' => 'Indica la cantidad.',
            'items.*.precio_unitario.required' => 'Indica el precio unitario.',
        ]);
    }
}
