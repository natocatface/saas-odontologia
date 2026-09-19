@extends('layouts.app')

@section('title', $insumo->nombre)

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('insumos.index') }}" class="hover:text-blue-600">Inventario</a><span>/</span>
    <span class="text-slate-600 font-medium">{{ $insumo->nombre }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Ficha + movimiento -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800">{{ $insumo->nombre }}</h1>
                    <p class="text-sm text-slate-500">{{ $insumo->categoria_nombre }}</p>
                </div>
                <a href="{{ route('insumos.edit', $insumo) }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-3 py-1.5 hover:bg-slate-50 transition">Editar</a>
            </div>
            <div class="mt-5 text-center rounded-xl border {{ $insumo->bajo_stock ? 'border-rose-200 bg-rose-50' : 'border-slate-200 bg-slate-50' }} py-5">
                <p class="text-3xl font-extrabold {{ $insumo->bajo_stock ? 'text-rose-600' : 'text-slate-800' }}">{{ rtrim(rtrim(number_format($insumo->stock, 2), '0'), '.') }}</p>
                <p class="text-xs text-slate-500">{{ $insumo->unidad }}(s) en stock @if($insumo->bajo_stock)· <span class="font-semibold text-rose-600">bajo el minimo</span>@endif</p>
            </div>
            <dl class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Stock minimo</dt><dd class="text-slate-700 font-medium">{{ rtrim(rtrim(number_format($insumo->stock_minimo, 2), '0'), '.') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Costo unitario</dt><dd class="text-slate-700 font-medium">{{ $insumo->costo_unitario !== null ? '$'.number_format($insumo->costo_unitario, 2) : '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Valor en stock</dt><dd class="text-slate-700 font-medium">${{ number_format($insumo->valor_stock, 2) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Proveedor</dt><dd class="text-slate-700 font-medium">{{ $insumo->proveedor ?: '—' }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Registrar movimiento</h2>
            <form method="POST" action="{{ route('insumos.movimiento', $insumo) }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Tipo</label>
                        <select name="tipo" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                            @foreach (\App\Models\MovimientoInventario::TIPOS as $k=>$v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Cantidad</label>
                        <input type="number" step="0.01" min="0.01" name="cantidad" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ now()->toDateString() }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1">Motivo (opcional)</label>
                    <input type="text" name="motivo" placeholder="Compra, uso en tratamiento, merma..." class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                </div>
                <button class="w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">Registrar</button>
                <p class="text-xs text-slate-400">Entrada suma, salida resta y ajuste fija el stock al valor indicado.</p>
            </form>
        </div>
    </div>

    <!-- Historial -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Historial de movimientos</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-slate-50 text-slate-500 text-left">
                        <th class="px-5 py-2 font-semibold">Fecha</th>
                        <th class="px-5 py-2 font-semibold">Tipo</th>
                        <th class="px-5 py-2 font-semibold">Motivo</th>
                        <th class="px-5 py-2 font-semibold">Registro</th>
                        <th class="px-5 py-2 font-semibold text-right">Cantidad</th>
                        <th class="px-5 py-2 font-semibold text-right">Stock</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($insumo->movimientos as $mov)
                            @php $tb = ['entrada'=>'bg-emerald-100 text-emerald-700','salida'=>'bg-rose-100 text-rose-700','ajuste'=>'bg-amber-100 text-amber-700']; @endphp
                            <tr>
                                <td class="px-5 py-3 text-slate-600">{{ $mov->fecha->format('d/m/Y') }}</td>
                                <td class="px-5 py-3"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $tb[$mov->tipo] ?? 'bg-slate-100 text-slate-600' }}">{{ $mov->tipo_nombre }}</span></td>
                                <td class="px-5 py-3 text-slate-600">{{ $mov->motivo ?: '—' }}</td>
                                <td class="px-5 py-3 text-slate-400">{{ $mov->user->name ?? '—' }}</td>
                                <td class="px-5 py-3 text-right font-medium {{ $mov->tipo === 'salida' ? 'text-rose-600' : 'text-slate-700' }}">
                                    {{ $mov->tipo === 'salida' ? '-' : ($mov->tipo === 'entrada' ? '+' : '=') }}{{ rtrim(rtrim(number_format($mov->cantidad, 2), '0'), '.') }}
                                </td>
                                <td class="px-5 py-3 text-right text-slate-500">{{ rtrim(rtrim(number_format($mov->stock_resultante, 2), '0'), '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Sin movimientos registrados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
