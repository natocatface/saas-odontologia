@extends('layouts.app')

@section('title','Pacientes')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Pacientes</h1>
        <p class="text-slate-500">Gestion de la historia clinica y datos de los pacientes</p>
    </div>
    <div class="flex items-center gap-2">
        @include('partials.export-menu', ['ruta' => 'export.pacientes'])
        <a href="{{ route('pacientes.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Nuevo paciente
        </a>
    </div>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 7a3 3 0 100 6 3 3 0 000-6zM3 20c0-2.5 2-4 5-4M21 20c0-2.5-2-4-5-4"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['total']) }}</p><p class="text-xs text-slate-500">Total pacientes</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['activos']) }}</p><p class="text-xs text-slate-500">Activos</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['nuevos']) }}</p><p class="text-xs text-slate-500">Nuevos este mes</p></div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
        </span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre, documento, telefono o correo..."
               class="w-full rounded-xl border border-slate-300 bg-white pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <select name="estado" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="" {{ $estado===''?'selected':'' }}>Todos</option>
        <option value="activos" {{ $estado==='activos'?'selected':'' }}>Activos</option>
        <option value="inactivos" {{ $estado==='inactivos'?'selected':'' }}>Inactivos</option>
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar || $estado)
        <a href="{{ route('pacientes.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Paciente</th>
                    <th class="px-5 py-3 font-semibold">Documento</th>
                    <th class="px-5 py-3 font-semibold">Contacto</th>
                    <th class="px-5 py-3 font-semibold">Edad</th>
                    <th class="px-5 py-3 font-semibold">Estado</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pacientes as $p)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold flex items-center justify-center shrink-0">{{ $p->iniciales }}</div>
                                <div>
                                    <a href="{{ route('pacientes.show', $p) }}" class="font-medium text-slate-700 hover:text-blue-600">{{ $p->nombre_completo }}</a>
                                    <p class="text-xs text-slate-400">{{ $p->email ?: 'Sin correo' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->documento ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->telefono ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->edad !== null ? $p->edad.' anos' : '—' }}</td>
                        <td class="px-5 py-3">
                            @if($p->activo)
                                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                            @else
                                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">Inactivo</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('pacientes.show', $p) }}" title="Ver" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></a>
                                <a href="{{ route('pacientes.edit', $p) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg></a>
                                <form method="POST" action="{{ route('pacientes.destroy', $p) }}" onsubmit="return confirm('¿Eliminar a {{ $p->nombre_completo }}? Esta accion no se puede deshacer.');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No se encontraron pacientes. <a href="{{ route('pacientes.create') }}" class="text-blue-600 font-medium">Registrar el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pacientes->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $pacientes->links() }}</div>
    @endif
</div>
@endsection
