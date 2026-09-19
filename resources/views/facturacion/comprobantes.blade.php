@extends('layouts.app')

@section('title','Comprobantes Electronicos')

@php
    $estadoStyles = [
        'aceptado'  => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'enviado'   => 'bg-blue-50 text-blue-700 ring-blue-200',
        'borrador'  => 'bg-slate-100 text-slate-600 ring-slate-200',
        'rechazado' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'error'     => 'bg-rose-50 text-rose-700 ring-rose-200',
        'anulado'   => 'bg-amber-50 text-amber-700 ring-amber-200',
    ];
    $estadoDot = [
        'aceptado'  => 'bg-emerald-500',
        'enviado'   => 'bg-blue-500',
        'borrador'  => 'bg-slate-400',
        'rechazado' => 'bg-rose-500',
        'error'     => 'bg-rose-500',
        'anulado'   => 'bg-amber-500',
    ];
    $tipoLabels = [
        'factura' => 'Factura', 'boleta' => 'Boleta',
        'nota_credito' => 'N. Credito', 'nota_debito' => 'N. Debito',
    ];
    $monedaSimbolo = ['PEN' => 'S/', 'USD' => '$'];
@endphp

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Inicio</a><span>/</span>
    <span class="text-slate-600 font-medium">Facturacion Electronica</span>
</div>

