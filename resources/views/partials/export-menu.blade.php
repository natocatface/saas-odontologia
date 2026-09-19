@php $qs = request()->query(); unset($qs['page']); @endphp
<div x-data="{ o:false }" class="relative">
    <button @click="o=!o" type="button" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
        Exportar
        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
    </button>
    <div x-show="o" @click.outside="o=false" x-cloak x-transition class="absolute right-0 mt-2 w-44 rounded-xl bg-white shadow-xl ring-1 ring-slate-200 z-30 overflow-hidden">
        <a href="{{ route($ruta, array_merge($qs, ['formato' => 'csv'])) }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
            <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5"/></svg>
            Excel (CSV)
        </a>
        <a href="{{ route($ruta, array_merge($qs, ['formato' => 'pdf'])) }}" target="_blank" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50 border-t border-slate-100">
            <svg class="h-4 w-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg>
            PDF / Imprimir
        </a>
    </div>
</div>
