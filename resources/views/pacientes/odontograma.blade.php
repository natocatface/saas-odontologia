@extends('layouts.app')

@section('title','Odontograma')

@section('content')
@php
    $q1 = [18,17,16,15,14,13,12,11];
    $q2 = [21,22,23,24,25,26,27,28];
    $q4 = [48,47,46,45,44,43,42,41];
    $q3 = [31,32,33,34,35,36,37,38];

    // Estados que aplican a TODO el diente.
    $generales = [
        'sano'       => ['Sano', '#ffffff'],
        'corona'     => ['Corona', '#f59e0b'],
        'endodoncia' => ['Endodoncia', '#8b5cf6'],
        'implante'   => ['Implante', '#14b8a6'],
        'fracturado' => ['Fracturado', '#f97316'],
        'extraccion' => ['Para extraccion', '#fca5a5'],
        'ausente'    => ['Ausente', '#e2e8f0'],
    ];
    // Estados que aplican por CARA.
    $caras = [
        'sano'     => ['Sano', '#ffffff'],
        'caries'   => ['Caries', '#ef4444'],
        'obturado' => ['Obturado', '#3b82f6'],
        'sellante' => ['Sellante', '#22c55e'],
    ];

    $generalesJs = collect($generales)->map(fn ($e) => ['label' => $e[0], 'color' => $e[1]]);
    $carasJs = collect($caras)->map(fn ($e) => ['label' => $e[0], 'color' => $e[1]]);
    $inicial = $paciente->odontograma ?? [];
@endphp

<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('pacientes.index') }}" class="hover:text-blue-600">Pacientes</a><span>/</span>
    <a href="{{ route('pacientes.show', $paciente) }}" class="hover:text-blue-600">{{ $paciente->nombre_completo }}</a><span>/</span>
    <span class="text-slate-600 font-medium">Odontograma</span>
</div>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div class="flex items-center gap-3">
        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-sm font-bold flex items-center justify-center">{{ $paciente->iniciales }}</div>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Odontograma</h1>
            <p class="text-slate-500 text-sm">{{ $paciente->nombre_completo }}</p>
        </div>
    </div>
    <a href="{{ route('pacientes.show', $paciente) }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-4 py-2.5 hover:bg-slate-50 transition self-start">Volver a la ficha</a>
</div>

