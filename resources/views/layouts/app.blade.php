@php
    $current = request()->route('modulo');
    $isDash = request()->routeIs('dashboard');

    $icons = [
        'home'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 11.5 12 4l9 7.5M5 10v9a1 1 0 001 1h4v-5h4v5h4a1 1 0 001-1v-9"/>',
        'users'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 14a4 4 0 10-8 0M12 7a3 3 0 100 6 3 3 0 000-6zM3 20c0-2.5 2-4 5-4M21 20c0-2.5-2-4-5-4"/>',
        'calendar'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/>',
        'clipboard' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 4h6v2H9zM7 4H6a1 1 0 00-1 1v15a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1"/>',
        'document'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5M9 13h6M9 17h6"/>',
        'cash'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3zM12 9a3 3 0 100 6 3 3 0 000-6zM6 9h.01M18 15h.01"/>',
        'chart'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M5 20V10M12 20V4M19 20v-6M3 20h18"/>',
        'cog'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9a3 3 0 100 6 3 3 0 000-6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 12a7 7 0 00-.1-1.2l2-1.6-2-3.4-2.4 1a7 7 0 00-2-1.2l-.4-2.6h-4l-.4 2.6a7 7 0 00-2 1.2l-2.4-1-2 3.4 2 1.6A7 7 0 005 12c0 .4 0 .8.1 1.2l-2 1.6 2 3.4 2.4-1c.6.5 1.3.9 2 1.2l.4 2.6h4l.4-2.6c.7-.3 1.4-.7 2-1.2l2.4 1 2-3.4-2-1.6c.1-.4.1-.8.1-1.2z"/>',
        'activity'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l2 6 4-14 2 8h6"/>',
        'shield'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3l8 3v6c0 5-3.4 8-8 9-4.6-1-8-4-8-9V6z"/>',
        'sliders'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h10M18 6h2M4 12h2M10 12h10M4 18h7M15 18h5M14 4v4M6 10v4M11 16v4"/>',
        'lock'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 11h14v9a1 1 0 01-1 1H6a1 1 0 01-1-1z"/>',
        'logout'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9M12 19H6a1 1 0 01-1-1V6a1 1 0 011-1h6"/>',
        'bell'      => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-2A2 2 0 0118 13.6V11a6 6 0 00-12 0v2.6a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0m6 0H9"/>',
        'search'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/>',
        'collapse'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7M19 19l-7-7 7-7"/>',
        'wallet'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 7a2 2 0 012-2h12a2 2 0 012 2v2H5a2 2 0 00-2 2zM3 11h16a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2zM16 14h.01"/>',
        'expense'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 100-18 9 9 0 000 18zM8 12h8"/>',
        'percent'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M19 5L5 19M8.5 7.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM18.5 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>',
        'box'       => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16zM3.3 7L12 12l8.7-5M12 22V12"/>',
        'receipt'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a1 1 0 011 1v18l-3-2-2 2-2-2-2 2-2-2-3 2V3a1 1 0 011-1zM9 8h6M9 12h6"/>',
    ];

    $nav = [
        ['label' => 'Dashboard',     'icon' => 'home',      'route' => route('dashboard'), 'active' => $isDash],
        ['label' => 'Pacientes',     'icon' => 'users',     'route' => route('pacientes.index'),        'active' => request()->routeIs('pacientes.*')],
        ['label' => 'Citas',         'icon' => 'calendar',  'route' => route('citas.index'),            'active' => request()->routeIs('citas.*')],
        ['label' => 'Tratamientos',  'icon' => 'clipboard', 'route' => route('tratamientos.index'),     'active' => request()->routeIs('tratamientos.*')],
        ['label' => 'Presupuestos',  'icon' => 'document',  'route' => route('presupuestos.index'),     'active' => request()->routeIs('presupuestos.*')],
        ['label' => 'Pagos',         'icon' => 'cash',      'route' => route('pagos.index'),            'active' => request()->routeIs('pagos.*')],
        ['label' => 'Caja',          'icon' => 'wallet',    'route' => route('caja.index'),             'active' => request()->routeIs('caja.*')],
        ['label' => 'Gastos',        'icon' => 'expense',   'route' => route('gastos.index'),           'active' => request()->routeIs('gastos.*')],
        ['label' => 'Inventario',    'icon' => 'box',       'route' => route('insumos.index'),          'active' => request()->routeIs('insumos.*')],
        ['label' => 'Comisiones',    'icon' => 'percent',   'route' => route('comisiones.index'),       'active' => request()->routeIs('comisiones.*'), 'admin' => true],
        ['label' => 'Reportes',      'icon' => 'chart',     'route' => route('reportes.index'),         'active' => request()->routeIs('reportes.*')],
        ['label' => 'Usuarios',      'icon' => 'cog',       'route' => route('usuarios.index'),         'active' => request()->routeIs('usuarios.*'), 'admin' => true],
        ['label' => 'Actividad',     'icon' => 'activity',  'route' => route('actividad.index'),        'active' => request()->routeIs('actividad.*'), 'admin' => true],
        ['label' => 'Mantenimiento', 'icon' => 'shield',    'route' => route('mantenimiento.index'),    'active' => request()->routeIs('mantenimiento.*'), 'admin' => true],
        ['label' => 'Facturacion Electronica', 'icon' => 'receipt', 'route' => route('facturacion.comprobantes.index'), 'active' => request()->routeIs('facturacion.*'), 'admin' => true],
        ['label' => 'Configuracion', 'icon' => 'sliders',   'route' => route('configuracion.edit'),     'active' => request()->routeIs('configuracion.*'), 'admin' => true],
    ];
    $user = auth()->user();
    $nombreClinica = \App\Models\Configuracion::valor('nombre_clinica', 'OdontoCRM');

    // Notificaciones: citas activas de hoy y manana (los doctores solo ven las suyas).
    $notiCitas = \App\Models\Cita::query()
        ->with('paciente')
        ->whereIn('estado', ['pendiente', 'confirmada'])
        ->whereBetween('fecha', [\Illuminate\Support\Carbon::today(), \Illuminate\Support\Carbon::today()->addDay()])
        ->when($user->esDoctor(), fn ($q) => $q->where('doctor_id', $user->id))
        ->orderBy('fecha')
        ->orderBy('hora')
        ->limit(8)
        ->get();
    $notiCount = $notiCitas->count();
