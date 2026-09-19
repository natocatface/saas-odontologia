@php
    $pac = auth('paciente')->user();
    $nombreClinica = \App\Models\Configuracion::valor('nombre_clinica', 'OdontoCRM');
    $nav = [
        ['label' => 'Inicio', 'route' => route('portal.dashboard'), 'active' => request()->routeIs('portal.dashboard')],
        ['label' => 'Mis citas', 'route' => route('portal.citas'), 'active' => request()->routeIs('portal.citas')],
        ['label' => 'Presupuestos', 'route' => route('portal.presupuestos'), 'active' => request()->routeIs('portal.presupuestos')],
        ['label' => 'Estado de cuenta', 'route' => route('portal.estado-cuenta'), 'active' => request()->routeIs('portal.estado-cuenta')],
    ];
@endphp
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Portal') · {{ $nombreClinica }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}[x-cloak]{display:none!important}</style>
</head>
<body class="h-full bg-slate-100">
<div class="min-h-full">
    <header class="bg-gradient-to-r from-blue-700 to-indigo-700 text-white" x-data="{ open:false }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="h-16 flex items-center justify-between">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2.5">
                    <div class="h-9 w-9 rounded-xl bg-white/15 flex items-center justify-center">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
                    </div>
                    <div class="leading-tight">
                        <p class="font-extrabold">{{ $nombreClinica }}</p>
                        <p class="text-[11px] text-blue-200">Portal del paciente</p>
                    </div>
                </a>
                <div class="flex items-center gap-3">
                    <span class="hidden sm:block text-sm text-blue-100">{{ $pac->nombre_completo ?? '' }}</span>
                    <div class="h-9 w-9 rounded-full bg-white/15 text-white text-sm font-semibold flex items-center justify-center">{{ $pac->iniciales ?? '' }}</div>
                    <form method="POST" action="{{ route('portal.logout') }}">
                        @csrf
                        <button class="text-sm text-blue-100 hover:text-white">Salir</button>
                    </form>
                </div>
            </div>
            <nav class="flex gap-1 -mb-px overflow-x-auto">
                @foreach ($nav as $item)
                    <a href="{{ $item['route'] }}" class="px-4 py-2.5 text-sm font-semibold border-b-2 whitespace-nowrap transition {{ $item['active'] ? 'border-white text-white' : 'border-transparent text-blue-200 hover:text-white' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
        </div>
    </header>

    @if (session('status'))
        <div class="max-w-5xl mx-auto px-4 sm:px-6 mt-4">
            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</div>
        </div>
    @endif

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-6">
        @yield('content')
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
