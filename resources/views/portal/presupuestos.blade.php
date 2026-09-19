@extends('portal.layout')

@section('title','Presupuestos')

@section('content')
<h1 class="text-2xl font-extrabold text-slate-800 mb-5">Mis presupuestos</h1>

<div class="space-y-4">
    @forelse ($presupuestos as $p)
        @php $eb = ['borrador'=>'bg-slate-100 text-slate-600','aprobado'=>'bg-emerald-100 text-emerald-700','rechazado'=>'bg-rose-100 text-rose-700']; @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="font-bold text-slate-800">{{ $p->codigo }}</p>
                    <p class="text-xs text-slate-400">{{ $p->fecha->format('d/m/Y') }}</p>
                </div>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $eb[$p->estado] ?? 'bg-slate-100 text-slate-600' }}">{{ $p->estado_nombre }}</span>
            </div>
            <div class="mt-4 grid grid-cols-3 gap-3 text-sm">
                <div><p class="text-slate-400">Total</p><p class="font-semibold text-slate-700">${{ number_format($p->total, 2) }}</p></div>
                <div><p class="text-slate-400">Pagado</p><p class="font-semibold text-emerald-600">${{ number_format($p->pagado, 2) }}</p></div>
                <div><p class="text-slate-400">Saldo</p><p class="font-semibold {{ $p->saldo > 0 ? 'text-rose-600' : 'text-slate-500' }}">${{ number_format($p->saldo, 2) }}</p></div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-10 text-center text-slate-400">No tienes presupuestos registrados.</div>
    @endforelse
</div>
@endsection
