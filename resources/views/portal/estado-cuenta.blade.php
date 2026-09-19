@extends('portal.layout')

@section('title','Estado de cuenta')

@section('content')
@php $moneda = $config['moneda'] ?? '$'; @endphp

<h1 class="text-2xl font-extrabold text-slate-800 mb-5">Estado de cuenta</h1>

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
    <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Pagos realizados</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-2 font-semibold">Recibo</th>
                <th class="px-5 py-2 font-semibold">Fecha</th>
                <th class="px-5 py-2 font-semibold">Metodo</th>
                <th class="px-5 py-2 font-semibold text-right">Monto</th>
                <th class="px-5 py-2 font-semibold text-right">Comprobante</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($paciente->pagos as $pago)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $pago->numero_recibo }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $pago->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $pago->metodo_nombre }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-emerald-600">{{ $moneda }}{{ number_format($pago->monto, 2) }}</td>
                        <td class="px-5 py-3 text-right"><a href="{{ route('portal.recibo', $pago) }}" target="_blank" class="text-blue-600 hover:underline font-medium">Ver recibo</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Aun no tienes pagos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
