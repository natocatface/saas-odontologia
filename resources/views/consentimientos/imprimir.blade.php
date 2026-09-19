<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $consentimiento->titulo }} · {{ $consentimiento->paciente->nombre_completo ?? '' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        @media print { .no-print{display:none!important} @page{margin:18mm} body{background:#fff} }
    </style>
</head>
@php $p = $consentimiento->paciente; @endphp
<body class="bg-slate-100 text-slate-800">

<div class="no-print sticky top-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
    <a href="{{ route('pacientes.show', ['paciente' => $consentimiento->paciente_id, 'tab' => 'consentimientos']) }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">&larr; Volver</a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="max-w-3xl mx-auto my-8 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 print:shadow-none print:ring-0 p-10">
    <!-- Encabezado -->
    <div class="flex items-start justify-between gap-6 border-b border-slate-200 pb-5 mb-6">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}</h1>
            @if(!empty($config['direccion']))<p class="text-sm text-slate-500">{{ $config['direccion'] }}{{ !empty($config['ciudad']) ? ', '.$config['ciudad'] : '' }}</p>@endif
            <p class="text-sm text-slate-500">
                @if(!empty($config['telefono'])){{ $config['telefono'] }}@endif
                @if(!empty($config['email'])) · {{ $config['email'] }}@endif
            </p>
        </div>
        <div class="text-right text-sm text-slate-500">
            <p class="font-semibold text-slate-700">Consentimiento N° {{ str_pad((string) $consentimiento->id, 5, '0', STR_PAD_LEFT) }}</p>
            <p>{{ ($consentimiento->fecha_firma ?? $consentimiento->created_at)->format('d/m/Y') }}</p>
        </div>
    </div>

    <h2 class="text-center text-lg font-extrabold text-slate-800 uppercase tracking-wide mb-6">{{ $consentimiento->titulo }}</h2>

    <!-- Datos del paciente -->
    <div class="grid grid-cols-2 gap-4 text-sm mb-6">
        <div><span class="text-slate-400">Paciente:</span> <span class="font-semibold text-slate-800">{{ $p->nombre_completo ?? '—' }}</span></div>
        <div><span class="text-slate-400">Documento:</span> <span class="font-semibold text-slate-800">{{ $p->documento ?: '—' }}</span></div>
    </div>

    <!-- Contenido -->
    <div class="text-sm text-slate-700 leading-relaxed text-justify whitespace-pre-line">{{ $consentimiento->contenido }}</div>

    <!-- Firmas -->
    <div class="mt-20 grid grid-cols-2 gap-12 text-center text-sm text-slate-600">
        <div>
            <div class="border-t border-slate-400 pt-2">
                {{ $consentimiento->firmante ?: ($p->nombre_completo ?? 'Paciente') }}
            </div>
            <p class="text-xs text-slate-400 mt-1">Firma del paciente / representante</p>
        </div>
        <div>
            <div class="border-t border-slate-400 pt-2">{{ $config['nombre_clinica'] ?? 'Profesional tratante' }}</div>
            <p class="text-xs text-slate-400 mt-1">Firma y sello del profesional</p>
        </div>
    </div>

    @if($consentimiento->firmado)
        <p class="mt-8 text-center text-xs text-emerald-600 font-semibold">Firmado el {{ optional($consentimiento->fecha_firma)->format('d/m/Y') }} por {{ $consentimiento->firmante }}</p>
    @else
        <p class="mt-8 text-center text-xs text-slate-400">Pendiente de firma.</p>
    @endif
</div>

</body>
</html>
