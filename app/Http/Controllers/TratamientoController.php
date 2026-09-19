<?php

namespace App\Http\Controllers;

use App\Models\Tratamiento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TratamientoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = $request->query('categoria', '');

        $tratamientos = Tratamiento::query()
            ->when($buscar !== '', fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->orderBy('categoria')->orderBy('nombre')
            ->paginate(12)->withQueryString();

        $stats = [
            'total' => Tratamiento::count(),
            'activos' => Tratamiento::where('activo', true)->count(),
            'promedio' => (float) Tratamiento::where('activo', true)->avg('precio'),
        ];

        $categorias = Tratamiento::CATEGORIAS;

        return view('tratamientos.index', compact('tratamientos', 'stats', 'categorias', 'buscar', 'categoria'));
    }

    public function create(): View
    {
        return view('tratamientos.create', [
            'tratamiento' => new Tratamiento(['activo' => true]),
            'categorias' => Tratamiento::CATEGORIAS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Tratamiento::create($this->validar($request));

        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento creado correctamente.');
    }

    public function edit(Tratamiento $tratamiento): View
    {
        return view('tratamientos.edit', [
            'tratamiento' => $tratamiento,
            'categorias' => Tratamiento::CATEGORIAS,
        ]);
    }

    public function update(Request $request, Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->update($this->validar($request));

        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento actualizado correctamente.');
    }

    public function destroy(Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->delete();

        return redirect()->route('tratamientos.index')->with('status', 'Tratamiento eliminado.');
    }

    public function toggleEstado(Tratamiento $tratamiento): RedirectResponse
    {
        $tratamiento->update(['activo' => ! $tratamiento->activo]);

        return back()->with('status', 'Estado del tratamiento actualizado.');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'categoria' => ['nullable', Rule::in(Tratamiento::CATEGORIAS)],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'precio' => ['required', 'numeric', 'min:0'],
            'duracion_min' => ['nullable', 'integer', 'min:0', 'max:600'],
            'activo' => ['nullable', 'boolean'],
        ], [
            'nombre.required' => 'El nombre del tratamiento es obligatorio.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un numero.',
        ]) + ['activo' => $request->boolean('activo')];
    }
}
