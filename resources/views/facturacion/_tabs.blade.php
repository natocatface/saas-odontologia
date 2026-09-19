@php
    $tabs = [
        ['label' => 'Comprobantes',  'route' => route('facturacion.comprobantes.index'), 'active' => request()->routeIs('facturacion.comprobantes.*')],
        ['label' => 'Configuracion', 'route' => route('facturacion.config.edit'),         'active' => request()->routeIs('facturacion.config.*')],
    ];
@endphp
<div class="inline-flex items-center gap-1 rounded-xl bg-slate-100 p-1 mb-6">
    @foreach ($tabs as $t)
        <a href="{{ $t['route'] }}"
           class="rounded-lg px-4 py-2 text-sm font-semibold transition
                  {{ $t['active'] ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            {{ $t['label'] }}
        </a>
    @endforeach
</div>
