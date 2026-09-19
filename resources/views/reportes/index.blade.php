@extends('layouts.app')

@section('title','Reportes')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Reportes</h1>
        <p class="text-slate-500">Indicadores del negocio en el periodo seleccionado</p>
    </div>
    <form method="GET" class="flex flex-wrap items-end gap-2">
        <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Desde</label>
            <input type="date" name="desde" value="{{ $desde }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>
        <div>
            <label class="block text-[11px] font-medium text-slate-500 mb-1">Hasta</label>
            <input type="date" name="hasta" value="{{ $hasta }}" class="rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>
        <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 transition">Aplicar</button>
    </form>
</div>

<!-- KPIs -->
@php
    $cards = [
        ['Ingresos del periodo', '$'.number_format($kpis['ingresos'],2), 'from-emerald-500 to-teal-600'],
        ['Pagos registrados', number_format($kpis['numPagos']), 'from-blue-500 to-blue-600'],
        ['Citas en el periodo', number_format($kpis['numCitas']), 'from-violet-500 to-purple-600'],
        ['Pacientes nuevos', number_format($kpis['pacientesNuevos']), 'from-orange-400 to-amber-500'],
    ];
@endphp
<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    @foreach ($cards as [$label,$val,$grad])
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $grad }} mb-3"></div>
            <p class="text-2xl font-extrabold text-slate-800 leading-tight">{{ $val }}</p>
            <p class="text-sm text-slate-500">{{ $label }}</p>
        </div>
    @endforeach
</div>

<!-- Graficos fila 1 -->
<div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
        <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Ingresos por mes</h2></div>
        <div class="p-5"><div class="h-72"><canvas id="rIngresos"></canvas></div></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Ingresos por metodo</h2></div>
        <div class="p-5"><div class="h-72 flex items-center justify-center"><canvas id="rMetodo"></canvas></div></div>
    </div>
</div>

<!-- Graficos fila 2 -->
<div class="mt-4 grid grid-cols-1 lg:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
        <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Citas por estado</h2></div>
        <div class="p-5"><div class="h-72 flex items-center justify-center"><canvas id="rEstado"></canvas></div></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
        <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Citas completadas por doctor</h2></div>
        <div class="p-5"><div class="h-72"><canvas id="rDoctor"></canvas></div></div>
    </div>
</div>

<!-- Top tratamientos -->
<div class="mt-4 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Tratamientos mas cotizados</h2></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                <th class="px-5 py-3 font-semibold">#</th>
                <th class="px-5 py-3 font-semibold">Tratamiento</th>
                <th class="px-5 py-3 font-semibold text-center">Veces</th>
                <th class="px-5 py-3 font-semibold text-right">Importe</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($topTratamientos as $i => $t)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3 text-slate-400">{{ $i+1 }}</td>
                        <td class="px-5 py-3 text-slate-700 font-medium">{{ $t->descripcion }}</td>
                        <td class="px-5 py-3 text-center text-slate-600">{{ (int) $t->veces }}</td>
                        <td class="px-5 py-3 text-right font-semibold text-slate-800">${{ number_format($t->importe, 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">No hay datos de tratamientos en este periodo.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    (function () {
        if (typeof Chart === 'undefined') return;
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#94a3b8';
        const money = (v) => '$' + Number(v).toLocaleString('es', { minimumFractionDigits: 0 });

        // Ingresos por mes (barras)
        const c1 = document.getElementById('rIngresos');
        if (c1) new Chart(c1, {
            type: 'bar',
            data: {
                labels: @json($labelsMes),
                datasets: [{ label: 'Ingresos', data: @json($ingresosMes), backgroundColor: '#3b82f6', borderRadius: 8, maxBarThickness: 46 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor:'#0f172a', padding:12, cornerRadius:10, callbacks: { label: (x) => money(x.raw) } } },
                scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { callback: (v) => money(v) } }, x: { grid: { display: false } } }
            }
        });

        // Ingresos por metodo (doughnut)
        const c2 = document.getElementById('rMetodo');
        if (c2) { const d = @json($porMetodo); new Chart(c2, {
            type: 'doughnut',
            data: { labels: Object.keys(d), datasets: [{ data: Object.values(d), backgroundColor: ['#10b981','#3b82f6','#8b5cf6','#f59e0b'], borderColor:'#fff', borderWidth:3, hoverOffset:8 }] },
            options: { responsive:true, maintainAspectRatio:false, cutout:'62%', plugins: { legend: { position:'bottom', labels:{ usePointStyle:true, pointStyle:'circle', padding:14, boxWidth:8 } }, tooltip: { backgroundColor:'#0f172a', padding:12, cornerRadius:10, callbacks:{ label:(x)=>x.label+': '+money(x.raw) } } } }
        }); }

        // Citas por estado (doughnut)
        const c3 = document.getElementById('rEstado');
        if (c3) { const d = @json($citasEstado); new Chart(c3, {
            type: 'doughnut',
            data: { labels: Object.keys(d), datasets: [{ data: Object.values(d), backgroundColor: ['#f59e0b','#10b981','#3b82f6','#f43f5e'], borderColor:'#fff', borderWidth:3, hoverOffset:8 }] },
            options: { responsive:true, maintainAspectRatio:false, cutout:'62%', plugins: { legend: { position:'bottom', labels:{ usePointStyle:true, pointStyle:'circle', padding:14, boxWidth:8 } }, tooltip: { backgroundColor:'#0f172a', padding:12, cornerRadius:10 } } }
        }); }

        // Citas por doctor (barras horizontales)
        const c4 = document.getElementById('rDoctor');
        if (c4) { const d = @json($porDoctor);
            new Chart(c4, {
                type: 'bar',
                data: { labels: d.map(x => x.nombre), datasets: [{ label: 'Completadas', data: d.map(x => x.total), backgroundColor: '#8b5cf6', borderRadius: 8, maxBarThickness: 26 }] },
                options: { indexAxis: 'y', responsive:true, maintainAspectRatio:false, plugins: { legend: { display:false }, tooltip: { backgroundColor:'#0f172a', padding:12, cornerRadius:10 } }, scales: { x: { beginAtZero:true, grid:{ color:'#f1f5f9' }, ticks:{ precision:0 } }, y: { grid:{ display:false } } } }
            });
        }
    })();
</script>
@endsection
