<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\User;
use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CitaController extends Controller
{
    public function index(Request $request): View
    {
        $fecha = $request->query('fecha', '');
        $estado = $request->query('estado', '');
        $doctorId = $request->query('doctor', '');
        $buscar = trim((string) $request->query('buscar', ''));

        $citas = Cita::query()
            ->with(['paciente', 'doctor'])
            ->when($fecha !== '', fn ($q) => $q->whereDate('fecha', $fecha))
            ->when($estado !== '', fn ($q) => $q->where('estado', $estado))
            ->when($doctorId !== '', fn ($q) => $q->where('doctor_id', $doctorId))
            ->when($buscar !== '', fn ($q) => $q->whereHas('paciente', function ($sub) use ($buscar) {
                $sub->where('nombre', 'like', "%{$buscar}%")->orWhere('apellido', 'like', "%{$buscar}%");
            }))
            ->orderByDesc('fecha')
            ->orderBy('hora')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'hoy' => Cita::whereDate('fecha', today())->count(),
            'pendientes' => Cita::where('estado', 'pendiente')->count(),
            'confirmadas' => Cita::where('estado', 'confirmada')->count(),
            'completadas' => Cita::where('estado', 'completada')
                ->whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count(),
        ];

        $doctores = User::where('rol', 'doctor')->where('activo', true)->orderBy('name')->get();

        return view('citas.index', compact('citas', 'stats', 'doctores', 'fecha', 'estado', 'doctorId', 'buscar'));
    }

    /** Vista de calendario mensual. */
    public function calendario(Request $request): View
    {
        try {
            $mesActual = $request->filled('mes')
                ? \Illuminate\Support\Carbon::createFromFormat('Y-m', $request->query('mes'))->startOfMonth()
                : \Illuminate\Support\Carbon::today()->startOfMonth();
        } catch (\Throwable $e) {
            $mesActual = \Illuminate\Support\Carbon::today()->startOfMonth();
        }

        $doctorId = $request->query('doctor', '');

        $inicioGrid = $mesActual->copy()->startOfWeek(\Illuminate\Support\Carbon::MONDAY);
        $finGrid = $mesActual->copy()->endOfMonth()->endOfWeek(\Illuminate\Support\Carbon::SUNDAY);

        $citas = Cita::query()
            ->with(['paciente', 'doctor'])
            ->whereBetween('fecha', [$inicioGrid->toDateString(), $finGrid->toDateString()])
            ->when($doctorId !== '', fn ($q) => $q->where('doctor_id', $doctorId))
            ->orderBy('hora')
            ->get()
            ->groupBy(fn ($c) => $c->fecha->format('Y-m-d'));

        // Construir matriz de dias.
        $dias = [];
        $cursor = $inicioGrid->copy();
        while ($cursor->lte($finGrid)) {
            $dias[] = $cursor->copy();
            $cursor->addDay();
        }

        $doctores = User::where('rol', 'doctor')->where('activo', true)->orderBy('name')->get();

        return view('citas.calendario', [
            'mesActual' => $mesActual,
            'dias' => $dias,
            'citas' => $citas,
            'doctores' => $doctores,
            'doctorId' => $doctorId,
            'mesAnterior' => $mesActual->copy()->subMonthNoOverflow()->format('Y-m'),
            'mesSiguiente' => $mesActual->copy()->addMonthNoOverflow()->format('Y-m'),
        ]);
    }

    public function create(Request $request): View
    {
        $cita = new Cita([
            'fecha' => $request->query('fecha', today()->format('Y-m-d')),
            'estado' => 'pendiente',
            'paciente_id' => $request->query('paciente'),
        ]);

        return view('citas.create', $this->formData($cita));
    }

    public function store(Request $request): RedirectResponse
    {
        $cita = Cita::create($this->validar($request));

        return redirect()->route('citas.index')
            ->with('status', 'Cita registrada correctamente.');
    }

    /** API ligera: horas ocupadas de un doctor/silla en una fecha. */
    public function disponibilidad(Request $request)
    {
        $fecha = $request->query('fecha');
        $doctorId = $request->query('doctor');
        $silla = $request->query('silla');
        $exceptoId = $request->query('excepto');

        $base = Cita::query()
            ->whereDate('fecha', $fecha)
            ->where('estado', '!=', 'cancelada')
            ->when($exceptoId, fn ($q) => $q->where('id', '!=', $exceptoId));

        return response()->json([
            'doctor' => $doctorId
                ? (clone $base)->where('doctor_id', $doctorId)->whereNotNull('hora')->pluck('hora')->map(fn ($h) => substr((string) $h, 0, 5))->values()
                : [],
            'silla' => $silla
                ? (clone $base)->where('silla', $silla)->whereNotNull('hora')->pluck('hora')->map(fn ($h) => substr((string) $h, 0, 5))->values()
                : [],
        ]);
    }

    public function edit(Cita $cita): View
    {
        return view('citas.edit', $this->formData($cita));
    }

    public function update(Request $request, Cita $cita): RedirectResponse
    {
        $cita->update($this->validar($request, $cita));

        return redirect()->route('citas.index')
            ->with('status', 'Cita actualizada correctamente.');
    }

    public function destroy(Cita $cita): RedirectResponse
    {
        $cita->delete();

        return redirect()->route('citas.index')->with('status', 'Cita eliminada.');
    }

    /** Envia (o reenvia) el recordatorio por email al paciente. */
    public function enviarRecordatorio(Cita $cita): RedirectResponse
    {
        $cita->load(['paciente', 'doctor']);

        if (empty($cita->paciente?->email)) {
            return back()->with('error', 'El paciente no tiene correo registrado.');
        }

        try {
            \Illuminate\Support\Facades\Mail::to($cita->paciente->email)
                ->send(new \App\Mail\RecordatorioCita($cita));

            $cita->update(['recordatorio_enviado_en' => now()]);
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo enviar el recordatorio: '.$e->getMessage());
        }

        return back()->with('status', 'Recordatorio enviado a '.$cita->paciente->email.'.');
    }

    /** Cambio rapido de estado desde el listado. */
    public function cambiarEstado(Request $request, Cita $cita): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(array_keys(Cita::ESTADOS))],
        ]);

        $cita->update($data);

        return back()->with('status', 'Estado de la cita actualizado.');
    }

    /** @return array<string, mixed> */
    private function formData(Cita $cita): array
    {
        return [
            'cita' => $cita,
            'pacientes' => Paciente::where('activo', true)->orderBy('nombre')->get(['id', 'nombre', 'apellido']),
            'doctores' => User::where('rol', 'doctor')->where('activo', true)->orderBy('name')->get(['id', 'name', 'especialidad']),
            'estados' => Cita::ESTADOS,
        ];
    }

    /** @return array<string, mixed> */
    private function validar(Request $request, ?Cita $cita = null): array
    {
        $maxSillas = (int) Configuracion::valor('num_sillas', '3');

        $data = $request->validate([
            'paciente_id' => ['required', 'exists:pacientes,id'],
            'doctor_id' => ['nullable', 'exists:users,id'],
            'silla' => ['nullable', 'integer', 'min:1', 'max:'.max($maxSillas, 1)],
            'fecha' => ['required', 'date'],
            'hora' => ['nullable', 'date_format:H:i'],
            'motivo' => ['nullable', 'string', 'max:150'],
            'estado' => ['required', Rule::in(array_keys(Cita::ESTADOS))],
            'notas' => ['nullable', 'string', 'max:1000'],
        ], [
            'paciente_id.required' => 'Selecciona un paciente.',
            'fecha.required' => 'La fecha es obligatoria.',
            'hora.date_format' => 'La hora debe tener formato HH:MM.',
            'silla.max' => 'Esa silla no existe. Hay '.max($maxSillas, 1).' silla(s) configurada(s).',
        ]);

        $this->verificarDisponibilidad($data, $cita);

        return $data;
    }

    /**
     * Evita solapamientos: el mismo doctor o la misma silla no pueden tener
     * dos citas a la misma fecha y hora (se ignoran las canceladas).
     *
     * @param  array<string, mixed>  $data
     */
    private function verificarDisponibilidad(array $data, ?Cita $cita): void
    {
        // Sin hora no hay franja que validar; una cita cancelada no ocupa lugar.
        if (empty($data['hora']) || ($data['estado'] ?? null) === 'cancelada') {
            return;
        }

        $base = Cita::query()
            ->whereDate('fecha', $data['fecha'])
            ->where('hora', $data['hora'])
            ->where('estado', '!=', 'cancelada')
            ->when($cita, fn ($q) => $q->where('id', '!=', $cita->id));

        if (! empty($data['doctor_id'])) {
            $ocupada = (clone $base)->where('doctor_id', $data['doctor_id'])
                ->with('paciente')->first();

            if ($ocupada) {
                throw ValidationException::withMessages([
                    'hora' => 'El doctor ya tiene una cita a esa hora ('
                        .($ocupada->paciente->nombre_completo ?? 'otro paciente').').',
                ]);
            }
        }

        if (! empty($data['silla'])) {
            $ocupada = (clone $base)->where('silla', $data['silla'])
                ->with('paciente')->first();

            if ($ocupada) {
                throw ValidationException::withMessages([
                    'silla' => 'La silla '.$data['silla'].' ya esta ocupada a esa hora ('
                        .($ocupada->paciente->nombre_completo ?? 'otro paciente').').',
                ]);
            }
        }
    }
}
