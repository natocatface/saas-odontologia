@extends('layouts.app')

@section('title','Tratamientos')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Tratamientos</h1>
        <p class="text-slate-500">Catalogo de procedimientos, precios y duracion</p>
    </div>
    <a href="{{ route('tratamientos.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Nuevo tratamiento
    </a>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6v2H9zM7 4H6a1 1 0 00-1 1v15a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['total']) }}</p><p class="text-xs text-slate-500">Tratamientos</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['activos']) }}</p><p class="text-xs text-slate-500">Activos</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['promedio'], 2) }}</p><p class="text-xs text-slate-500">Precio promedio</p></div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg></span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar tratamiento..."
               class="w-full rounded-xl border border-slate-300 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <select name="categoria" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todas las categorias</option>
        @foreach ($categorias as $cat)
            <option value="{{ $cat }}" {{ $categoria===$cat?'selected':'' }}>{{ $cat }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar||$categoria)
        <a href="{{ route('tratamientos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Tratamiento</th>
                    <th class="px-5 py-3 font-semibold">Categoria</th>
                    <th class="px-5 py-3 font-semibold">Duracion</th>
                    <th class="px-5 py-3 font-semibold">Precio</th>
                    <th class="px-5 py-3 font-semibold">Estado</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($tratamientos as $t)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-700">{{ $t->nombre }}</p>
                            @if($t->descripcion)<p class="text-xs text-slate-400 truncate max-w-xs">{{ $t->descripcion }}</p>@endif
                        </td>
                        <td class="px-5 py-3">
                            @if($t->categoria)<span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">{{ $t->categoria }}</span>@else<span class="text-slate-400">—</span>@endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $t->duracion_min ? $t->duracion_min.' min' : '—' }}</td>
                        <td class="px-5 py-3 font-semibold text-slate-800">{{ $t->precio_formateado }}</td>
                        <td class="px-5 py-3">
                            <form method="POST" action="{{ route('tratamientos.estado', $t) }}">
                                @csrf @method('PATCH')
                                <button class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $t->activo ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                                    {{ $t->activo ? 'Activo' : 'Inactivo' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('tratamientos.edit', $t) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg></a>
                                <form method="POST" action="{{ route('tratamientos.destroy', $t) }}" onsubmit="return confirm('¿Eliminar el tratamiento {{ $t->nombre }}?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No hay tratamientos. <a href="{{ route('tratamientos.create') }}" class="text-blue-600 font-medium">Crear el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tratamientos->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $tratamientos->links() }}</div>
    @endif
</div>
@endsection
