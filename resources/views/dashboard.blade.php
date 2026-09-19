@extends('layouts.app')

@section('title','Dashboard')

@section('content')
@php
    $hora = now()->hour;
    $saludo = $hora < 12 ? 'Buenos dias' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');

    $cards = [
        [
            'label' => 'Total Pacientes', 'value' => number_format($totalPacientes),
            'icon' => 'M16 14a4 4 0 10-8 0M12 7a3 3 0 100 6 3 3 0 000-6zM3 20c0-2.5 2-4 5-4M21 20c0-2.5-2-4-5-4',
            'grad' => 'from-blue-500 to-blue-600', 'soft' => 'bg-blue-100/70 text-blue-600',
            'bg' => 'from-blue-100 to-blue-50', 'border' => 'border-blue-200', 'blob' => 'bg-blue-300/50', 'accent' => 'border-l-blue-500',
            'trend' => '+'.$pacientesNuevosMes.' este mes', 'up' => true,
        ],
        [
            'label' => 'Citas Hoy', 'value' => number_format($citasHoy),
            'icon' => 'M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z',
            'grad' => 'from-teal-500 to-emerald-600', 'soft' => 'bg-emerald-100/70 text-emerald-600',
            'bg' => 'from-emerald-100 to-emerald-50', 'border' => 'border-emerald-200', 'blob' => 'bg-emerald-300/50', 'accent' => 'border-l-emerald-500',
            'trend' => $citasConfirmadasHoy.' confirmadas', 'up' => true,
        ],
        [
            'label' => 'Citas Pendientes', 'value' => number_format($pendientes),
            'icon' => 'M12 8v4l3 2M12 21a9 9 0 110-18 9 9 0 010 18z',
            'grad' => 'from-orange-400 to-amber-500', 'soft' => 'bg-amber-100/70 text-amber-600',
            'bg' => 'from-amber-100 to-amber-50', 'border' => 'border-amber-200', 'blob' => 'bg-amber-300/50', 'accent' => 'border-l-amber-500',
            'trend' => $tasaPendientes.'% del total', 'up' => null,
        ],
        [
            'label' => 'Ingresos del Mes', 'value' => '$'.number_format($ingresosMes),
            'icon' => 'M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8',
            'grad' => 'from-violet-500 to-purple-600', 'soft' => 'bg-violet-100/70 text-violet-600',
            'bg' => 'from-violet-100 to-violet-50', 'border' => 'border-violet-200', 'blob' => 'bg-violet-300/50', 'accent' => 'border-l-violet-500',
            'trend' => ($deltaIngresos >= 0 ? '+' : '').$deltaIngresos.'% vs mes anterior', 'up' => $deltaIngresos >= 0,
        ],
    ];
@endphp

