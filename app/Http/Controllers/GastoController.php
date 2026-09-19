<?php

namespace App\Http\Controllers;

use App\Models\Gasto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GastoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = $request->query('categoria', '');

        $gastos = Gasto::query()
            ->with('user')
            ->when($buscar !== '', fn ($q) => $q->where('descripcion', 'like', "%{$buscar}%"))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->orderByDesc('fecha')->orderByDesc('id')
            ->paginate(15)->withQueryString();

        $stats = [
            'hoy' => (float) Gasto::whereDate('fecha', today())->sum('monto'),
            'mes' => (float) Gasto::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('monto'),
            'registros' => Gasto::count(),
        ];

        return view('gastos.index', compact('gastos', 'stats', 'buscar', 'categoria'));
    }

    public function create(): View
    {
        return view('gastos.create', [
            'gasto' => new Gasto(['fecha' => today()->format('Y-m-d'), 'categoria' => 'insumos', 'metodo' => 'efectivo']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'categoria' => ['required', Rule::in(array_keys(Gasto::CATEGORIAS))],
            'descripcion' => ['required', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'metodo' => ['required', Rule::in(array_keys(Gasto::METODOS))],
        ], [
            'descripcion.required' => 'Ingresa una descripcion.',
            'monto.required' => 'Ingresa el monto.',
            'monto.min' => 'El monto debe ser mayor a cero.',
        ]);

        $data['user_id'] = $request->user()->id;

        Gasto::create($data);

        return redirect()->route('gastos.index')->with('status', 'Gasto registrado correctamente.');
    }

    public function destroy(Gasto $gasto): RedirectResponse
    {
        $gasto->delete();

        return redirect()->route('gastos.index')->with('status', 'Gasto eliminado.');
    }
}
