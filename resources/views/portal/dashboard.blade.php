@extends('portal.layout')

@section('title','Inicio')

@section('content')
@php $moneda = $config['moneda'] ?? '$'; @endphp

<div class="mb-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Hola, {{ $paciente->nombre }} 👋</h1>
    <p class="text-slate-500">Este es el resumen de tu cuenta.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Facturado</p>
        <p class="mt-1 text-2xl font-extrabold text-slate-800">{{ $moneda }}{{ number_format($paciente->total_facturado, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Pagado</p>
        <p class="mt-1 text-2xl font-extrabold text-emerald-600">{{ $moneda }}{{ number_format($paciente->total_pagado, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Saldo pendiente</p>
        <p class="mt-1 text-2xl font-extrabold {{ $paciente->saldo > 0 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $moneda }}{{ number_format($paciente->saldo, 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="font-bold text-slate-800">Proximas citas</h2>
        <a href="{{ route('portal.citas') }}" class="text-sm font-semibold text-blue-600 hover:underline">Ver todas</a>
    </div>
    <div class="divide-y divide-slate-100">
        @forelse ($proximas as $cita)
            @php $cb = ['confirmada'=>'bg-emerald-100 text-emerald-700','pendiente'=>'bg-amber-100 text-amber-700']; @endphp
            <div class="px-6 py-4 flex items-center gap-3">
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0">
                    <span class="text-[10px] uppercase leading-none">{{ $cita->fecha->translatedFormat('M') }}</span>
                    <span class="text-sm font-bold leading-none">{{ $cita->fecha->format('d') }}</span>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium text-slate-700">{{ $cita->motivo ?: 'Consulta' }}</p>
                    <p class="text-xs text-slate-400">{{ $cita->fecha->translatedFormat('l d/m/Y') }} · {{ $cita->hora ? \Illuminate\Support\Str::of($cita->hora)->substr(0,5) : 'Por confirmar' }} · {{ $cita->doctor->name ?? 'Por asignar' }}</p>
                </div>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $cb[$cita->estado] ?? 'bg-slate-100 text-slate-600' }}">{{ $cita->estado_nombre }}</span>
            </div>
        @empty
            <p class="px-6 py-10 text-center text-sm text-slate-400">No tienes citas proximas agendadas.</p>
        @endforelse
    </div>
</div>
@endsection
