@extends('layouts.app')

@section('title','Configuracion')

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <span class="text-slate-600 font-medium">Configuracion</span>
</div>

<div class="max-w-3xl">
    <div class="mb-5">
        <h1 class="text-2xl font-extrabold text-slate-800">Configuracion</h1>
        <p class="text-slate-500">Parametros generales de la clinica</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
            <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('configuracion.update') }}" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Datos de la clinica -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Datos de la clinica</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre de la clinica <span class="text-rose-500">*</span></label>
                    <input type="text" name="nombre_clinica" value="{{ old('nombre_clinica', $config['nombre_clinica']) }}" required
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">NIT / RUC</label>
                    <input type="text" name="nit" value="{{ old('nit', $config['nit']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Telefono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $config['telefono']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Correo</label>
                    <input type="email" name="email" value="{{ old('email', $config['email']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $config['ciudad']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Direccion</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $config['direccion']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>
        </div>

        <!-- Operacion -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Operacion</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Simbolo de moneda <span class="text-rose-500">*</span></label>
                    <input type="text" name="moneda" value="{{ old('moneda', $config['moneda']) }}" required maxlength="5"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Impuesto (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="impuesto" value="{{ old('impuesto', $config['impuesto']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Sillas / consultorios</label>
                    <input type="number" min="1" max="50" name="num_sillas" value="{{ old('num_sillas', $config['num_sillas'] ?? 3) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <p class="text-xs text-slate-400 mt-1">Numero de sillas disponibles para agendar citas.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Horario de atencion</label>
                    <input type="text" name="horario" value="{{ old('horario', $config['horario']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Mensaje de recordatorio de citas</label>
                    <textarea name="mensaje_recordatorio" rows="3"
                              class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('mensaje_recordatorio', $config['mensaje_recordatorio']) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Guardar configuracion</button>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
        </div>
    </form>
</div>
@endsection
