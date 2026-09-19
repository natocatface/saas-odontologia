<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InsumoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', ''));
        $categoria = $request->query('categoria', '');
        $soloBajos = $request->boolean('bajos');

        $insumos = Insumo::query()
            ->when($buscar !== '', fn ($q) => $q->where(fn ($s) => $s
                ->where('nombre', 'like', "%{$buscar}%")->orWhere('proveedor', 'like', "%{$buscar}%")))
            ->when($categoria !== '', fn ($q) => $q->where('categoria', $categoria))
            ->when($soloBajos, fn ($q) => $q->whereColumn('stock', '<=', 'stock_minimo'))
            ->orderBy('nombre')
            ->paginate(15)->withQueryString();

        $stats = [
            'total' => Insumo::count(),
            'bajos' => Insumo::whereColumn('stock', '<=', 'stock_minimo')->count(),
            'valor' => (float) Insumo::select(DB::raw('COALESCE(SUM(stock * COALESCE(costo_unitario,0)),0) as v'))->value('v'),
        ];

        return view('insumos.index', compact('insumos', 'stats', 'buscar', 'categoria', 'soloBajos'));
    }

    public function create(): View
    {
        return view('insumos.create', ['insumo' => new Insumo(['unidad' => 'unidad', 'categoria' => 'general', 'activo' => true])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);
        $insumo = Insumo::create($data);

        if ((float) $insumo->stock > 0) {
            $insumo->movimientos()->create([
                'user_id' => $request->user()->id,
                'tipo' => 'entrada',
                'cantidad' => $insumo->stock,
                'stock_resultante' => $insumo->stock,
                'motivo' => 'Stock inicial',
                'fecha' => now()->toDateString(),
            ]);
        }

        return redirect()->route('insumos.show', $insumo)->with('status', 'Insumo creado correctamente.');
    }

    public function show(Insumo $insumo): View
    {
        $insumo->load(['movimientos.user']);

        return view('insumos.show', compact('insumo'));
    }

    public function edit(Insumo $insumo): View
    {
        return view('insumos.edit', compact('insumo'));
    }

    public function update(Request $request, Insumo $insumo): RedirectResponse
    {
        // Al editar no se toca el stock directamente (se ajusta por movimientos).
        $data = $this->validar($request, $insumo);
        unset($data['stock']);
        $insumo->update($data);

        return redirect()->route('insumos.show', $insumo)->with('status', 'Insumo actualizado.');
    }

    public function destroy(Insumo $insumo): RedirectResponse
    {
        $insumo->delete();

        return redirect()->route('insumos.index')->with('status', 'Insumo eliminado.');
    }

    /** Registra un movimiento (entrada/salida/ajuste) y actualiza el stock. */
    public function movimiento(Request $request, Insumo $insumo): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', Rule::in(array_keys(\App\Models\MovimientoInventario::TIPOS))],
            'cantidad' => ['required', 'numeric', 'min:0.01'],
            'motivo' => ['nullable', 'string', 'max:150'],
            'fecha' => ['required', 'date'],
        ], [
            'cantidad.required' => 'Indica la cantidad.',
            'cantidad.min' => 'La cantidad debe ser mayor a cero.',
        ]);

        DB::transaction(function () use ($insumo, $data, $request) {
            $insumo->refresh();
            $stock = (float) $insumo->stock;
            $cant = (float) $data['cantidad'];

            $nuevo = match ($data['tipo']) {
                'entrada' => $stock + $cant,
                'salida' => max($stock - $cant, 0),
                'ajuste' => $cant, // el ajuste fija el stock al valor indicado
            };

            $insumo->update(['stock' => $nuevo]);

            $insumo->movimientos()->create([
                'user_id' => $request->user()->id,
                'tipo' => $data['tipo'],
                'cantidad' => $cant,
                'stock_resultante' => $nuevo,
                'motivo' => $data['motivo'] ?? null,
                'fecha' => $data['fecha'],
            ]);
        });

        return back()->with('status', 'Movimiento registrado.');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request, ?Insumo $insumo = null): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'categoria' => ['required', Rule::in(array_keys(Insumo::CATEGORIAS))],
            'unidad' => ['required', Rule::in(Insumo::UNIDADES)],
            'stock' => ['nullable', 'numeric', 'min:0'],
            'stock_minimo' => ['nullable', 'numeric', 'min:0'],
            'costo_unitario' => ['nullable', 'numeric', 'min:0'],
            'proveedor' => ['nullable', 'string', 'max:150'],
            'activo' => ['nullable', 'boolean'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
        ]) + [
            'activo' => $request->boolean('activo'),
            'stock' => $request->input('stock', 0),
            'stock_minimo' => $request->input('stock_minimo', 0),
        ];
    }
}
