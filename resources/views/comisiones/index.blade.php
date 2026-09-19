@extends('layouts.app')

@section('title','Comisiones por doctor')

@section('content')
<style>
    @media print {
        aside, header, .no-print { display:none !important; }
        main { padding:0 !important; }
        body { background:#fff !important; }
        .lg\:pl-64, .lg\:pl-20 { padding-left:0 !important; }
    }
</style>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Comisiones por doctor</h1>
        <p class="text-slate-500">Produccion cobrada y comision de {{ $mes->translatedFormat('F \d\e Y') }}</p>
    </div>
    <div class="no-print flex items-center gap-2">
        <form method="GET" class="flex items-center gap-2">
            <input type="month" name="mes" value="{{ $mesParam }}" onchange="this.form.submit()"
                   class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </form>
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
            Imprimir / PDF
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Produccion total cobrada</p>
        <p class="mt-1 text-2xl font-extrabold text-slate-800">${{ number_format($totalProduccion, 2) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Comisiones a pagar</p>
        <p class="mt-1 text-2xl font-extrabold text-blue-600">${{ number_format($totalComision, 2) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-3 font-semibold">Doctor</th>
                <th class="px-5 py-3 font-semibold">Especialidad</th>
                <th class="px-5 py-3 font-semibold text-right">Produccion cobrada</th>
                <th class="px-5 py-3 font-semibold text-right">Comision %</th>
                <th class="px-5 py-3 font-semibold text-right">Comision</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($filas as $fila)
                    <tr>
                        <td class="px-5 py-3 font-medium text-slate-700">{{ $fila['doctor']->name }}</td>
                        <td class="px-5 py-3 text-slate-500">{{ $fila['doctor']->especialidad ?: '—' }}</td>
                        <td class="px-5 py-3 text-right text-slate-700">${{ number_format($fila['produccion'], 2) }}</td>
                        <td class="px-5 py-3 text-right text-slate-600">{{ number_format($fila['porcentaje'], 2) }}%</td>
                        <td class="px-5 py-3 text-right font-semibold text-blue-600">${{ number_format($fila['comision'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No hay doctores registrados. <a href="{{ route('usuarios.create') }}" class="text-blue-600 font-medium no-print">Crear uno</a>.</td></tr>
                @endforelse
            </tbody>
            @if($filas->isNotEmpty())
            <tfoot><tr class="bg-slate-50 border-t border-slate-200">
                <td class="px-5 py-3 font-bold text-slate-700" colspan="2">Total</td>
                <td class="px-5 py-3 text-right font-extrabold text-slate-800">${{ number_format($totalProduccion, 2) }}</td>
                <td></td>
                <td class="px-5 py-3 text-right font-extrabold text-blue-600">${{ number_format($totalComision, 2) }}</td>
            </tr></tfoot>
            @endif
        </table>
    </div>
</div>

<p class="mt-4 text-xs text-slate-400">La produccion considera los pagos cobrados en el mes asociados a presupuestos de cada doctor. El % de comision se configura en cada usuario doctor.</p>
@endsection
