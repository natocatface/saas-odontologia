<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recibo {{ $pago->numero_recibo }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        @media print {
            .no-print{display:none!important}
            @page{margin:14mm}
            body{background:#fff}
        }
    </style>
</head>
@php
    $moneda = $config['moneda'] ?? '$';
    $monto = number_format((float) $pago->monto, 2);
@endphp
<body class="bg-slate-100 text-slate-800">

<div class="no-print sticky top-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
    <a href="{{ url()->previous() }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">&larr; Volver</a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="max-w-2xl mx-auto my-8 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 print:shadow-none print:ring-0 p-10">
    <!-- Encabezado -->
    <div class="flex items-start justify-between gap-6 border-b border-slate-200 pb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}</h1>
            @if(!empty($config['direccion']))<p class="text-sm text-slate-500 mt-1">{{ $config['direccion'] }}{{ !empty($config['ciudad']) ? ', '.$config['ciudad'] : '' }}</p>@endif
            <p class="text-sm text-slate-500">
                @if(!empty($config['telefono'])){{ $config['telefono'] }}@endif
                @if(!empty($config['email'])) · {{ $config['email'] }}@endif
            </p>
            @if(!empty($config['nit']))<p class="text-sm text-slate-500">NIT/RUC: {{ $config['nit'] }}</p>@endif
        </div>
        <div class="text-right">
            <span class="inline-block text-xs font-bold uppercase tracking-wide bg-blue-50 text-blue-700 px-3 py-1 rounded-full">Recibo de pago</span>
            <p class="mt-3 text-lg font-extrabold text-slate-800">{{ $pago->numero_recibo }}</p>
            <p class="text-sm text-slate-500">{{ $pago->fecha->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Datos -->
    <div class="grid grid-cols-2 gap-6 py-6 text-sm">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Recibido de</p>
            <p class="font-semibold text-slate-800">{{ $pago->paciente->nombre_completo ?? '—' }}</p>
            @if($pago->paciente?->documento)<p class="text-slate-500">Doc: {{ $pago->paciente->documento }}</p>@endif
            @if($pago->paciente?->telefono)<p class="text-slate-500">Tel: {{ $pago->paciente->telefono }}</p>@endif
        </div>
        <div class="text-right">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Forma de pago</p>
            <p class="font-semibold text-slate-800">{{ $pago->metodo_nombre }}</p>
            @if($pago->referencia)<p class="text-slate-500">Ref: {{ $pago->referencia }}</p>@endif
            @if($pago->presupuesto)<p class="text-slate-500">Presupuesto: {{ $pago->presupuesto->codigo }}</p>@endif
        </div>
    </div>

    <!-- Concepto y monto -->
    <table class="w-full text-sm border border-slate-200 rounded-xl overflow-hidden">
        <thead><tr class="bg-slate-50 text-slate-500 text-left">
            <th class="px-4 py-2 font-semibold">Concepto</th>
            <th class="px-4 py-2 font-semibold text-right w-40">Monto</th>
        </tr></thead>
        <tbody>
            <tr class="border-t border-slate-100">
                <td class="px-4 py-3 text-slate-700">
                    {{ $pago->notas ?: 'Pago por servicios odontologicos' }}
                    @if($pago->presupuesto) ({{ $pago->presupuesto->codigo }})@endif
                </td>
                <td class="px-4 py-3 text-right font-semibold text-slate-800">{{ $moneda }}{{ $monto }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="border-t border-slate-200 bg-slate-50">
                <td class="px-4 py-3 text-right font-bold text-slate-700">Total recibido</td>
                <td class="px-4 py-3 text-right text-lg font-extrabold text-emerald-600">{{ $moneda }}{{ $monto }}</td>
            </tr>
        </tfoot>
    </table>

    @if($pago->presupuesto)
        @php $saldo = $pago->presupuesto->saldo; @endphp
        <p class="mt-4 text-sm {{ $saldo > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
            Saldo pendiente del presupuesto {{ $pago->presupuesto->codigo }}: <strong>{{ $moneda }}{{ number_format($saldo, 2) }}</strong>
        </p>
    @endif

    <!-- Firma -->
    <div class="mt-16 grid grid-cols-2 gap-10 text-center text-sm text-slate-500">
        <div class="border-t border-slate-300 pt-2">Firma y sello</div>
        <div class="border-t border-slate-300 pt-2">Recibido conforme</div>
    </div>

    <p class="mt-8 text-center text-xs text-slate-400">Documento generado por {{ $config['nombre_clinica'] ?? 'OdontoCRM' }} · {{ now()->format('d/m/Y H:i') }}</p>
</div>

</body>
</html>
