@extends('layouts.app')

@section('title','Citas')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Citas</h1>
        <p class="text-slate-500">Agenda y seguimiento de citas de la clinica</p>
    </div>
    <div class="flex items-center gap-2">
        @include('partials.export-menu', ['ruta' => 'export.citas'])
        <a href="{{ route('citas.calendario') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
            Calendario
        </a>
        <a href="{{ route('citas.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Nueva cita
        </a>
    </div>
</div>

<!-- Stats -->
@php
    $chips = [
        ['Citas hoy', $stats['hoy'], 'bg-blue-100 text-blue-600'],
        ['Pendientes', $stats['pendientes'], 'bg-amber-100 text-amber-600'],
        ['Confirmadas', $stats['confirmadas'], 'bg-emerald-100 text-emerald-600'],
        ['Completadas (mes)', $stats['completadas'], 'bg-violet-100 text-violet-600'],
    ];
@endphp
<div class="mt-5 grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ($chips as [$label,$val,$cls])
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <span class="h-7 w-7 rounded-lg {{ $cls }} flex items-center justify-center text-xs font-bold">{{ $val }}</span>
                <p class="text-xs text-slate-500">{{ $label }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
    <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar paciente..."
           class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 lg:col-span-2">
    <input type="date" name="fecha" value="{{ $fecha }}"
           class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    <select name="doctor" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todos los doctores</option>
        @foreach ($doctores as $d)
            <option value="{{ $d->id }}" {{ (string)$doctorId===(string)$d->id?'selected':'' }}>{{ $d->name }}</option>
        @endforeach
    </select>
    <select name="estado" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todos los estados</option>
        @foreach (\App\Models\Cita::ESTADOS as $k=>$v)
            <option value="{{ $k }}" {{ $estado===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <div class="flex gap-2 sm:col-span-2 lg:col-span-5">
        <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
        @if($buscar||$fecha||$estado||$doctorId)
            <a href="{{ route('citas.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Limpiar</a>
        @endif
    </div>
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Fecha / Hora</th>
                    <th class="px-5 py-3 font-semibold">Paciente</th>
                    <th class="px-5 py-3 font-semibold">Doctor</th>
                    <th class="px-5 py-3 font-semibold">Motivo</th>
                    <th class="px-5 py-3 font-semibold">Estado</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($citas as $cita)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3">
                            <p class="font-medium text-slate-700">{{ $cita->fecha->format('d/m/Y') }}</p>
                            <p class="text-xs text-slate-400">{{ \Illuminate\Support\Str::of($cita->hora)->substr(0,5) ?: '—' }}</p>
                        </td>
                        <td class="px-5 py-3">
                            @if($cita->paciente)
                                <a href="{{ route('pacientes.show', $cita->paciente) }}" class="font-medium text-slate-700 hover:text-blue-600">{{ $cita->paciente->nombre_completo }}</a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">
                            {{ $cita->doctor->name ?? '—' }}
                            @if($cita->silla)<span class="ml-1 text-[11px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">Silla {{ $cita->silla }}</span>@endif
                        </td>
                        <td class="px-5 py-3 text-slate-600">{{ $cita->motivo ?: '—' }}</td>
                        <td class="px-5 py-3">
                            @php
                                $badge = match($cita->estado) {
                                    'confirmada' => 'bg-emerald-100 text-emerald-700',
                                    'completada' => 'bg-blue-100 text-blue-700',
                                    'cancelada'  => 'bg-rose-100 text-rose-700',
                                    default      => 'bg-amber-100 text-amber-700',
                                };
                            @endphp
                            <form method="POST" action="{{ route('citas.estado', $cita) }}">
                                @csrf @method('PATCH')
                                <select name="estado" onchange="this.form.submit()"
                                        class="text-[11px] font-medium px-2 py-1 rounded-full border-0 cursor-pointer {{ $badge }} focus:ring-2 focus:ring-blue-300 outline-none">
                                    @foreach (\App\Models\Cita::ESTADOS as $k=>$v)
                                        <option value="{{ $k }}" {{ $cita->estado===$k?'selected':'' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                @php
                                    $tel = preg_replace('/\D/', '', (string) ($cita->paciente->telefono ?? ''));
                                    $msg = rawurlencode('Hola '.($cita->paciente->nombre ?? '').', le recordamos su cita el '.$cita->fecha->format('d/m/Y').' a las '.\Illuminate\Support\Str::of($cita->hora)->substr(0,5).'. Por favor confirme su asistencia.');
                                @endphp
                                @if($tel)
                                    <a href="https://wa.me/{{ $tel }}?text={{ $msg }}" target="_blank" rel="noopener" title="Enviar recordatorio por WhatsApp" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M21 12a8.5 8.5 0 01-12.3 7.6L3 21l1.5-5.5A8.5 8.5 0 1121 12z"/></svg>
                                    </a>
                                @endif
                                @if($cita->paciente?->email)
                                    <form method="POST" action="{{ route('citas.recordatorio', $cita) }}">
                                        @csrf
                                        <button title="{{ $cita->recordatorio_enviado_en ? 'Reenviar recordatorio por email (enviado '.$cita->recordatorio_enviado_en->format('d/m H:i').')' : 'Enviar recordatorio por email' }}"
                                                class="p-2 rounded-lg {{ $cita->recordatorio_enviado_en ? 'text-emerald-500 hover:bg-emerald-50' : 'text-slate-400 hover:text-blue-600 hover:bg-blue-50' }}">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('citas.edit', $cita) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg></a>
                                <form method="POST" action="{{ route('citas.destroy', $cita) }}" onsubmit="return confirm('¿Eliminar esta cita?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No se encontraron citas. <a href="{{ route('citas.create') }}" class="text-blue-600 font-medium">Agendar una nueva</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($citas->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $citas->links() }}</div>
    @endif
</div>
@endsection