<!-- Hero -->
<div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-6 sm:p-8 text-white shadow-xl shadow-blue-600/20">
    <div class="absolute inset-0 opacity-10" style="background-image:radial-gradient(circle at 15% 20%, white 1.5px, transparent 1.5px),radial-gradient(circle at 80% 70%, white 1.5px, transparent 1.5px);background-size:54px 54px;"></div>
    <div class="absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/10"></div>
    <div class="absolute -right-20 top-20 h-56 w-56 rounded-full bg-white/5"></div>
    <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
        <div>
            <p class="text-blue-100 text-sm font-medium">{{ ucfirst(\Illuminate\Support\Carbon::now()->translatedFormat('l, d \d\e F \d\e Y')) }}</p>
            <h1 class="mt-1 text-2xl sm:text-3xl font-extrabold">{{ $saludo }}, {{ \Illuminate\Support\Str::of(auth()->user()->name)->explode(' ')->first() }} 👋</h1>
            <p class="mt-1 text-blue-100 max-w-md">Este es el resumen general de tu clinica. Tienes <span class="font-semibold text-white">{{ $citasHoy }}</span> citas hoy y <span class="font-semibold text-white">{{ $pendientes }}</span> pendientes por atender.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('pacientes.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white text-blue-700 hover:bg-blue-50 text-sm font-semibold px-4 py-2.5 shadow transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                Paciente
            </a>
            <a href="{{ route('citas.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur text-white text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                Cita
            </a>
            <a href="{{ route('presupuestos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur text-white text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                Presupuesto
            </a>
            <a href="{{ route('pagos.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 backdrop-blur text-white text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8"/></svg>
                Pago
            </a>
        </div>
    </div>
</div>

<!-- KPIs -->
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    @foreach ($cards as $c)
        <div class="group relative bg-gradient-to-br {{ $c['bg'] }} rounded-2xl border {{ $c['border'] }} border-l-4 {{ $c['accent'] }} p-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all overflow-hidden">
            <div class="absolute right-0 top-0 h-28 w-28 -mr-10 -mt-10 rounded-full {{ $c['blob'] }} blur-xl"></div>
            <div class="absolute -right-6 -bottom-8 h-24 w-24 rounded-full {{ $c['blob'] }} opacity-50 blur-xl"></div>
            <div class="relative flex items-start justify-between">
                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $c['grad'] }} flex items-center justify-center text-white shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/></svg>
                </div>
                @if(!is_null($c['up']))
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold px-2 py-1 rounded-full {{ $c['up'] ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['up'] ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                    </span>
                @endif
            </div>
            <p class="relative mt-4 text-3xl font-extrabold text-slate-800 leading-none">{{ $c['value'] }}</p>
            <p class="relative mt-1.5 text-sm text-slate-500">{{ $c['label'] }}</p>
            <p class="relative mt-3 pt-3 border-t border-black/10 text-xs text-slate-500 font-medium">{{ $c['trend'] }}</p>
        </div>
    @endforeach
</div>

<!-- Fila intermedia -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Citas de hoy -->
    <div class="bg-gradient-to-b from-blue-50/60 to-white rounded-2xl border border-blue-100 shadow-sm">
        <div class="px-5 py-4 border-b border-blue-100/70 flex items-center justify-between">
            <h2 class="font-bold text-slate-800 flex items-center gap-2">
                <span class="h-7 w-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg></span>
                Citas de Hoy
            </h2>
            <a href="{{ route('citas.index') }}" class="text-xs font-medium text-blue-600 hover:underline">Ver todas</a>
        </div>
        <div class="p-3 max-h-80 overflow-y-auto">
            @forelse ($citasDelDia as $cita)
                <a href="{{ route('citas.edit', $cita) }}" class="flex items-center gap-3 px-2 py-2.5 rounded-xl hover:bg-slate-50 transition">
                    <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-xs font-semibold flex items-center justify-center shrink-0">{{ $cita->paciente->iniciales ?? '--' }}</div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $cita->paciente->nombre_completo ?? 'Paciente' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $cita->motivo }} · {{ \Illuminate\Support\Str::of($cita->hora)->substr(0,5) }}</p>
                    </div>
                    @php
                        $badge = match($cita->estado) {
                            'confirmada' => 'bg-emerald-100 text-emerald-700',
                            'completada' => 'bg-blue-100 text-blue-700',
                            'cancelada'  => 'bg-rose-100 text-rose-700',
                            default      => 'bg-amber-100 text-amber-700',
                        };
                    @endphp
                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $badge }}">{{ $cita->estado_nombre }}</span>
                </a>
            @empty
                <div class="text-center py-12">
                    <div class="mx-auto h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400"><svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg></div>
                    <p class="mt-3 text-sm text-slate-400">No hay citas programadas para hoy.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tasa pendientes -->
    <div class="bg-gradient-to-b from-amber-50/60 to-white rounded-2xl border border-amber-100 shadow-sm p-5 flex flex-col">
        <h2 class="font-bold text-slate-800">Tasa de Pendientes</h2>
        <div class="flex-1 flex flex-col items-center justify-center py-4">
            @php $deg = $tasaPendientes * 3.6; @endphp
            <div class="relative h-40 w-40 rounded-full" style="background:conic-gradient(#f59e0b 0deg {{ $deg }}deg, #f1f5f9 {{ $deg }}deg 360deg)">
                <div class="absolute inset-3 bg-white rounded-full flex flex-col items-center justify-center shadow-inner">
                    <span class="text-4xl font-extrabold text-slate-800">{{ $tasaPendientes }}%</span>
                    <span class="text-xs text-slate-400">pendientes</span>
                </div>
            </div>
            <p class="mt-4 text-sm text-slate-500 text-center">{{ $pendientes }} citas pendientes de un total registrado.</p>
        </div>
    </div>

    <!-- Actividad mes -->
    <div class="bg-gradient-to-b from-emerald-50/60 to-white rounded-2xl border border-emerald-100 shadow-sm p-5">
        <h2 class="font-bold text-slate-800">Actividad del Mes</h2>
        <div class="mt-5 space-y-4">
            <div>
                <div class="flex justify-between text-sm mb-1.5"><span class="text-slate-500">Citas del mes</span><span class="font-semibold text-slate-700">{{ $citasMes }}</span></div>
                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-2.5 rounded-full bg-gradient-to-r from-blue-500 to-blue-600" style="width:{{ min(100,$citasMes) }}%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1.5"><span class="text-slate-500">Completadas</span><span class="font-semibold text-slate-700">{{ $completadasMes }}</span></div>
                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-500 to-emerald-600" style="width:{{ $citasMes ? round($completadasMes/$citasMes*100) : 0 }}%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1.5"><span class="text-slate-500">Pendientes</span><span class="font-semibold text-slate-700">{{ $pendientes }}</span></div>
                <div class="h-2.5 rounded-full bg-slate-100 overflow-hidden"><div class="h-2.5 rounded-full bg-gradient-to-r from-amber-400 to-amber-500" style="width:{{ $tasaPendientes }}%"></div></div>
            </div>
            <div class="pt-3 mt-1 border-t border-slate-100 flex items-center justify-between">
                <span class="text-sm text-slate-500">Pacientes activos</span>
                <span class="font-extrabold text-slate-800 text-lg">{{ number_format($totalPacientes) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Graficos -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-slate-800">Tendencia de Citas</h2>
                <p class="text-xs text-slate-400">Citas totales y completadas en los ultimos 6 meses</p>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-xs">
                <span class="flex items-center gap-1.5 text-slate-500"><span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>Totales</span>
                <span class="flex items-center gap-1.5 text-slate-500"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Completadas</span>
            </div>
        </div>
        <div class="p-5"><div class="h-72"><canvas id="chartCitas"></canvas></div></div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100">
            <h2 class="font-bold text-slate-800">Citas por Estado</h2>
            <p class="text-xs text-slate-400">Distribucion general</p>
        </div>
        <div class="p-5"><div class="h-72 flex items-center justify-center"><canvas id="chartEstados"></canvas></div></div>
    </div>
</div>

<!-- Rendimiento por doctor -->
<div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-bold text-slate-800">Rendimiento por Doctor</h2>
            <p class="text-xs text-slate-400">Citas atendidas y pendientes por profesional</p>
        </div>
        <a href="{{ route('reportes.index') }}" class="text-xs font-medium text-blue-600 hover:underline">Ver reportes</a>
    </div>
    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @forelse ($rendimiento as $doc)
            <div class="rounded-2xl border border-slate-200 p-4 hover:border-blue-200 hover:shadow-sm transition">
                <div class="flex items-center gap-3">
                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-sm font-semibold flex items-center justify-center">{{ $doc->iniciales }}</div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $doc->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $doc->especialidad ?? 'Odontologia' }}</p>
                    </div>
                </div>
                <div class="mt-4 flex items-center justify-between text-center">
                    <div><p class="text-lg font-extrabold text-slate-800">{{ $doc->total_citas }}</p><p class="text-[11px] text-slate-400">Total</p></div>
                    <div><p class="text-lg font-extrabold text-emerald-600">{{ $doc->completadas }}</p><p class="text-[11px] text-slate-400">Hechas</p></div>
                    <div><p class="text-lg font-extrabold text-amber-500">{{ $doc->pendientes_count }}</p><p class="text-[11px] text-slate-400">Pend.</p></div>
                </div>
                <div class="mt-3 h-1.5 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-1.5 rounded-full bg-gradient-to-r from-blue-500 to-indigo-500" style="width:{{ $doc->total_citas ? round($doc->completadas/$doc->total_citas*100) : 0 }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-sm text-slate-400 col-span-full text-center py-6">No hay doctores registrados.</p>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') {
            document.querySelectorAll('#chartCitas, #chartEstados').forEach(function (c) {
                var box = c.closest('.p-5');
                if (box) box.innerHTML = '<p class="text-sm text-slate-400 text-center py-10">No se pudo cargar la libreria de graficos. Revisa tu conexion e intenta recargar.</p>';
            });
            return;
        }
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';

        const ctx1 = document.getElementById('chartCitas');
        if (ctx1) {
            const g1 = ctx1.getContext('2d').createLinearGradient(0, 0, 0, 280);
            g1.addColorStop(0, 'rgba(59,130,246,0.25)'); g1.addColorStop(1, 'rgba(59,130,246,0)');
            const g2 = ctx1.getContext('2d').createLinearGradient(0, 0, 0, 280);
            g2.addColorStop(0, 'rgba(16,185,129,0.20)'); g2.addColorStop(1, 'rgba(16,185,129,0)');
            new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: @json($chartMeses),
                    datasets: [
                        { label: 'Totales', data: @json($chartCitasMes), borderColor: '#3b82f6', backgroundColor: g1, borderWidth: 2.5, fill: true, tension: 0.4, pointBackgroundColor: '#3b82f6', pointRadius: 4, pointHoverRadius: 6 },
                        { label: 'Completadas', data: @json($chartCompletadasMes), borderColor: '#10b981', backgroundColor: g2, borderWidth: 2.5, fill: true, tension: 0.4, pointBackgroundColor: '#10b981', pointRadius: 4, pointHoverRadius: 6 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10, titleFont: { weight: '600' } } },
                    scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }, x: { grid: { display: false } } }
                }
            });
        }

        const ctx2 = document.getElementById('chartEstados');
        if (ctx2) {
            const estados = @json($chartEstados);
            new Chart(ctx2, {
                type: 'doughnut',
                data: { labels: Object.keys(estados), datasets: [{ data: Object.values(estados), backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#f43f5e'], borderColor: '#ffffff', borderWidth: 3, hoverOffset: 8 }] },
                options: { responsive: true, maintainAspectRatio: false, cutout: '64%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, boxWidth: 8 } }, tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 10 } } }
            });
        }
    })();
</script>
@endsection
