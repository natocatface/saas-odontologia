@extends('layouts.app')

@section('title','Calendario de Citas')

@section('content')
@php
    $hoy = \Illuminate\Support\Carbon::today();
    $chip = [
        'pendiente'  => 'bg-amber-100 text-amber-700 hover:bg-amber-200',
        'confirmada' => 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200',
        'completada' => 'bg-blue-100 text-blue-700 hover:bg-blue-200',
        'cancelada'  => 'bg-rose-100 text-rose-700 hover:bg-rose-200 line-through',
    ];
    $diasSemana = ['Lun','Mar','Mie','Jue','Vie','Sab','Dom'];
@endphp

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Calendario de Citas</h1>
        <p class="text-slate-500">Vista mensual de la agenda</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('citas.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Lista
        </a>
        <a href="{{ route('citas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Nueva cita
        </a>
    </div>
</div>

<!-- Controles -->
<div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div class="flex items-center gap-2">
        <a href="{{ route('citas.calendario', ['mes' => $mesAnterior, 'doctor' => $doctorId]) }}" class="h-9 w-9 rounded-xl border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h2 class="text-lg font-bold text-slate-800 capitalize min-w-[180px] text-center">{{ $mesActual->translatedFormat('F Y') }}</h2>
        <a href="{{ route('citas.calendario', ['mes' => $mesSiguiente, 'doctor' => $doctorId]) }}" class="h-9 w-9 rounded-xl border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
        <a href="{{ route('citas.calendario', ['doctor' => $doctorId]) }}" class="ml-1 text-sm font-medium text-blue-600 hover:underline">Hoy</a>
    </div>
    <form method="GET" class="flex items-center gap-2">
        <input type="hidden" name="mes" value="{{ $mesActual->format('Y-m') }}">
        <select name="doctor" onchange="this.form.submit()" class="rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <option value="">Todos los doctores</option>
            @foreach ($doctores as $d)
                <option value="{{ $d->id }}" {{ (string)$doctorId===(string)$d->id?'selected':'' }}>{{ $d->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- Calendario -->
<div class="mt-4 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="grid grid-cols-7 bg-slate-50 border-b border-slate-100">
        @foreach ($diasSemana as $d)
            <div class="px-2 py-2.5 text-center text-xs font-semibold text-slate-500">{{ $d }}</div>
        @endforeach
    </div>
    <div class="grid grid-cols-7">
        @foreach ($dias as $dia)
            @php
                $esMes = $dia->month === $mesActual->month;
                $esHoy = $dia->isSameDay($hoy);
                $citasDia = $citas[$dia->format('Y-m-d')] ?? collect();
            @endphp
            <div class="min-h-[116px] border-b border-r border-slate-100 p-1.5 {{ $esMes ? 'bg-white' : 'bg-slate-50/50' }} group/cell relative">
                <div class="flex items-center justify-between px-1">
                    <span class="text-xs font-semibold {{ $esHoy ? 'h-6 w-6 rounded-full bg-blue-600 text-white flex items-center justify-center' : ($esMes ? 'text-slate-600' : 'text-slate-300') }}">{{ $dia->day }}</span>
                    <a href="{{ route('citas.create', ['fecha' => $dia->format('Y-m-d')]) }}" title="Nueva cita" class="opacity-0 group-hover/cell:opacity-100 text-slate-300 hover:text-blue-600 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    </a>
                </div>
                <div class="mt-1 space-y-1">
                    @foreach ($citasDia->take(3) as $cita)
                        <a href="{{ route('citas.edit', $cita) }}"
                           class="block truncate rounded-md px-1.5 py-1 text-[11px] font-medium transition {{ $chip[$cita->estado] ?? 'bg-slate-100 text-slate-600' }}">
                            <span class="font-semibold">{{ \Illuminate\Support\Str::of($cita->hora)->substr(0,5) }}</span>
                            {{ $cita->paciente->nombre ?? 'Paciente' }} {{ \Illuminate\Support\Str::of($cita->paciente->apellido ?? '')->substr(0,1) }}.
                        </a>
                    @endforeach
                    @if($citasDia->count() > 3)
                        <p class="text-[11px] text-slate-400 px-1.5">+{{ $citasDia->count() - 3 }} mas</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Leyenda -->
<div class="mt-4 flex flex-wrap gap-4">
    @foreach (['Pendiente'=>'bg-amber-200','Confirmada'=>'bg-emerald-200','Completada'=>'bg-blue-200','Cancelada'=>'bg-rose-200'] as $label=>$cls)
        <div class="flex items-center gap-1.5 text-xs text-slate-600">
            <span class="h-3 w-3 rounded {{ $cls }}"></span>{{ $label }}
        </div>
    @endforeach
</div>
@endsection
