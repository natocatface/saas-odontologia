@extends('layouts.app')

@section('title','Pagos')

@section('content')
@php
    $metodoBadge = ['efectivo'=>'bg-emerald-100 text-emerald-700','tarjeta'=>'bg-blue-100 text-blue-700','transferencia'=>'bg-violet-100 text-violet-700','qr'=>'bg-amber-100 text-amber-700'];
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Pagos</h1>
        <p class="text-slate-500">Registro de cobros e ingresos de la clinica</p>
    </div>
    <div class="flex items-center gap-2">
        @include('partials.export-menu', ['ruta' => 'export.pagos'])
        <a href="{{ route('pagos.create') }}"
           class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-emerald-600/20 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Registrar pago
        </a>
    </div>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['hoy'], 2) }}</p><p class="text-xs text-slate-500">Ingresos hoy</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3zM12 9a3 3 0 100 6 3 3 0 000-6z"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">${{ number_format($stats['mes'], 2) }}</p><p class="text-xs text-slate-500">Ingresos del mes</p></div>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></div>
        <div><p class="text-xl font-extrabold text-slate-800">{{ number_format($stats['registros']) }}</p><p class="text-xs text-slate-500">Pagos registrados</p></div>
    </div>
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg></span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar paciente..."
               class="w-full rounded-xl border border-slate-300 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <select name="metodo" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todos los metodos</option>
        @foreach (\App\Models\Pago::METODOS as $k=>$v)
            <option value="{{ $k }}" {{ $metodo===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar||$metodo)
        <a href="{{ route('pagos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Fecha</th>
                    <th class="px-5 py-3 font-semibold">Paciente</th>
                    <th class="px-5 py-3 font-semibold">Presupuesto</th>
                    <th class="px-5 py-3 font-semibold">Metodo</th>
                    <th class="px-5 py-3 font-semibold">Monto</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($pagos as $pago)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3 text-slate-600">{{ $pago->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-700">{{ $pago->paciente->nombre_completo ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @if($pago->presupuesto)
                                <a href="{{ route('presupuestos.show', $pago->presupuesto) }}" class="text-blue-600 hover:underline">{{ $pago->presupuesto->codigo }}</a>
                            @else<span class="text-slate-400">—</span>@endif
                        </td>
                        <td class="px-5 py-3"><span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $metodoBadge[$pago->metodo] ?? 'bg-slate-100 text-slate-600' }}">{{ $pago->metodo_nombre }}</span></td>
                        <td class="px-5 py-3 font-semibold text-emerald-600">${{ number_format($pago->monto, 2) }}</td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('pagos.recibo', $pago) }}" target="_blank" title="Ver recibo" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5M9 13h6M9 17h6"/></svg></a>
                                <form method="POST" action="{{ route('pagos.destroy', $pago) }}" onsubmit="return confirm('¿Eliminar este pago?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">No hay pagos registrados. <a href="{{ route('pagos.create') }}" class="text-blue-600 font-medium">Registrar el primero</a>.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pagos->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $pagos->links() }}</div>
    @endif
</div>
@endsection
