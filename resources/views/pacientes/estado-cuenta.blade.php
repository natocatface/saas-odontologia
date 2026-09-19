@extends('layouts.app')

@section('title', 'Estado de cuenta · '.$paciente->nombre_completo)

@section('content')
@php
    $moneda = $config['moneda'] ?? '$';
    $cuotasVencidas = $cuotasPendientes->filter->vencida;
@endphp

<style>
    @media print {
        aside, header, .no-print { display:none !important; }
        main { padding:0 !important; }
        body { background:#fff !important; }
        .print-pl { padding-left:0 !important; }
        .lg\:pl-64, .lg\:pl-20 { padding-left:0 !important; }
    }
</style>

<div class="no-print flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('pacientes.index') }}" class="hover:text-blue-600">Pacientes</a><span>/</span>
    <a href="{{ route('pacientes.show', $paciente) }}" class="hover:text-blue-600">{{ $paciente->nombre_completo }}</a><span>/</span>
    <span class="text-slate-600 font-medium">Estado de cuenta</span>
</div>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Estado de cuenta</h1>
        <p class="text-slate-500">{{ $paciente->nombre_completo }}@if($paciente->documento) · Doc: {{ $paciente->documento }}@endif</p>
    </div>
    <div class="no-print flex items-center gap-2">
        <a href="{{ route('pacientes.show', $paciente) }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">Volver</a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
            Imprimir / PDF
        </button>
    </div>
</div>

<!-- Resumen -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Facturado (aprobado)</p>
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

@if($cuotasVencidas->isNotEmpty())
    <div class="no-print mb-6 rounded-2xl bg-rose-50 border border-rose-200 px-5 py-4 flex items-start gap-3">
        <svg class="h-5 w-5 text-rose-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/></svg>
        <div>
            <p class="text-sm font-bold text-rose-700">{{ $cuotasVencidas->count() }} cuota(s) vencida(s) · recordatorio de cobro</p>
            <p class="text-sm text-rose-600">Suma vencida: {{ $moneda }}{{ number_format($cuotasVencidas->sum('monto'), 2) }}</p>
        </div>
    </div>
@endif

<!-- Presupuestos -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Presupuestos</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-2 font-semibold">Codigo</th>
                <th class="px-5 py-2 font-semibold">Fecha</th>
                <th class="px-5 py-2 font-semibold">Estado</th>
                <th class="px-5 py-2 font-semibold text-right">Total</th>
                <th class="px-5 py-2 font-semibold text-right">Pagado</th>
                <th class="px-5 py-2 font-semibold text-right">Saldo</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($paciente->presupuestos as $p)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $p->codigo }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $p->estado_nombre }}</td>
                        <td class="px-5 py-3 text-right text-slate-700">{{ $moneda }}{{ number_format($p->total, 2) }}</td>
                        <td class="px-5 py-3 text-right text-emerald-600">{{ $moneda }}{{ number_format($p->pagado, 2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold {{ $p->saldo > 0 ? 'text-rose-600' : 'text-slate-400' }}">{{ $moneda }}{{ number_format($p->saldo, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Sin presupuestos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Cuotas pendientes -->
@if($cuotasPendientes->isNotEmpty())
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">
    <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Cuotas pendientes</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-2 font-semibold">Presupuesto</th>
                <th class="px-5 py-2 font-semibold">Cuota</th>
                <th class="px-5 py-2 font-semibold">Vence</th>
                <th class="px-5 py-2 font-semibold">Estado</th>
                <th class="px-5 py-2 font-semibold text-right">Monto</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($cuotasPendientes as $c)
                    <tr class="{{ $c->vencida ? 'bg-rose-50/50' : '' }}">
                        <td class="px-5 py-3 text-slate-600">{{ $c->presupuesto->codigo ?? '—' }}</td>
                        <td class="px-5 py-3 text-slate-700">#{{ $c->numero }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $c->vence_el->format('d/m/Y') }}</td>
                        <td class="px-5 py-3">
                            @if($c->vencida)
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">Vencida</span>
                            @else
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">Pendiente</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-700">{{ $moneda }}{{ number_format($c->monto, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Pagos -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Pagos recibidos</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-2 font-semibold">Recibo</th>
                <th class="px-5 py-2 font-semibold">Fecha</th>
                <th class="px-5 py-2 font-semibold">Metodo</th>
                <th class="px-5 py-2 font-semibold">Presupuesto</th>
                <th class="px-5 py-2 font-semibold text-right">Monto</th>
                <th class="px-5 py-2 font-semibold text-right no-print">Recibo</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($paciente->pagos as $pago)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $pago->numero_recibo }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $pago->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $pago->metodo_nombre }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $pago->presupuesto->codigo ?? '—' }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-emerald-600">{{ $moneda }}{{ number_format($pago->monto, 2) }}</td>
                        <td class="px-5 py-3 text-right no-print">
                            <a href="{{ route('pagos.recibo', $pago) }}" class="text-blue-600 hover:underline font-medium">Ver</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-8 text-center text-slate-400">Sin pagos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<p class="hidden print:block mt-8 text-center text-xs text-slate-400">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }} · Estado de cuenta generado el {{ now()->format('d/m/Y H:i') }}</p>
@endsection
