@extends('layouts.app')

@section('title','Inventario')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Inventario de insumos</h1>
        <p class="text-slate-500">Control de stock y existencias</p>
    </div>
    <div class="flex items-center gap-2">
        @include('partials.export-menu', ['ruta' => 'export.insumos'])
        <a href="{{ route('insumos.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Nuevo insumo
        </a>
    </div>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16zM3.3 7L12 12l8.7-5M12 22V12"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['total']) }}</p><p class="text-xs text-slate-500">Insumos</p></div>
    </div>
    <a href="{{ route('insumos.index', ['bajos' => 1]) }}" class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3 hover:border-rose-300 transition">
        <div class="h-10 w-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg></div>
        <div><p class="text-xl font-extrabold {{ $stats['bajos'] > 0 ? 'text-rose-600' : 'text-slate-800' }}">{{ number_format($stats['bajos']) }}</p><p class="text-xs text-slate-500">Bajo stock</p></div>
    </a>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['valor'], 2) }}</p><p class="text-xs text-slate-500">Valor del inventario</p></div>
    </div>
</div>

@if($stats['bajos'] > 0 && !$soloBajos)
    <div class="mt-5 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-3 flex items-center justify-between gap-3">
        <p class="text-sm text-rose-700"><span class="font-bold">{{ $stats['bajos'] }}</span> insumo(s) con stock por debajo del minimo.</p>
        <a href="{{ route('insumos.index', ['bajos' => 1]) }}" class="text-sm font-semibold text-rose-700 hover:underline">Ver</a>
    </div>
@endif

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg></span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar insumo o proveedor..."
               class="w-full rounded-xl border border-slate-300 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
    @if($soloBajos)<input type="hidden" name="bajos" value="1">@endif
    <select name="categoria" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todas las categorias</option>
        @foreach (\App\Models\Insumo::CATEGORIAS as $k=>$v)
            <option value="{{ $k }}" {{ $categoria===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar||$categoria||$soloBajos)
        <a href="{{ route('insumos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Insumo</th>
                    <th class="px-5 py-3 font-semibold">Categoria</th>
                    <th class="px-5 py-3 font-semibold text-right">Stock</th>
                    <th class="px-5 py-3 font-semibold text-right">Minimo</th>
                    <th class="px-5 py-3 font-semibold text-right">Costo unit.</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($insumos as $insumo)
                    <tr class="hover:bg-slate-50/70 {{ $insumo->bajo_stock ? 'bg-rose-50/40' : '' }}">
                        <td class="px-5 py-3">
                            <a href="{{ route('insumos.show', $insumo) }}" class="font-medium text-slate-700 hover:text-blue-600">{{ $insumo->nombre }}</a>
                            @if($insumo->proveedor)<p class="text-xs text-slate-400">{{ $insumo->proveedor }}</p>@endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $insumo->categoria_nombre }}</td>
                        <td class="px-5 py-3 text-right">
                            <span class="font-semibold {{ $insumo->bajo_stock ? 'text-rose-600' : 'text-slate-700' }}">{{ rtrim(rtrim(number_format($insumo->stock, 2), '0'), '.') }}</span>
                            <span class="text-xs text-slate-400">{{ $insumo->unidad }}</span>
                            @if($insumo->bajo_stock)<span class="ml-1 text-[10px] font-semibold px-1.5 py-0.5 rounded-full bg-rose-100 text-rose-600">Bajo</span>@endif
                        </td>
                        <td class="px-5 py-3 text-right text-slate-500">{{ rtrim(rtrim(number_format($insumo->stock_minimo, 2), '0'), '.') }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">{{ $insumo->costo_unitario !== null ? '$'.number_format($insumo->costo_unitario, 2) : '—' }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('insumos.show', $insumo) }}" title="Ver / movimientos" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></a>
                                <a href="{{ route('insumos.edit', $insumo) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg></a>
                                <form method="POST" action="{{ route('insumos.destroy', $insumo) }}" onsubmit="return confirm('¿Eliminar este insumo y sus movimientos?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No hay insumos. <a href="{{ route('insumos.create') }}" class="text-blue-600 font-medium">Crear el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($insumos->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $insumos->links() }}</div>
    @endif
</div>
@endsection