@endphp
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Dashboard') · OdontoCRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        ::-webkit-scrollbar{width:8px;height:8px}
        ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:8px}
    </style>
</head>
<body class="h-full bg-slate-100"
      x-data="{ collapsed:false, mobile:false }">
<div class="min-h-full">
    <!-- Overlay movil -->
    <div x-show="mobile" @click="mobile=false" x-cloak class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"></div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-40 flex flex-col bg-gradient-to-b from-blue-700 via-blue-600 to-blue-800 text-blue-50 transition-all duration-200"
           :class="[ collapsed ? 'w-20' : 'w-64', mobile ? 'translate-x-0' : '-translate-x-full lg:translate-x-0' ]">
        <!-- Marca -->
        <div class="h-16 flex items-center gap-3 px-4 border-b border-white/10">
            <div class="h-10 w-10 shrink-0 rounded-xl bg-white/15 flex items-center justify-center">
                <svg class="h-6 w-6 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <div x-show="!collapsed" x-cloak class="leading-tight">
                <p class="font-extrabold text-white truncate max-w-[150px]">{{ $nombreClinica }}</p>
                <p class="text-[11px] text-blue-200">Sistema dental</p>
            </div>
        </div>

        <!-- Navegacion -->
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            @foreach ($nav as $item)
                @continue(!empty($item['admin']) && ! $user->esAdmin())
                <a href="{{ $item['route'] }}"
                   class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition
                          {{ $item['active'] ? 'bg-white/95 text-blue-700 shadow' : 'text-blue-50 hover:bg-white/10' }}">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons[$item['icon']] !!}</svg>
                    <span x-show="!collapsed" x-cloak>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <!-- Pie -->
        <div class="border-t border-white/10 px-3 py-4 space-y-1">
            <a href="{{ route('password.edit') }}"
               class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-blue-50 hover:bg-white/10 transition">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['lock'] !!}</svg>
                <span x-show="!collapsed" x-cloak>Cambiar clave</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-200 hover:bg-rose-500/20 transition">
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['logout'] !!}</svg>
                    <span x-show="!collapsed" x-cloak>Cerrar sesion</span>
                </button>
            </form>
            <button @click="collapsed=!collapsed"
                    class="hidden lg:flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-blue-200 hover:bg-white/10 transition">
                <svg class="h-5 w-5 shrink-0 transition-transform" :class="collapsed && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['collapse'] !!}</svg>
                <span x-show="!collapsed" x-cloak>Colapsar</span>
            </button>
        </div>
    </aside>

    <!-- Contenido -->
    <div class="transition-all duration-200" :class="collapsed ? 'lg:pl-20' : 'lg:pl-64'">
        <!-- Topbar -->
        <header class="sticky top-0 z-20 h-16 bg-white border-b border-slate-200 flex items-center gap-4 px-4 sm:px-6">
            <button @click="mobile=true" class="lg:hidden text-slate-500 hover:text-slate-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="hidden sm:block">
                <p class="text-sm font-semibold text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-slate-400">{{ $user->rol_nombre }}</p>
            </div>
            <form action="{{ route('pacientes.index') }}" method="GET" class="flex-1 max-w-md mx-auto hidden md:block">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['search'] !!}</svg>
                    </span>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar paciente..."
                           class="w-full rounded-full bg-slate-100 border border-transparent focus:border-blue-300 focus:bg-white pl-10 pr-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-100 transition">
                </div>
            </form>
            <div class="ml-auto flex items-center gap-3">
                <div class="relative" x-data="{ open:false }">
                    <button @click="open=!open" class="relative text-slate-400 hover:text-slate-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['bell'] !!}</svg>
                        @if ($notiCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center ring-2 ring-white">{{ $notiCount }}</span>
                        @endif
                    </button>
                    <div x-show="open" x-cloak @click.outside="open=false" x-transition
                         class="absolute right-0 mt-2 w-80 max-h-96 overflow-y-auto rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 z-50">
                        <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                            <p class="text-sm font-bold text-slate-800">Notificaciones</p>
                            <span class="text-xs text-slate-400">Citas de hoy y manana</span>
                        </div>
                        @forelse ($notiCitas as $noti)
                            <a href="{{ route('citas.edit', $noti) }}" class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition border-b border-slate-50 last:border-0">
                                <span class="mt-0.5 h-8 w-8 shrink-0 rounded-lg flex items-center justify-center {{ $noti->estado === 'confirmada' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">{!! $icons['calendar'] !!}</svg>
                                </span>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-700 truncate">{{ $noti->paciente->nombre ?? 'Paciente' }} {{ $noti->paciente->apellido ?? '' }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ $noti->motivo ?: 'Cita' }}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        {{ \Illuminate\Support\Carbon::parse($noti->fecha)->isToday() ? 'Hoy' : 'Manana' }}
                                        {{ $noti->hora ? '· '.\Illuminate\Support\Str::of($noti->hora)->substr(0,5) : '' }}
                                        · {{ $noti->estado_nombre }}
                                    </p>
                                </div>
                            </a>
                        @empty
                            <div class="px-4 py-8 text-center text-sm text-slate-400">Sin citas proximas.</div>
                        @endforelse
                        <a href="{{ route('citas.calendario') }}" class="block px-4 py-3 text-center text-sm font-semibold text-blue-600 hover:bg-blue-50 transition border-t border-slate-100">Ver calendario</a>
                    </div>
                </div>
                <div class="h-9 w-9 rounded-full bg-blue-600 text-white text-sm font-semibold flex items-center justify-center">
                    {{ $user->iniciales }}
                </div>
            </div>
        </header>

        @if (session('status'))
            <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mx-4 sm:mx-6 mt-4 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <main class="p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>
<style>[x-cloak]{display:none!important}</style>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
