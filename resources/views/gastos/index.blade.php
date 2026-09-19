@extends('layouts.app')

@section('title','Gastos')

@section('content')
@php
    $catBadge = ['insumos'=>'bg-blue-100 text-blue-700','laboratorio'=>'bg-violet-100 text-violet-700','sueldos'=>'bg-emerald-100 text-emerald-700','alquiler'=>'bg-amber-100 text-amber-700','servicios'=>'bg-cyan-100 text-cyan-700','equipos'=>'bg-indigo-100 text-indigo-700','marketing'=>'bg-pink-100 text-pink-700','impuestos'=>'bg-rose-100 text-rose-700','otros'=>'bg-slate-100 text-slate-600'];
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Gastos</h1>
        <p class="text-slate-500">Egresos y salidas de dinero de la clinica</p>
    </div>
    <div class="flex items-center gap-2">
        @include('partials.export-menu', ['ruta' => 'export.gastos'])
        <a href="{{ route('gastos.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-rose-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Registrar gasto
        </a>
    </div>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['hoy'], 2) }}</p><p class="text-xs text-slate-500">Gastos hoy</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['mes'], 2) }}</p><p class="text-xs text-slate-500">Gastos del mes</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['registros']) }}</p><p class="text-xs text-slate-500">Gastos registrados</p></div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg></span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar descripcion..."
               class="w-full rounded-xl border border-slate-300 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <select name="categoria" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todas las categorias</option>
        @foreach (\App\Models\Gasto::CATEGORIAS as $k=>$v)
            <option value="{{ $k }}" {{ $categoria===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar||$categoria)
        <a href="{{ route('gastos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Fecha</th>
                    <th class="px-5 py-3 font-semibold">Descripcion</th>
                    <th class="px-5 py-3 font-semibold">Categoria</th>
                    <th class="px-5 py-3 font-semibold">Metodo</th>
                    <th class="px-5 py-3 font-semibold">Registro</th>
                    <th class="px-5 py-3 font-semibold">Monto</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($gastos as $gasto)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3 text-slate-600">{{ $gasto->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-700">{{ $gasto->descripcion }}</td>
                        <td class="px-5 py-3"><span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $catBadge[$gasto->categoria] ?? 'bg-slate-100 text-slate-600' }}">{{ $gasto->categoria_nombre }}</span></td>
                        <td class="px-5 py-3 text-slate-600">{{ $gasto->metodo_nombre }}</td>
                        <td class="px-5 py-3 text-slate-400">{{ $gasto->user->name ?? '—' }}</td>
                        <td class="px-5 py-3 font-semibold text-rose-600">${{ number_format($gasto->monto, 2) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end">
                                <form method="POST" action="{{ route('gastos.destroy', $gasto) }}" onsubmit="return confirm('¿Eliminar este gasto?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">No hay gastos registrados. <a href="{{ route('gastos.create') }}" class="text-rose-600 font-medium">Registrar el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($gastos->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $gastos->links() }}</div>
    @endif
</div>
@endsection
