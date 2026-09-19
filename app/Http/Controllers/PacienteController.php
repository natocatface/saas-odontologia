<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PacienteController extends Controller
{
    /** Listado con busqueda y filtros. */
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $estado = $request->query('estado', '');

        $pacientes = Paciente::query()
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->where(function ($sub) use ($buscar) {
                    $sub->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido', 'like', "%{$buscar}%")
                        ->orWhere('documento', 'like', "%{$buscar}%")
                        ->orWhere('telefono', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->when($estado === 'activos', fn ($q) => $q->where('activo', true))
            ->when($estado === 'inactivos', fn ($q) => $q->where('activo', false))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Paciente::count(),
            'activos' => Paciente::where('activo', true)->count(),
            'nuevos' => Paciente::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];

        return view('pacientes.index', compact('pacientes', 'buscar', 'estado', 'stats'));
    }

    public function create(): View
    {
        return view('pacientes.create', ['paciente' => new Paciente()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);
        unset($data['portal_password']);
        $paciente = Paciente::create($data);
        $this->aplicarAccesoPortal($request, $paciente);

        return redirect()->route('pacientes.show', $paciente)
            ->with('status', 'Paciente registrado correctamente.');
    }

    public function show(Paciente $paciente): View
    {
        $paciente->load([
            'citas' => fn ($q) => $q->with('doctor')->orderByDesc('fecha')->limit(20),
            'evoluciones.user',
            'archivos',
            'consentimientos.user',
        ]);

        return view('pacientes.show', compact('paciente'));
    }

    public function edit(Paciente $paciente): View
    {
        return view('pacientes.edit', compact('paciente'));
    }

    public function update(Request $request, Paciente $paciente): RedirectResponse
    {
        $data = $this->validar($request, $paciente);
        unset($data['portal_password']);
        $paciente->update($data);
        $this->aplicarAccesoPortal($request, $paciente);

        return redirect()->route('pacientes.show', $paciente)
            ->with('status', 'Paciente actualizado correctamente.');
    }

    public function destroy(Paciente $paciente): RedirectResponse
    {
        $paciente->delete();

        return redirect()->route('pacientes.index')
            ->with('status', 'Paciente eliminado.');
    }

    /** Estado de cuenta del paciente: facturado, pagado, saldo y cuotas. */
    public function estadoCuenta(Paciente $paciente): View
    {
        $paciente->load([
            'presupuestos.cuotas',
            'pagos.presupuesto',
        ]);

        $cuotasPendientes = $paciente->presupuestos
            ->flatMap->cuotas
            ->where('pagada', false)
            ->sortBy('vence_el')
            ->values();

        return view('pacientes.estado-cuenta', [
            'paciente' => $paciente,
            'cuotasPendientes' => $cuotasPendientes,
            'config' => \App\Models\Configuracion::todas(),
        ]);
    }

    /** Muestra el odontograma del paciente. */
    public function odontograma(Paciente $paciente): View
    {
        return view('pacientes.odontograma', compact('paciente'));
    }

    /** Guarda el odontograma del paciente. */
    public function guardarOdontograma(Request $request, Paciente $paciente): RedirectResponse
    {
        $request->validate(['odontograma' => ['nullable', 'string']]);

        $datos = json_decode((string) $request->input('odontograma'), true);
        $paciente->update(['odontograma' => is_array($datos) ? $datos : []]);

        return redirect()->route('pacientes.odontograma', $paciente)
            ->with('status', 'Odontograma guardado correctamente.');
    }

    /** Activar / desactivar paciente. */
    public function toggleEstado(Paciente $paciente): RedirectResponse
    {
        $paciente->update(['activo' => ! $paciente->activo]);

        return back()->with('status', 'Estado del paciente actualizado.');
    }

    /** Reglas de validacion compartidas. */
    private function validar(Request $request, ?Paciente $paciente = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'documento' => ['nullable', 'string', 'max:30', Rule::unique('pacientes', 'documento')->ignore($paciente)],
            'telefono' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'fecha_nacimiento' => ['nullable', 'date', 'before_or_equal:today'],
            'genero' => ['nullable', Rule::in(['M', 'F', 'O'])],
            'tipo_sangre' => ['nullable', Rule::in(Paciente::TIPOS_SANGRE)],
            'direccion' => ['nullable', 'string', 'max:255'],
            'alergias' => ['nullable', 'string', 'max:1000'],
            'enfermedades' => ['nullable', 'array'],
            'enfermedades.*' => [Rule::in(array_keys(Paciente::ENFERMEDADES))],
            'medicacion' => ['nullable', 'string', 'max:1000'],
            'habitos' => ['nullable', 'array'],
            'habitos.*' => [Rule::in(array_keys(Paciente::HABITOS))],
            'antecedentes_notas' => ['nullable', 'string', 'max:2000'],
            'observaciones' => ['nullable', 'string', 'max:2000'],
            'activo' => ['nullable', 'boolean'],
            'portal_activo' => ['nullable', 'boolean'],
            'portal_password' => ['nullable', 'string', 'min:6', 'max:100'],
        ], [
            'portal_password.min' => 'La clave del portal debe tener al menos 6 caracteres.',
            'nombre.required' => 'El nombre es obligatorio.',
            'apellido.required' => 'El apellido es obligatorio.',
            'documento.unique' => 'Ya existe un paciente con ese documento.',
            'email.email' => 'Ingresa un correo valido.',
            'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser futura.',
        ]) + [
            'activo' => $request->boolean('activo'),
            'portal_activo' => $request->boolean('portal_activo'),
            'enfermedades' => $request->input('enfermedades', []),
            'habitos' => $request->input('habitos', []),
        ];
    }

    /** Aplica la contrasena del portal si se proporciono. */
    private function aplicarAccesoPortal(Request $request, Paciente $paciente): void
    {
        if ($request->filled('portal_password')) {
            // El cast 'hashed' del modelo se encarga de cifrarla.
            $paciente->update(['password' => $request->input('portal_password')]);
        }
    }
}
