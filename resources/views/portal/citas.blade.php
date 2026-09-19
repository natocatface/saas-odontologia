@extends('portal.layout')

@section('title','Mis citas')

@section('content')
<h1 class="text-2xl font-extrabold text-slate-800 mb-5">Mis citas</h1>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-slate-500 text-left">
                <th class="px-5 py-3 font-semibold">Fecha</th>
                <th class="px-5 py-3 font-semibold">Hora</th>
                <th class="px-5 py-3 font-semibold">Motivo</th>
                <th class="px-5 py-3 font-semibold">Doctor</th>
                <th class="px-5 py-3 font-semibold">Estado</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($citas as $cita)
                    @php $cb = ['confirmada'=>'bg-emerald-100 text-emerald-700','completada'=>'bg-blue-100 text-blue-700','cancelada'=>'bg-rose-100 text-rose-700','pendiente'=>'bg-amber-100 text-amber-700']; @endphp
                    <tr>
                        <td class="px-5 py-3 text-slate-700">{{ $cita->fecha->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $cita->hora ? \Illuminate\Support\Str::of($cita->hora)->substr(0,5) : '—' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $cita->motivo ?: 'Consulta' }}</td>
                        <td class="px-5 py-3 text-slate-600">{{ $cita->doctor->name ?? '—' }}</td>
                        <td class="px-5 py-3"><span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $cb[$cita->estado] ?? 'bg-slate-100 text-slate-600' }}">{{ $cita->estado_nombre }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No tienes citas registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($citas->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $citas->links() }}</div>
    @endif
</div>
@endsection
