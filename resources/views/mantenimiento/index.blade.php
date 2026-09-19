@extends('layouts.app')

@section('title','Mantenimiento')

@section('content')
<div class="mb-5">
    <h1 class="text-2xl font-extrabold text-slate-800">Mantenimiento</h1>
    <p class="text-slate-500">Informacion del sistema y herramientas tecnicas</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Info del sistema -->
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h2 class="font-bold text-slate-800 mb-4">Informacion del sistema</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">PHP</span><span class="font-medium text-slate-700">{{ $sistema['php'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Laravel</span><span class="font-medium text-slate-700">{{ $sistema['laravel'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Entorno</span><span class="font-medium text-slate-700 capitalize">{{ $sistema['entorno'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Modo debug</span><span class="font-medium text-slate-700">{{ $sistema['debug'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Base de datos</span><span class="font-medium text-slate-700">{{ $sistema['base_datos'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Driver</span><span class="font-medium text-slate-700">{{ $sistema['driver'] }}</span></div>
            <div class="flex justify-between border-b border-slate-100 pb-2"><span class="text-slate-400">Zona horaria</span><span class="font-medium text-slate-700">{{ $sistema['zona'] }}</span></div>
        </div>

        <h2 class="font-bold text-slate-800 mt-8 mb-4">Registros almacenados</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($conteos as $label => $valor)
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 text-center">
                    <p class="text-xl font-extrabold text-slate-800">{{ number_format($valor) }}</p>
                    <p class="text-[11px] text-slate-500 leading-tight mt-1">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Herramientas -->
    <div class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10a1 1 0 001 1h14a1 1 0 001-1V9a1 1 0 00-1-1h-7l-2-2H5a1 1 0 00-1 1z"/></svg></div>
                <div><h2 class="font-bold text-slate-800">Respaldo</h2><p class="text-xs text-slate-400">Descarga la base de datos en SQL</p></div>
            </div>
            <p class="text-sm text-slate-500 mb-4">Genera un archivo .sql con la estructura y los datos de todas las tablas. Guardalo en un lugar seguro.</p>
            <a href="{{ route('mantenimiento.respaldo') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"/></svg>
                Descargar respaldo
            </a>

            <div class="mt-5 pt-5 border-t border-slate-100">
                <div class="flex items-center gap-2 mb-2">
                    <h3 class="font-bold text-slate-800 text-sm">Restaurar respaldo</h3>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-600">Cuidado</span>
                </div>
                <p class="text-sm text-slate-500 mb-3">Sube un archivo .sql para sobrescribir la base de datos actual. Esta accion <strong>reemplaza los datos existentes</strong> y no se puede deshacer.</p>
                <form method="POST" action="{{ route('mantenimiento.restaurar') }}" enctype="multipart/form-data"
                      onsubmit="return confirm('Esto SOBRESCRIBIRA la base de datos actual con el respaldo. Esta accion no se puede deshacer. Deseas continuar?');">
                    @csrf
                    <input type="file" name="respaldo" accept=".sql" required
                           class="block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 mb-3">
                    <button class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 transition">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006 5.3L4 7M4 15a8 8 0 0014 3.7l2-1.7"/></svg>
                        Restaurar desde archivo
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v6h6M20 20v-6h-6M20 9A8 8 0 006 5.3L4 7M4 15a8 8 0 0014 3.7l2-1.7"/></svg></div>
                <div><h2 class="font-bold text-slate-800">Limpiar cache</h2><p class="text-xs text-slate-400">Cache, config, vistas y rutas</p></div>
            </div>
            <p class="text-sm text-slate-500 mb-4">Util tras cambios de configuracion o si ves contenido desactualizado.</p>
            <form method="POST" action="{{ route('mantenimiento.cache') }}">
                @csrf
                <button class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-4 py-2.5 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5 4v6m4-6v6M9 7V4h6v3"/></svg>
                    Limpiar cache ahora
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
