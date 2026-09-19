@extends('layouts.app')

@section('title','Actividad')

@section('content')
@php
    $accionBadge = [
        'creado' => 'bg-emerald-100 text-emerald-700',
        'actualizado' => 'bg-amber-100 text-amber-700',
        'eliminado' => 'bg-rose-100 text-rose-700',
        'acceso' => 'bg-blue-100 text-blue-700',
        'salida' => 'bg-slate-200 text-slate-600',
    ];
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Actividad</h1>
        <p class="text-slate-500">Bitacora de auditoria del sistema</p>
    </div>
    <div class="flex gap-3">
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-2 text-center"><p class="text-lg font-extrabold text-slate-800">{{ number_format($stats['total']) }}</p><p class="text-[11px] text-slate-500">Registros</p></div>
        <div class="bg-white rounded-xl border border-slate-200 px-4 py-2 text-center"><p class="text-lg font-extrabold text-slate-800">{{ number_format($stats['hoy']) }}</p><p class="text-[11px] text-slate-500">Hoy</p></div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar en descripcion o modulo..."
           class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    <select name="accion" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todas las acciones</option>
        @foreach (\App\Models\Actividad::ACCIONES as $k=>$v)
            <option value="{{ $k }}" {{ $accion===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <select name="usuario" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todos los usuarios</option>
        @foreach ($usuarios as $u)
            <option value="{{ $u->id }}" {{ (string)$userId===(string)$u->id?'selected':'' }}>{{ $u->name }}</option>
        @endforeach
    </select>
    <div class="flex gap-2">
        <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
        @if($buscar||$accion||$userId)
            <a href="{{ route('actividad.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">Limpiar</a>
        @endif
    </div>
</form>

<!-- Lista -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Fecha</th>
                    <th class="px-5 py-3 font-semibold">Usuario</th>
                    <th class="px-5 py-3 font-semibold">Accion</th>
                    <th class="px-5 py-3 font-semibold">Modulo</th>
                    <th class="px-5 py-3 font-semibold">Detalle</th>
                    <th class="px-5 py-3 font-semibold">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($actividades as $a)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3 text-slate-600 whitespace-nowrap">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 text-slate-700">{{ $a->user->name ?? 'Sistema' }}</td>
                        <td class="px-5 py-3"><span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $accionBadge[$a->accion] ?? 'bg-slate-100 text-slate-600' }}">{{ $a->accion_nombre }}</span></td>
                        <td class="px-5 py-3 text-slate-600">{{ $a->modulo ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $a->descripcion ?: '—' }}</td>
                        <td class="px-5 py-3 text-slate-400 text-xs">{{ $a->ip ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">Aun no hay actividad registrada. Las acciones de los usuarios apareceran aqui.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($actividades->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $actividades->links() }}</div>
    @endif
</div>
@endsection