{{-- Encabezado --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-600 shadow-sm mb-6">
    <div class="absolute -right-8 -top-10 h-40 w-40 rounded-full bg-white/10"></div>
    <div class="absolute -right-16 top-10 h-40 w-40 rounded-full bg-white/5"></div>
    <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-6">
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 shrink-0 rounded-2xl bg-white/15 ring-1 ring-white/20 flex items-center justify-center">
                <svg class="h-7 w-7 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a1 1 0 011 1v18l-3-2-2 2-2-2-2 2-2-2-3 2V3a1 1 0 011-1zM9 8h6M9 12h6"/></svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-white">Comprobantes Electronicos</h1>
                <p class="text-blue-100 text-sm">Boletas, facturas y notas emitidas ante SUNAT</p>
            </div>
        </div>
        <a href="{{ route('facturacion.config.edit') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white/95 hover:bg-white text-blue-700 text-sm font-semibold px-4 py-2.5 shadow-sm transition self-start sm:self-auto">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9a3 3 0 100 6 3 3 0 000-6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19 12a7 7 0 00-.1-1.2l2-1.6-2-3.4-2.4 1a7 7 0 00-2-1.2l-.4-2.6h-4l-.4 2.6a7 7 0 00-2 1.2l-2.4-1-2 3.4 2 1.6A7 7 0 005 12c0 .4 0 .8.1 1.2l-2 1.6 2 3.4 2.4-1c.6.5 1.3.9 2 1.2l.4 2.6h4l.4-2.6c.7-.3 1.4-.7 2-1.2l2.4 1 2-3.4-2-1.6c.1-.4.1-.8.1-1.2z"/></svg>
            Configuracion
        </a>
    </div>
</div>

@include('facturacion._tabs')

@if (session('status'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 mb-5">{{ session('status') }}</div>
@endif
@if (session('error'))
    <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700 mb-5">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
        <ul class="list-disc list-inside">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

@if (! empty($sinTabla))
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center">
        <div class="mx-auto h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-4">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a1 1 0 011 1v18l-3-2-2 2-2-2-2 2-2-2-3 2V3a1 1 0 011-1zM9 8h6M9 12h6"/></svg>
        </div>
        <h2 class="text-lg font-bold text-slate-800">El modulo de facturacion aun no esta migrado</h2>
        <p class="text-slate-500 mt-1 max-w-md mx-auto">Ejecuta <code class="text-blue-600">php artisan migrate</code> para crear las tablas de comprobantes, luego configura la emision.</p>
        <a href="{{ route('facturacion.config.edit') }}" class="inline-flex items-center gap-2 mt-5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Ir a la configuracion</a>
    </div>
@else
  <div x-data="{ open:false, action:'', label:'' }">
    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach ([
            ['Total',      $stats['total'],      'text-slate-800',   'bg-slate-100 text-slate-500',    'border-slate-200',   '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h10"/>'],
            ['Aceptados',  $stats['aceptado'],   'text-emerald-600', 'bg-emerald-50 text-emerald-500', 'border-emerald-100', '<path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/>'],
            ['Rechazados', $stats['rechazado'],  'text-rose-600',    'bg-rose-50 text-rose-500',       'border-rose-100',    '<path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>'],
            ['Pendientes', $stats['pendiente'],  'text-blue-600',    'bg-blue-50 text-blue-500',       'border-blue-100',    '<path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2M12 21a9 9 0 100-18 9 9 0 000 18z"/>'],
        ] as [$label, $valor, $color, $iconBg, $border, $icon])
            <div class="group bg-white rounded-2xl border {{ $border }} shadow-sm p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $label }}</p>
                        <p class="text-3xl font-extrabold {{ $color }} mt-1 leading-none">{{ $valor }}</p>
                    </div>
                    <div class="h-10 w-10 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9">{!! $icon !!}</svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filtros --}}
    <form method="GET" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-5 grid grid-cols-1 md:grid-cols-4 gap-3">
        <div class="md:col-span-2 relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg>
            </span>
            <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar serie, numero o cliente..."
                   class="w-full rounded-xl border border-slate-300 pl-9 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
        </div>
        <select name="estado" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            <option value="">Todos los estados</option>
            @foreach (['aceptado' => 'Aceptado', 'enviado' => 'Enviado', 'borrador' => 'Borrador', 'rechazado' => 'Rechazado', 'error' => 'Error', 'anulado' => 'Anulado'] as $k => $v)
                <option value="{{ $k }}" @selected($estado === $k)>{{ $v }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="tipo" class="flex-1 rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                <option value="">Todos</option>
                @foreach ($tipoLabels as $k => $v)
                    <option value="{{ $k }}" @selected($tipo === $k)>{{ $v }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-sm transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18l-7 8v5l-4 2v-7z"/></svg>
                Filtrar
            </button>
        </div>
    </form>

    {{-- Tabla --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide bg-slate-50/80 border-b border-slate-100">
                        <th class="px-5 py-3.5">Comprobante</th>
                        <th class="px-5 py-3.5">Cliente</th>
                        <th class="px-5 py-3.5">Fecha</th>
                        <th class="px-5 py-3.5 text-right">Total</th>
                        <th class="px-5 py-3.5">Estado</th>
                        <th class="px-5 py-3.5 text-right">Archivos</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($comprobantes as $c)
                        @php $r = $c->receptor_snapshot ?? []; @endphp
                        <tr class="hover:bg-blue-50/40 transition-colors">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-md bg-blue-50 text-blue-700 text-xs font-semibold px-2 py-0.5 ring-1 ring-blue-100">{{ $tipoLabels[$c->tipo] ?? ucfirst($c->tipo) }}</span>
                                <span class="block font-bold text-slate-700 mt-1 tracking-tight">{{ $c->serie }}-{{ str_pad((string) $c->numero, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="block font-medium text-slate-700">{{ $r['razonSocial'] ?? '—' }}</span>
                                <span class="block text-xs text-slate-400">{{ $r['tipoIdentificacion'] ?? '' }} {{ $r['identificacion'] ?? '' }}</span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3.5 text-right font-bold text-slate-800 whitespace-nowrap">{{ $monedaSimbolo[$c->moneda] ?? $c->moneda }} {{ number_format((float) $c->total, 2) }}</td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full ring-1 px-2.5 py-1 text-xs font-semibold {{ $estadoStyles[$c->estado] ?? 'bg-slate-100 text-slate-600 ring-slate-200' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $estadoDot[$c->estado] ?? 'bg-slate-400' }}"></span>
                                    {{ ucfirst($c->estado) }}
                                </span>
                                @if ($c->estado === 'rechazado' && $c->error)
                                    <span class="block text-[11px] text-rose-400 mt-1 max-w-[220px] truncate" title="{{ $c->error }}">{{ $c->error }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('facturacion.comprobantes.representacion', $c) }}" target="_blank" title="Ver / Imprimir"
                                       class="inline-flex items-center gap-1 rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100 transition">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.9"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="2.5"/></svg>
                                        Ver
                                    </a>
                                    @if ($c->xml_path)
                                        <a href="{{ route('facturacion.comprobantes.xml', $c) }}" title="Descargar XML"
                                           class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">XML</a>
                                    @endif
                                    @if ($c->cdr_path)
                                        <a href="{{ route('facturacion.comprobantes.cdr', $c) }}" title="Descargar CDR"
                                           class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">CDR</a>
                                    @endif
                                    @if ($c->estado === 'aceptado' && in_array($c->tipo, ['factura', 'nota_credito', 'nota_debito']))
                                        <button type="button" title="Anular (comunicacion de baja)"
                                                @click="open=true; action='{{ route('facturacion.comprobantes.anular', $c) }}'; label='{{ $c->serie }}-{{ str_pad((string) $c->numero, 6, '0', STR_PAD_LEFT) }}'"
                                                class="inline-flex items-center gap-1 rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-100 transition">Anular</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center text-slate-400">
                                <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-50 to-slate-100 text-blue-400 flex items-center justify-center mb-4 ring-1 ring-slate-100">
                                    <svg class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a1 1 0 011 1v18l-3-2-2 2-2-2-2 2-2-2-3 2V3a1 1 0 011-1zM9 8h6M9 12h6"/></svg>
                                </div>
                                <p class="text-slate-600 font-semibold">Aun no hay comprobantes emitidos</p>
                                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">
                                    <a href="{{ route('facturacion.config.edit') }}" class="text-blue-600 font-semibold hover:underline">Revisa la configuracion</a> y registra un pago con la emision automatica activa.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $comprobantes->links() }}</div>

    {{-- Modal de anulacion --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="open=false" x-transition.opacity></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex items-start gap-3">
                <div class="h-10 w-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L14.7 3.9a2 2 0 00-3.4 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800">Anular comprobante</h3>
                    <p class="text-sm text-slate-500">Comunicacion de baja ante SUNAT para <span class="font-semibold" x-text="label"></span>. Esta accion no se puede deshacer.</p>
                </div>
            </div>
            <form :action="action" method="POST" class="mt-4">
                @csrf
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Motivo de la anulacion <span class="text-rose-500">*</span></label>
                <textarea name="motivo" rows="3" required maxlength="100" placeholder="Ej. Error en los datos del comprobante"
                          class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-rose-500 focus:ring-2 focus:ring-rose-200"></textarea>
                <div class="flex items-center justify-end gap-3 mt-4">
                    <button type="button" @click="open=false" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2 hover:bg-slate-50 transition">Cancelar</button>
                    <button type="submit" class="rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2 transition">Anular comprobante</button>
                </div>
            </form>
        </div>
    </div>
  </div>
@endif
@endsection
