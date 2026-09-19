@extends('layouts.app')

@section('title','Caja diaria')

@section('content')
@php $moneda = $config['moneda'] ?? '$'; @endphp

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
        <h1 class="text-2xl font-extrabold text-slate-800">Caja diaria</h1>
        <p class="text-slate-500">Arqueo del {{ $fecha->translatedFormat('l d \d\e F \d\e Y') }}</p>
    </div>
    <div class="no-print flex items-center gap-2">
        <form method="GET" class="flex items-center gap-2">
            <input type="date" name="fecha" value="{{ $fecha->toDateString() }}" onchange="this.form.submit()"
                   class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </form>
        <a href="{{ route('gastos.create') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">+ Gasto</a>
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
            Imprimir / PDF
        </button>
    </div>
</div>

<!-- Resumen -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Ingresos</p>
        <p class="mt-1 text-2xl font-extrabold text-emerald-600">{{ $moneda }}{{ number_format($totalIngresos, 2) }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $pagos->count() }} pago(s)</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Egresos</p>
        <p class="mt-1 text-2xl font-extrabold text-rose-600">{{ $moneda }}{{ number_format($totalEgresos, 2) }}</p>
        <p class="text-xs text-slate-400 mt-1">{{ $gastos->count() }} gasto(s)</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Neto del dia</p>
        <p class="mt-1 text-2xl font-extrabold {{ $neto >= 0 ? 'text-slate-800' : 'text-rose-600' }}">{{ $moneda }}{{ number_format($neto, 2) }}</p>
        <p class="text-xs text-slate-400 mt-1">Ingresos - Egresos</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Ingresos por metodo -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Ingresos por metodo</h2></div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-slate-100">
                @forelse ($ingresosPorMetodo as $row)
                    <tr><td class="px-6 py-3 text-slate-600">{{ $row['label'] }}</td><td class="px-6 py-3 text-right font-semibold text-emerald-600">{{ $moneda }}{{ number_format($row['total'], 2) }}</td></tr>
                @empty
                    <tr><td class="px-6 py-6 text-center text-slate-400" colspan="2">Sin ingresos este dia.</td></tr>
                @endforelse
            </tbody>
            @if($ingresosPorMetodo->isNotEmpty())
            <tfoot><tr class="bg-slate-50 border-t border-slate-200"><td class="px-6 py-3 font-bold text-slate-700">Total</td><td class="px-6 py-3 text-right font-extrabold text-emerald-600">{{ $moneda }}{{ number_format($totalIngresos, 2) }}</td></tr></tfoot>
            @endif
        </table>
    </div>

    <!-- Egresos por categoria -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Egresos por categoria</h2></div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-slate-100">
                @forelse ($egresosPorCategoria as $row)
                    <tr><td class="px-6 py-3 text-slate-600">{{ $row['label'] }}</td><td class="px-6 py-3 text-right font-semibold text-rose-600">{{ $moneda }}{{ number_format($row['total'], 2) }}</td></tr>
                @empty
                    <tr><td class="px-6 py-6 text-center text-slate-400" colspan="2">Sin egresos este dia.</td></tr>
                @endforelse
            </tbody>
            @if($egresosPorCategoria->isNotEmpty())
            <tfoot><tr class="bg-slate-50 border-t border-slate-200"><td class="px-6 py-3 font-bold text-slate-700">Total</td><td class="px-6 py-3 text-right font-extrabold text-rose-600">{{ $moneda }}{{ number_format($totalEgresos, 2) }}</td></tr></tfoot>
            @endif
        </table>
    </div>
</div>

<!-- Detalle de movimientos -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Detalle de movimientos</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-2 font-semibold">Tipo</th>
                <th class="px-5 py-2 font-semibold">Concepto</th>
                <th class="px-5 py-2 font-semibold">Metodo</th>
                <th class="px-5 py-2 font-semibold text-right">Monto</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($pagos as $pago)
                    <tr>
                        <td class="px-5 py-2"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Ingreso</span></td>
                        <td class="px-5 py-2 text-slate-700">{{ $pago->paciente->nombre_completo ?? 'Pago' }} · {{ $pago->numero_recibo }}</td>
                        <td class="px-5 py-2 text-slate-600">{{ $pago->metodo_nombre }}</td>
                        <td class="px-5 py-2 text-right font-semibold text-emerald-600">{{ $moneda }}{{ number_format($pago->monto, 2) }}</td>
                    </tr>
                @endforeach
                @foreach ($gastos as $gasto)
                    <tr>
                        <td class="px-5 py-2"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700">Egreso</span></td>
                        <td class="px-5 py-2 text-slate-700">{{ $gasto->descripcion }} · {{ $gasto->categoria_nombre }}</td>
                        <td class="px-5 py-2 text-slate-600">{{ $gasto->metodo_nombre }}</td>
                        <td class="px-5 py-2 text-right font-semibold text-rose-600">- {{ $moneda }}{{ number_format($gasto->monto, 2) }}</td>
                    </tr>
                @endforeach
                @if($pagos->isEmpty() && $gastos->isEmpty())
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">Sin movimientos en esta fecha.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<p class="hidden print:block mt-8 text-center text-xs text-slate-400">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }} · Arqueo de caja del {{ $fecha->format('d/m/Y') }} · Generado el {{ now()->format('d/m/Y H:i') }}</p>
@endsection
