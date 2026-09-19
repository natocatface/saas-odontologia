<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ strtoupper($doc->tipo) }} {{ $doc->serie }}-{{ $numeroFmt }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        @media print {
            .no-print{display:none!important}
            @page{margin:12mm}
            body{background:#fff}
        }
    </style>
</head>
@php
    $tipoTitulo = [
        'factura' => 'FACTURA ELECTRONICA',
        'boleta' => 'BOLETA DE VENTA ELECTRONICA',
        'nota_credito' => 'NOTA DE CREDITO ELECTRONICA',
        'nota_debito' => 'NOTA DE DEBITO ELECTRONICA',
    ][$doc->tipo] ?? 'COMPROBANTE ELECTRONICO';
    $simbolo = ['PEN' => 'S/', 'USD' => '$'][$doc->moneda] ?? $doc->moneda;
    $razonEmisor = $emisor['razon_social'] ?: ($config['nombre_clinica'] ?? 'CLINICA DENTAL');
    $estadoStyles = [
        'aceptado' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'rechazado' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'error' => 'bg-rose-50 text-rose-700 ring-rose-200',
    ][$doc->estado] ?? 'bg-slate-100 text-slate-600 ring-slate-200';
@endphp
<body class="bg-slate-100 text-slate-800">

<div class="no-print sticky top-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between z-10">
    <a href="{{ route('facturacion.comprobantes.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">&larr; Volver a comprobantes</a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="max-w-3xl mx-auto my-8 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 print:shadow-none print:ring-0 p-10">
    {{-- Encabezado --}}
    <div class="flex items-start justify-between gap-6">
        <div class="flex items-start gap-4">
            <div class="h-14 w-14 shrink-0 rounded-2xl bg-blue-600 text-white flex items-center justify-center">
                <svg class="h-8 w-8" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 leading-tight">{{ strtoupper($razonEmisor) }}</h1>
                @if ($emisor['nombre_comercial'])<p class="text-sm text-slate-500">{{ $emisor['nombre_comercial'] }}</p>@endif
                @if ($emisor['direccion'])<p class="text-sm text-slate-500 mt-1">{{ $emisor['direccion'] }}</p>@endif
                <p class="text-sm text-slate-500">
                    {{ trim(($emisor['distrito'] ?? '').' '.($emisor['provincia'] ?? '').' '.($emisor['departamento'] ?? '')) }}
                </p>
            </div>
        </div>

        {{-- Recuadro fiscal (estilo peruano) --}}
        <div class="text-center border-2 border-slate-300 rounded-xl px-5 py-4 min-w-[220px]">
            <p class="text-sm font-bold text-slate-700">R.U.C. {{ $emisor['ruc'] ?: '—' }}</p>
            <p class="text-sm font-extrabold text-blue-700 mt-1">{{ $tipoTitulo }}</p>
            <p class="text-lg font-extrabold text-slate-800 mt-1">{{ $doc->serie }}-{{ $numeroFmt }}</p>
        </div>
    </div>

    {{-- Estado + fecha --}}
    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100">
        <div class="text-sm text-slate-500">
            <span class="font-semibold text-slate-600">Fecha de emision:</span> {{ optional($doc->created_at)->format('d/m/Y H:i') }}
            @if ($doc->referencia_externa)<span class="ml-3 font-semibold text-slate-600">Ref:</span> {{ $doc->referencia_externa }}@endif
        </div>
        <span class="inline-flex items-center rounded-full ring-1 px-2.5 py-1 text-xs font-semibold {{ $estadoStyles }}">{{ ucfirst($doc->estado) }}</span>
    </div>

    {{-- Receptor --}}
    <div class="mt-4 rounded-xl bg-slate-50 border border-slate-100 p-4 text-sm">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-1">Cliente</p>
        <p class="font-semibold text-slate-800">{{ $receptor['razonSocial'] ?? '—' }}</p>
        <p class="text-slate-500">{{ $receptor['tipoIdentificacion'] ?? 'Doc' }}: {{ $receptor['identificacion'] ?? '—' }}</p>
        @if (!empty($receptor['direccion']))<p class="text-slate-500">{{ $receptor['direccion'] }}</p>@endif
    </div>

    {{-- Detalle --}}
    <table class="w-full text-sm mt-6">
        <thead>
            <tr class="text-left text-xs font-semibold text-slate-400 uppercase tracking-wide border-b border-slate-200">
                <th class="py-2">Cant.</th>
                <th class="py-2">Descripcion</th>
                <th class="py-2 text-right">P. Unit.</th>
                <th class="py-2 text-right">Importe</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
            @forelse ($doc->lineas as $l)
                <tr>
                    <td class="py-2.5 align-top">{{ rtrim(rtrim(number_format((float) $l->cantidad, 2), '0'), '.') }}</td>
                    <td class="py-2.5 align-top text-slate-700">{{ $l->descripcion }}</td>
                    <td class="py-2.5 align-top text-right">{{ $simbolo }} {{ number_format((float) $l->precio, 2) }}</td>
                    <td class="py-2.5 align-top text-right font-medium">{{ $simbolo }} {{ number_format((float) $l->cantidad * (float) $l->precio, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="py-4 text-center text-slate-400">Sin detalle de lineas.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Totales --}}
    <div class="flex justify-end mt-4">
        <div class="w-full max-w-xs text-sm space-y-1">
            <div class="flex justify-between text-slate-500"><span>Op. Gravada</span><span>{{ $simbolo }} {{ number_format((float) $doc->subtotal, 2) }}</span></div>
            <div class="flex justify-between text-slate-500"><span>IGV (18%)</span><span>{{ $simbolo }} {{ number_format((float) $doc->impuestos, 2) }}</span></div>
            <div class="flex justify-between text-base font-extrabold text-slate-800 pt-2 border-t border-slate-200"><span>Total</span><span>{{ $simbolo }} {{ number_format((float) $doc->total, 2) }}</span></div>
        </div>
    </div>

    <p class="text-xs text-slate-500 mt-4"><span class="font-semibold">Son:</span> {{ $enLetras }}</p>

    {{-- QR + pie --}}
    <div class="flex items-end justify-between gap-6 mt-8 pt-6 border-t border-slate-200">
        <div class="text-center">
            <div id="qr" class="inline-block"></div>
        </div>
        <div class="flex-1 text-[11px] text-slate-400 leading-relaxed">
            <p>Representacion impresa del comprobante electronico.</p>
            <p>Consulte su comprobante en el portal de SUNAT (www.sunat.gob.pe).</p>
            <p class="mt-1 break-all">Autorizado mediante emision electronica.</p>
        </div>
    </div>
</div>

<script>
    (function () {
        var data = @json($qr);
        var el = document.getElementById('qr');
        if (window.QRCode && data) {
            new QRCode(el, { text: data, width: 128, height: 128, correctLevel: QRCode.CorrectLevel.M });
        }
    })();
</script>
</body>
</html>
