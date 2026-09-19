@extends('layouts.app')

@section('title', $paciente->nombre_completo)

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('pacientes.index') }}" class="hover:text-blue-600">Pacientes</a><span>/</span>
    <span class="text-slate-600 font-medium">{{ $paciente->nombre_completo }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <!-- Tarjeta perfil -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
            <div class="mx-auto h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-2xl font-bold flex items-center justify-center">{{ $paciente->iniciales }}</div>
            <h1 class="mt-4 text-xl font-extrabold text-slate-800">{{ $paciente->nombre_completo }}</h1>
            <div class="mt-1">
                @if($paciente->activo)
                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                @else
                    <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">Inactivo</span>
                @endif
            </div>

            <div class="mt-6 flex items-center gap-2 justify-center">
                <a href="{{ route('pacientes.edit', $paciente) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 transition">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('pacientes.estado', $paciente) }}">
                    @csrf @method('PATCH')
                    <button class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2 hover:bg-slate-50 transition">
                        {{ $paciente->activo ? 'Desactivar' : 'Activar' }}
                    </button>
                </form>
            </div>

            <a href="{{ route('pacientes.odontograma', $paciente) }}"
               class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 22h4M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.1h6c0-.8.4-1.6 1-2.1A7 7 0 0012 2z"/></svg>
                Ver odontograma
                @php
                    $hallazgos = collect($paciente->odontograma ?? [])->filter(function ($d) {
                        $gen = $d['general'] ?? $d['estado'] ?? 'sano';
                        $caras = collect($d['caras'] ?? [])->contains(fn ($c) => $c !== 'sano');
                        return $gen !== 'sano' || $caras;
                    })->count();
                @endphp
                @if($hallazgos > 0)<span class="ml-1 text-[11px] bg-white/20 rounded-full px-2 py-0.5">{{ $hallazgos }}</span>@endif
            </a>

            <a href="{{ route('pacientes.estado-cuenta', $paciente) }}"
               class="mt-3 w-full inline-flex items-center justify-center gap-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-sm font-semibold px-4 py-2.5 transition">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18v10H3zM12 9a3 3 0 100 6 3 3 0 000-6zM6 9h.01M18 15h.01"/></svg>
                Estado de cuenta
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mt-5">
            <h2 class="font-bold text-slate-800 mb-4">Datos de contacto</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Documento</dt><dd class="text-slate-700 font-medium text-right">{{ $paciente->documento ?: '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Telefono</dt><dd class="text-slate-700 font-medium text-right">{{ $paciente->telefono ?: '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Correo</dt><dd class="text-slate-700 font-medium text-right break-all">{{ $paciente->email ?: '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Nacimiento</dt><dd class="text-slate-700 font-medium text-right">{{ $paciente->fecha_nacimiento ? $paciente->fecha_nacimiento->format('d/m/Y') : '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Edad</dt><dd class="text-slate-700 font-medium text-right">{{ $paciente->edad !== null ? $paciente->edad.' anos' : '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Genero</dt><dd class="text-slate-700 font-medium text-right">{{ ['M'=>'Masculino','F'=>'Femenino','O'=>'Otro'][$paciente->genero] ?? '—' }}</dd></div>
                <div class="flex justify-between gap-3"><dt class="text-slate-400">Direccion</dt><dd class="text-slate-700 font-medium text-right">{{ $paciente->direccion ?: '—' }}</dd></div>
            </dl>
        </div>
    </div>

    <!-- Columna principal -->
    <div class="lg:col-span-2" x-data="{ tab: new URLSearchParams(location.search).get('tab') || 'resumen' }">
        <!-- Tabs -->
        <div class="flex items-center gap-1 mb-4 bg-white rounded-xl border border-slate-200 p-1 shadow-sm w-fit">
            <button @click="tab='resumen'" :class="tab==='resumen' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold transition">Resumen</button>
            <button @click="tab='evolucion'" :class="tab==='evolucion' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold transition">Evolucion ({{ $paciente->evoluciones->count() }})</button>
            <button @click="tab='archivos'" :class="tab==='archivos' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold transition">Archivos ({{ $paciente->archivos->count() }})</button>
            <button @click="tab='consentimientos'" :class="tab==='consentimientos' ? 'bg-blue-600 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold transition">Consentimientos ({{ $paciente->consentimientos->count() }})</button>
        </div>

        <!-- Panel resumen -->
        <div x-show="tab==='resumen'" class="space-y-5">
        <!-- Info clinica / antecedentes -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Antecedentes medicos</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl bg-rose-50 border border-rose-100 p-4">
                    <p class="text-xs font-semibold text-rose-600 uppercase tracking-wide mb-1">Alergias</p>
                    <p class="text-sm text-slate-700">{{ $paciente->alergias ?: 'Sin alergias registradas' }}</p>
                </div>
                <div class="rounded-xl bg-amber-50 border border-amber-100 p-4">
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide mb-1">Enfermedades</p>
                    @if(!empty($paciente->enfermedades_nombres))
                        <div class="flex flex-wrap gap-1">
                            @foreach ($paciente->enfermedades_nombres as $enf)
                                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-white border border-amber-200 text-amber-700">{{ $enf }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-slate-700">Sin antecedentes registrados</p>
                    @endif
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Medicacion actual</p>
                    <p class="text-sm text-slate-700">{{ $paciente->medicacion ?: 'Ninguna registrada' }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Habitos · Tipo de sangre</p>
                    <p class="text-sm text-slate-700">
                        {{ !empty($paciente->habitos_nombres) ? implode(', ', $paciente->habitos_nombres) : 'Sin habitos' }}
                        @if($paciente->tipo_sangre) · <span class="font-semibold">{{ $paciente->tipo_sangre }}</span>@endif
                    </p>
                </div>
            </div>
            @if($paciente->antecedentes_notas || $paciente->observaciones)
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($paciente->antecedentes_notas)
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Notas de antecedentes</p>
                            <p class="text-sm text-slate-700">{{ $paciente->antecedentes_notas }}</p>
                        </div>
                    @endif
                    @if($paciente->observaciones)
                        <div class="rounded-xl bg-slate-50 border border-slate-100 p-4">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Observaciones</p>
                            <p class="text-sm text-slate-700">{{ $paciente->observaciones }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Historial de citas -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-bold text-slate-800">Historial de citas</h2>
                <span class="text-xs text-slate-400">{{ $paciente->citas->count() }} registros</span>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($paciente->citas as $cita)
                    <div class="px-6 py-3 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-lg bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] uppercase leading-none">{{ $cita->fecha->translatedFormat('M') }}</span>
                            <span class="text-sm font-bold leading-none">{{ $cita->fecha->format('d') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $cita->motivo ?: 'Consulta' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $cita->doctor->name ?? 'Sin doctor' }} · {{ \Illuminate\Support\Str::of($cita->hora)->substr(0,5) }}</p>
                        </div>
                        @php
                            $badge = match($cita->estado) {
                                'confirmada' => 'bg-emerald-100 text-emerald-700',
                                'completada' => 'bg-blue-100 text-blue-700',
                                'cancelada'  => 'bg-rose-100 text-rose-700',
                                default      => 'bg-amber-100 text-amber-700',
                            };
                        @endphp
                        <span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $badge }}">{{ $cita->estado_nombre }}</span>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-slate-400">Este paciente aun no tiene citas registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Panel evolucion -->
    <div x-show="tab==='evolucion'" x-cloak class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Registrar evolucion</h2>
            <form method="POST" action="{{ route('evoluciones.store', $paciente) }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                @csrf
                <input type="date" name="fecha" value="{{ now()->toDateString() }}" required class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500">
                <input type="text" name="diente" placeholder="Diente (opc.)" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500">
                <input type="text" name="descripcion" required placeholder="Que se realizo / observaciones..." class="sm:col-span-2 rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500">
                <button class="sm:col-span-4 justify-self-start rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Agregar evolucion</button>
            </form>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Historial de evolucion</h2></div>
            <div class="divide-y divide-slate-100">
                @forelse ($paciente->evoluciones as $evo)
                    <div class="px-6 py-4 flex items-start gap-3">
                        <div class="h-9 w-9 rounded-lg bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0">
                            <span class="text-[10px] uppercase leading-none">{{ $evo->fecha->translatedFormat('M') }}</span>
                            <span class="text-sm font-bold leading-none">{{ $evo->fecha->format('d') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm text-slate-700">{{ $evo->descripcion }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $evo->fecha->format('d/m/Y') }}
                                @if($evo->diente) · Diente {{ $evo->diente }}@endif
                                @if($evo->user) · {{ $evo->user->name }}@endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('evoluciones.destroy', $evo) }}" onsubmit="return confirm('¿Eliminar esta evolucion?');">
                            @csrf @method('DELETE')
                            <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                        </form>
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-slate-400">Sin evoluciones registradas.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Panel archivos -->
    <div x-show="tab==='archivos'" x-cloak class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Subir archivo</h2>
            <form method="POST" action="{{ route('archivos.store', $paciente) }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-center">
                @csrf
                <input type="file" name="archivo" required accept=".jpg,.jpeg,.png,.webp,.gif,.pdf" class="sm:col-span-2 block w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                <select name="categoria" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500">
                    @foreach (\App\Models\Archivo::CATEGORIAS as $k=>$v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
                <button class="sm:col-span-3 justify-self-start rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Subir archivo</button>
            </form>
            <p class="text-xs text-slate-400 mt-2">Imagenes o PDF, hasta 8 MB (radiografias, fotos intraorales, documentos).</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            @if($paciente->archivos->isEmpty())
                <p class="py-8 text-center text-sm text-slate-400">Sin archivos cargados.</p>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach ($paciente->archivos as $archivo)
                        <div class="rounded-xl border border-slate-200 overflow-hidden group">
                            <a href="{{ $archivo->url }}" target="_blank" class="block aspect-video bg-slate-50 flex items-center justify-center overflow-hidden">
                                @if($archivo->es_imagen)
                                    <img src="{{ $archivo->url }}" alt="{{ $archivo->nombre }}" class="object-cover w-full h-full">
                                @else
                                    <svg class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1zM14 3v5h5"/></svg>
                                @endif
                            </a>
                            <div class="p-3">
                                <p class="text-xs font-medium text-slate-700 truncate" title="{{ $archivo->nombre }}">{{ $archivo->nombre }}</p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-slate-100 text-slate-500">{{ $archivo->categoria_nombre }}</span>
                                    <form method="POST" action="{{ route('archivos.destroy', $archivo) }}" onsubmit="return confirm('¿Eliminar archivo?');">
                                        @csrf @method('DELETE')
                                        <button title="Eliminar" class="text-slate-400 hover:text-rose-600"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Panel consentimientos -->
    <div x-show="tab==='consentimientos'" x-cloak class="space-y-5">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-slate-800 mb-4">Generar consentimiento</h2>
            <form method="POST" action="{{ route('consentimientos.store', $paciente) }}" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <select name="tipo" class="flex-1 rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500">
                    @foreach (\App\Models\Consentimiento::TIPOS as $k=>$v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
                <button class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Generar</button>
            </form>
            <p class="text-xs text-slate-400 mt-2">Se crea con el texto estandar del tipo elegido. Luego puedes imprimirlo y marcarlo como firmado.</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="px-6 py-4 border-b border-slate-100"><h2 class="font-bold text-slate-800">Consentimientos del paciente</h2></div>
            <div class="divide-y divide-slate-100">
                @forelse ($paciente->consentimientos as $cons)
                    <div class="px-6 py-4" x-data="{ firmar: false }">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-700">{{ $cons->titulo }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    @if($cons->firmado)
                                        <span class="text-emerald-600 font-semibold">Firmado</span> el {{ optional($cons->fecha_firma)->format('d/m/Y') }} por {{ $cons->firmante }}
                                    @else
                                        <span class="text-amber-600 font-semibold">Pendiente de firma</span>
                                    @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('consentimientos.imprimir', $cons) }}" target="_blank" title="Imprimir" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 9V4h12v5M6 18H4a1 1 0 01-1-1v-5a1 1 0 011-1h16a1 1 0 011 1v5a1 1 0 01-1 1h-2M6 14h12v6H6z"/></svg></a>
                                @unless($cons->firmado)
                                    <button @click="firmar=!firmar" title="Marcar firmado" class="p-2 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></button>
                                @endunless
                                <form method="POST" action="{{ route('consentimientos.destroy', $cons) }}" onsubmit="return confirm('¿Eliminar consentimiento?');">
                                    @csrf @method('DELETE')
                                    <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                </form>
                            </div>
                        </div>
                        @unless($cons->firmado)
                            <form method="POST" action="{{ route('consentimientos.firmar', $cons) }}" x-show="firmar" x-cloak class="mt-3 flex gap-2">
                                @csrf @method('PATCH')
                                <input type="text" name="firmante" required placeholder="Nombre de quien firma" class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm outline-none focus:border-blue-500">
                                <button class="rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2 transition">Confirmar firma</button>
                            </form>
                        @endunless
                    </div>
                @empty
                    <p class="px-6 py-10 text-center text-sm text-slate-400">Sin consentimientos generados.</p>
                @endforelse
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