<form method="POST" action="{{ route('pacientes.odontograma.update', $paciente) }}"
      x-data="odontograma(@js($generalesJs), @js($carasJs), @js($inicial))"
      @submit="$refs.payload.value = JSON.stringify(datos)">
    @csrf
    @method('PUT')
    <input type="hidden" name="odontograma" x-ref="payload">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Mapa dental -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <p class="text-xs text-slate-400 mb-5 text-center">Haz clic en un diente para editar su estado y caras · Numeracion FDI</p>

            <div class="overflow-x-auto pb-2">
                <div class="min-w-[680px] mx-auto rounded-2xl bg-gradient-to-b from-slate-50 to-white border border-slate-100 px-4 py-5">
                    <div class="flex justify-between px-2 mb-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300">
                        <span>Superior derecha</span><span>Superior izquierda</span>
                    </div>
                    <div class="flex items-end justify-center gap-5">
                        <div class="flex gap-0.5">@foreach ($q1 as $n)<x-diente :n="$n" />@endforeach</div>
                        <div class="w-px self-stretch bg-slate-300/70"></div>
                        <div class="flex gap-0.5">@foreach ($q2 as $n)<x-diente :n="$n" />@endforeach</div>
                    </div>

                    <div class="my-4 border-t-2 border-dashed border-slate-200"></div>

                    <div class="flex items-start justify-center gap-5">
                        <div class="flex gap-0.5">@foreach ($q4 as $n)<x-diente :n="$n" :bottom="true" />@endforeach</div>
                        <div class="w-px self-stretch bg-slate-300/70"></div>
                        <div class="flex gap-0.5">@foreach ($q3 as $n)<x-diente :n="$n" :bottom="true" />@endforeach</div>
                    </div>
                    <div class="flex justify-between px-2 mt-1 text-[10px] font-semibold uppercase tracking-wider text-slate-300">
                        <span>Inferior derecha</span><span>Inferior izquierda</span>
                    </div>
                </div>
            </div>

            <!-- Leyenda -->
            <div class="mt-6 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Estado del diente</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1.5">
                        @foreach ($generales as $e)
                            <div class="flex items-center gap-1.5 text-xs text-slate-600"><span class="h-3.5 w-3.5 rounded border border-slate-300" style="background:{{ $e[1] }}"></span>{{ $e[0] }}</div>
                        @endforeach
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Caras</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-1.5">
                        @foreach ($caras as $e)
                            <div class="flex items-center gap-1.5 text-xs text-slate-600"><span class="h-3.5 w-3.5 rounded border border-slate-300" style="background:{{ $e[1] }}"></span>{{ $e[0] }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel de edicion -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <template x-if="selected === null">
                <div class="text-center py-10">
                    <div class="mx-auto h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18h6M10 22h4M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.1h6c0-.8.4-1.6 1-2.1A7 7 0 0012 2z"/></svg>
                    </div>
                    <p class="mt-3 text-sm text-slate-400">Selecciona un diente para editar.</p>
                </div>
            </template>

            <template x-if="selected !== null">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-400">Diente seleccionado</p>
                            <p class="text-2xl font-extrabold text-slate-800" x-text="'N° ' + selected"></p>
                        </div>
                        <span class="h-10 w-8 rounded-md border-2 border-slate-300" :style="`background:${color(selected)}`"></span>
                    </div>

                    <!-- Estado general -->
                    <p class="text-sm font-medium text-slate-700 mb-2">Estado del diente</p>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="(info, key) in generales" :key="'g'+key">
                            <button type="button" @click="setGeneral(key)"
                                    class="flex items-center gap-2 rounded-lg border px-2.5 py-2 text-xs font-medium transition"
                                    :class="datos[selected].general === key ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                <span class="h-3.5 w-3.5 rounded border border-slate-300 shrink-0" :style="`background:${info.color}`"></span>
                                <span x-text="info.label"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Caras -->
                    <div class="mt-5 flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-700">Caras del diente</p>
                        <span class="text-[11px] text-slate-400">Pinta con:</span>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <template x-for="(info, key) in caras" :key="'b'+key">
                            <button type="button" @click="pincel = key"
                                    class="flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-medium transition"
                                    :class="pincel === key ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600 hover:bg-slate-50'">
                                <span class="h-3 w-3 rounded-full border border-slate-300" :style="`background:${info.color}`"></span>
                                <span x-text="info.label"></span>
                            </button>
                        </template>
                    </div>

                    <!-- Diagrama grande de caras -->
                    <div class="mt-4 flex justify-center">
                        <div class="relative">
                            <svg viewBox="0 0 120 120" class="h-40 w-40 cursor-pointer">
                                <polygon points="0,0 120,0 84,36 36,36" :fill="colorCara(selected,'v')" @click="pintar('v')" stroke="#cbd5e1" stroke-width="1.5" class="hover:opacity-80"/>
                                <polygon points="120,0 120,120 84,84 84,36" :fill="colorCara(selected,'d')" @click="pintar('d')" stroke="#cbd5e1" stroke-width="1.5" class="hover:opacity-80"/>
                                <polygon points="0,120 120,120 84,84 36,84" :fill="colorCara(selected,'l')" @click="pintar('l')" stroke="#cbd5e1" stroke-width="1.5" class="hover:opacity-80"/>
                                <polygon points="0,0 0,120 36,84 36,36" :fill="colorCara(selected,'m')" @click="pintar('m')" stroke="#cbd5e1" stroke-width="1.5" class="hover:opacity-80"/>
                                <rect x="36" y="36" width="48" height="48" :fill="colorCara(selected,'o')" @click="pintar('o')" stroke="#cbd5e1" stroke-width="1.5" class="hover:opacity-80"/>
                            </svg>
                            <span class="absolute top-1 left-1/2 -translate-x-1/2 text-[10px] font-semibold text-slate-400">V</span>
                            <span class="absolute bottom-1 left-1/2 -translate-x-1/2 text-[10px] font-semibold text-slate-400">L/P</span>
                            <span class="absolute left-1 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400">M</span>
                            <span class="absolute right-1 top-1/2 -translate-y-1/2 text-[10px] font-semibold text-slate-400">D</span>
                            <span class="absolute inset-0 flex items-center justify-center text-[10px] font-semibold text-slate-400 pointer-events-none">O</span>
                        </div>
                    </div>

                    <!-- Nota -->
                    <p class="text-sm font-medium text-slate-700 mt-4 mb-1.5">Nota</p>
                    <textarea x-model="datos[selected].nota" rows="2" placeholder="Observacion del diente..."
                              class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"></textarea>

                    <button type="button" @click="reset()" class="mt-3 text-xs text-rose-600 hover:underline">Restablecer este diente</button>
                </div>
            </template>

            <div class="mt-6 pt-5 border-t border-slate-100">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Resumen</p>
                <p class="text-sm text-slate-600"><span class="font-bold text-slate-800" x-text="afectados()"></span> dientes con hallazgos de 32.</p>
            </div>

            <button type="submit" class="mt-5 w-full rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 transition flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Guardar odontograma
            </button>
        </div>
    </div>
</form>

<script>
    function odontograma(generales, caras, inicial) {
        const teeth = [18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28,48,47,46,45,44,43,42,41,31,32,33,34,35,36,37,38];
        const carasVacias = () => ({ v: 'sano', l: 'sano', m: 'sano', d: 'sano', o: 'sano' });
        const datos = {};
        teeth.forEach(n => {
            const prev = inicial && inicial[n] ? inicial[n] : null;
            datos[n] = {
                general: prev && prev.general ? prev.general : (prev && prev.estado ? prev.estado : 'sano'),
                caras: prev && prev.caras ? Object.assign(carasVacias(), prev.caras) : carasVacias(),
                nota: prev && prev.nota ? prev.nota : '',
            };
        });
        return {
            generales, caras, datos,
            selected: null,
            pincel: 'caries',
            pick(n) { this.selected = n; },
            color(n) {
                const g = this.datos[n] ? this.datos[n].general : 'sano';
                return (this.generales[g] && this.generales[g].color) || '#ffffff';
            },
            colorCara(n, cara) {
                if (!this.datos[n]) return '#ffffff';
                const e = this.datos[n].caras[cara] || 'sano';
                return (this.caras[e] && this.caras[e].color) || '#ffffff';
            },
            caraHallazgo(n) {
                const d = this.datos[n];
                return d ? Object.values(d.caras).some(c => c !== 'sano') : false;
            },
            setGeneral(key) { if (this.selected !== null) this.datos[this.selected].general = key; },
            pintar(cara) { if (this.selected !== null) this.datos[this.selected].caras[cara] = this.pincel; },
            reset() {
                if (this.selected === null) return;
                this.datos[this.selected].general = 'sano';
                this.datos[this.selected].caras = { v: 'sano', l: 'sano', m: 'sano', d: 'sano', o: 'sano' };
                this.datos[this.selected].nota = '';
            },
            afectados() {
                return Object.values(this.datos).filter(d =>
                    d.general !== 'sano' || Object.values(d.caras).some(c => c !== 'sano')
                ).length;
            },
        };
    }
</script>
@endsection
