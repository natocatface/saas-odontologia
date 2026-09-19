@extends('layouts.app')

@section('title', $presupuesto->codigo)

@section('content')
@php
    $estadoBadge = ['borrador'=>'bg-slate-100 text-slate-600','aprobado'=>'bg-emerald-100 text-emerald-700','rechazado'=>'bg-rose-100 text-rose-700'];
@endphp
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('presupuestos.index') }}" class="hover:text-blue-600">Presupuestos</a><span>/</span>
    <span class="text-slate-600 font-medium">{{ $presupuesto->codigo }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Detalle -->
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold text-slate-800">{{ $presupuesto->codigo }}</h1>
                    <p class="text-slate-500 text-sm">Emitido el {{ $presupuesto->fecha->format('d/m/Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-medium px-2 py-1 rounded-full {{ $estadoBadge[$presupuesto->estado] ?? 'bg-slate-100 text-slate-600' }}">{{ $presupuesto->estado_nombre }}</span>
                    <a href="{{ route('presupuestos.edit', $presupuesto) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-3 py-1.5 hover:bg-slate-50 transition">Editar</a>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-6 text-sm">
                <div><p class="text-slate-400">Paciente</p><p class="font-medium text-slate-700">{{ $presupuesto->paciente->nombre_completo ?? '—' }}</p></div>
                <div><p class="text-slate-400">Doctor</p><p class="font-medium text-slate-700">{{ $presupuesto->doctor->name ?? 'Sin asignar' }}</p></div>
                <div><p class="text-slate-400">Fecha</p><p class="font-medium text-slate-700">{{ $presupuesto->fecha->format('d/m/Y') }}</p></div>
            </div>

            <!-- Items -->
            <div class="mt-6 border border-slate-200 rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead><tr class="bg-slate-50 text-slate-500 text-left">
                        <th class="px-4 py-2 font-semibold w-12 text-center">Hecho</th>
                        <th class="px-4 py-2 font-semibold">Descripcion</th>
                        <th class="px-4 py-2 font-semibold w-16 text-center">Cant.</th>
                        <th class="px-4 py-2 font-semibold w-28 text-right">P. unit.</th>
                        <th class="px-4 py-2 font-semibold w-28 text-right">Subtotal</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($presupuesto->items as $item)
                            <tr class="{{ $item->realizado ? 'bg-emerald-50/40' : '' }}">
                                <td class="px-4 py-2 text-center">
                                    <form method="POST" action="{{ route('presupuestos.item.realizado', $item) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="{{ $item->realizado ? 'Marcar pendiente' : 'Marcar realizado' }}"
                                                class="h-5 w-5 rounded-md border flex items-center justify-center transition {{ $item->realizado ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 text-transparent hover:border-emerald-400' }}">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="px-4 py-2 text-slate-700 {{ $item->realizado ? 'line-through text-slate-400' : '' }}">{{ $item->descripcion }}</td>
                                <td class="px-4 py-2 text-center text-slate-600">{{ $item->cantidad }}</td>
                                <td class="px-4 py-2 text-right text-slate-600">${{ number_format($item->precio_unitario, 2) }}</td>
                                <td class="px-4 py-2 text-right font-medium text-slate-700">${{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php $totItems = $presupuesto->items->count(); $hechos = $presupuesto->items->where('realizado', true)->count(); @endphp
            @if($totItems > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Avance del plan de tratamiento</span>
                        <span class="font-semibold text-slate-700">{{ $hechos }}/{{ $totItems }} realizados</span>
                    </div>
                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                        <div class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-teal-500" style="width:{{ round($hechos / $totItems * 100) }}%"></div>
                    </div>
                </div>
            @endif

            <!-- Totales -->
            <div class="mt-4 flex justify-end">
                <div class="w-64 space-y-1.5 text-sm">
                    <div class="flex justify-between text-slate-600"><span>Subtotal</span><span>${{ number_format($presupuesto->subtotal, 2) }}</span></div>
                    <div class="flex justify-between text-slate-600"><span>Descuento</span><span>- ${{ number_format($presupuesto->descuento, 2) }}</span></div>
                    <div class="flex justify-between text-base font-extrabold text-slate-800 border-t border-slate-200 pt-2"><span>Total</span><span>${{ number_format($presupuesto->total, 2) }}</span></div>
                </div>
            </div>

            @if($presupuesto->notas)
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-100 p-4 text-sm text-slate-600"><span class="font-semibold text-slate-700">Notas:</span> {{ $presupuesto->notas }}</div>
            @endif
        </div>

        <!-- Plan de pago en cuotas -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-slate-800">Plan de pago en cuotas</h2>
                @if($presupuesto->cuotas->isNotEmpty())
                    <form method="POST" action="{{ route('cuotas.destroyPlan', $presupuesto) }}" onsubmit="return confirm('Eliminar las cuotas pendientes de este plan?');">
                        @csrf @method('DELETE')
                        <button class="text-xs font-semibold text-rose-600 hover:text-rose-700">Eliminar plan pendiente</button>
                    </form>
                @endif
            </div>

            @if($presupuesto->cuotas->isNotEmpty())
                <div class="border border-slate-200 rounded-xl overflow-hidden mb-4">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-slate-50 text-slate-500 text-left">
                            <th class="px-4 py-2 font-semibold w-16">Cuota</th>
                            <th class="px-4 py-2 font-semibold">Vence</th>
                            <th class="px-4 py-2 font-semibold">Estado</th>
                            <th class="px-4 py-2 font-semibold text-right">Monto</th>
                            <th class="px-4 py-2 font-semibold text-right">Accion</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($presupuesto->cuotas as $cuota)
                                <tr class="{{ $cuota->vencida ? 'bg-rose-50/50' : '' }}">
                                    <td class="px-4 py-2 font-medium text-slate-700">#{{ $cuota->numero }}</td>
                                    <td class="px-4 py-2 text-slate-600">{{ $cuota->vence_el->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">
                                        @php $cb = ['pagada'=>'bg-emerald-100 text-emerald-700','vencida'=>'bg-rose-100 text-rose-700','pendiente'=>'bg-amber-100 text-amber-700']; @endphp
                                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $cb[$cuota->estado] }}">{{ ucfirst($cuota->estado) }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-right font-medium text-slate-700">${{ number_format($cuota->monto, 2) }}</td>
                                    <td class="px-4 py-2 text-right">
                                        @if($cuota->pagada)
                                            <span class="text-xs text-slate-400">{{ $cuota->pago?->numero_recibo }}</span>
                                        @else
                                            <form method="POST" action="{{ route('cuotas.pagar', $cuota) }}" class="inline">
                                                @csrf
                                                <button class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Marcar pagada</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            @if($presupuesto->saldo > 0)
                <form method="POST" action="{{ route('cuotas.generar', $presupuesto) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">N° de cuotas</label>
                        <input type="number" name="numero_cuotas" min="1" max="60" value="3" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Primera cuota</label>
                        <input type="date" name="fecha_inicio" value="{{ now()->addMonthNoOverflow()->toDateString() }}" required class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1">Frecuencia</label>
                        <select name="frecuencia" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                            <option value="mensual">Mensual</option>
                            <option value="quincenal">Quincenal</option>
                            <option value="semanal">Semanal</option>
                        </select>
                    </div>
                    <button class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">Generar plan</button>
                </form>
                <p class="mt-2 text-xs text-slate-400">Se dividira el saldo pendiente de ${{ number_format($presupuesto->saldo, 2) }} en el numero de cuotas indicado. Genera de nuevo para reemplazar las cuotas no pagadas.</p>
            @else
                <p class="text-sm text-slate-400">Este presupuesto no tiene saldo pendiente.</p>
            @endif
        </div>
    </div>

    <!-- Lateral: estado + pagos -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-3">Cambiar estado</h2>
            <form method="POST" action="{{ route('presupuestos.estado', $presupuesto) }}" class="flex gap-2">
                @csrf @method('PATCH')
                <select name="estado" class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                    @foreach (\App\Models\Presupuesto::ESTADOS as $k=>$v)
                        <option value="{{ $k }}" {{ $presupuesto->estado===$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2 transition">Guardar</button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-slate-600"><span>Total</span><span class="font-medium">${{ number_format($presupuesto->total, 2) }}</span></div>
                <div class="flex justify-between text-emerald-600"><span>Pagado</span><span class="font-medium">${{ number_format($presupuesto->pagado, 2) }}</span></div>
                <div class="flex justify-between text-base font-extrabold {{ $presupuesto->saldo > 0 ? 'text-rose-600' : 'text-emerald-600' }} border-t border-slate-200 pt-2"><span>Saldo</span><span>${{ number_format($presupuesto->saldo, 2) }}</span></div>
            </div>
            @if($presupuesto->saldo > 0)
                <a href="{{ route('pagos.create', ['presupuesto' => $presupuesto->id]) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
                    Registrar pago
                </a>
            @endif

            <div class="mt-5">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Pagos registrados</p>
                @forelse ($presupuesto->pagos as $pago)
                    <div class="flex items-center justify-between py-2 border-t border-slate-100 text-sm">
                        <div>
                            <p class="text-slate-700 font-medium">${{ number_format($pago->monto, 2) }}</p>
                            <p class="text-xs text-slate-400">{{ $pago->fecha->format('d/m/Y') }} · {{ $pago->metodo_nombre }} · {{ $pago->numero_recibo }}</p>
                        </div>
                        <a href="{{ route('pagos.recibo', $pago) }}" target="_blank" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Recibo</a>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">Sin pagos registrados.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
