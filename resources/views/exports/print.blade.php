<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $titulo }} · {{ $clinica }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        @media print { .no-print{display:none!important} @page{margin:12mm; size:landscape} body{background:#fff} }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">

<div class="no-print sticky top-0 bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between">
    <a href="{{ url()->previous() }}" class="text-sm font-semibold text-slate-500 hover:text-slate-700">&larr; Volver</a>
    <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
        Imprimir / Guardar PDF
    </button>
</div>

<div class="max-w-6xl mx-auto my-6 bg-white rounded-2xl shadow-sm ring-1 ring-slate-200 print:shadow-none print:ring-0 p-8">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4 mb-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800">{{ $clinica }}</h1>
            <p class="text-sm text-slate-500">Reporte: {{ $titulo }}</p>
        </div>
        <div class="text-right text-sm text-slate-500">
            <p>{{ now()->format('d/m/Y H:i') }}</p>
            <p>{{ count($filas) }} registro(s)</p>
        </div>
    </div>

    <table class="w-full text-xs border border-slate-200">
        <thead><tr class="bg-slate-50 text-slate-600 text-left">
            @foreach ($columnas as $col)
                <th class="px-3 py-2 font-semibold border-b border-slate-200">{{ $col }}</th>
            @endforeach
        </tr></thead>
        <tbody>
            @forelse ($filas as $fila)
                <tr class="border-b border-slate-100">
                    @foreach ($fila as $celda)
                        <td class="px-3 py-1.5 text-slate-700">{{ $celda }}</td>
                    @endforeach
                </tr>
            @empty
                <tr><td colspan="{{ max(count($columnas), 1) }}" class="px-3 py-6 text-center text-slate-400">Sin registros.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>window.addEventListener('load', () => { if (new URLSearchParams(location.search).get('auto') === '1') window.print(); });</script>
</body>
</html>
