@php
    $c = $cita;
    $numSillas = (int) \App\Models\Configuracion::valor('num_sillas', '3');
@endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5"
     x-data="{
        fecha: @js(old('fecha', optional($c->fecha)->format('Y-m-d') ?? $c->fecha)),
        doctor: @js((string) old('doctor_id', $c->doctor_id)),
        silla: @js((string) old('silla', $c->silla)),
        hora: @js((string) old('hora', \Illuminate\Support\Str::of($c->hora)->substr(0,5))),
        excepto: @js((string) $c->id),
        horasDoctor: [],
        horasSilla: [],
        init() { this.cargar(); },
        async cargar() {
            if (!this.fecha) { this.horasDoctor = []; this.horasSilla = []; return; }
            try {
                const url = '{{ route('citas.disponibilidad') }}?fecha=' + encodeURIComponent(this.fecha)
                    + '&doctor=' + encodeURIComponent(this.doctor || '')
                    + '&silla=' + encodeURIComponent(this.silla || '')
                    + '&excepto=' + encodeURIComponent(this.excepto || '');
                const r = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const d = await r.json();
                this.horasDoctor = d.doctor || [];
                this.horasSilla = d.silla || [];
            } catch (e) { this.horasDoctor = []; this.horasSilla = []; }
        },
        get choca() {
            return this.hora && (this.horasDoctor.includes(this.hora) || this.horasSilla.includes(this.hora));
        }
     }">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Paciente <span class="text-rose-500">*</span></label>
        <select name="paciente_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            <option value="">Selecciona un paciente...</option>
            @foreach ($pacientes as $pac)
                <option value="{{ $pac->id }}" {{ (string)old('paciente_id', $c->paciente_id)===(string)$pac->id?'selected':'' }}>{{ $pac->nombre }} {{ $pac->apellido }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Doctor</label>
        <select name="doctor_id" x-model="doctor" @change="cargar()" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            <option value="">Sin asignar</option>
            @foreach ($doctores as $doc)
                <option value="{{ $doc->id }}" {{ (string)old('doctor_id', $c->doctor_id)===(string)$doc->id?'selected':'' }}>{{ $doc->name }}{{ $doc->especialidad ? ' · '.$doc->especialidad : '' }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Silla / consultorio</label>
        <select name="silla" x-model="silla" @change="cargar()" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            <option value="">Sin asignar</option>
            @for ($i = 1; $i <= max($numSillas, 1); $i++)
                <option value="{{ $i }}" {{ (string)old('silla', $c->silla)===(string)$i?'selected':'' }}>Silla {{ $i }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
        <select name="estado" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            @foreach ($estados as $k=>$v)
                <option value="{{ $k }}" {{ old('estado', $c->estado ?? 'pendiente')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha <span class="text-rose-500">*</span></label>
        <input type="date" name="fecha" required x-model="fecha" @change="cargar()" value="{{ old('fecha', optional($c->fecha)->format('Y-m-d') ?? $c->fecha) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Hora</label>
        <input type="time" name="hora" x-model="hora" value="{{ old('hora', \Illuminate\Support\Str::of($c->hora)->substr(0,5)) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
               :class="choca ? 'border-rose-400 ring-2 ring-rose-200' : ''">
    </div>
    <div class="md:col-span-2 -mt-2 space-y-1" x-cloak>
        <p x-show="choca" class="text-sm font-semibold text-rose-600">⚠ Esa hora ya esta ocupada para el doctor o la silla seleccionada.</p>
        <p x-show="horasDoctor.length" class="text-xs text-slate-500">Horas ocupadas del doctor ese dia: <span class="font-medium text-slate-700" x-text="horasDoctor.join(', ')"></span></p>
        <p x-show="silla && horasSilla.length" class="text-xs text-slate-500">Horas ocupadas de la silla ese dia: <span class="font-medium text-slate-700" x-text="horasSilla.join(', ')"></span></p>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Motivo</label>
        <input type="text" name="motivo" value="{{ old('motivo', $c->motivo) }}" placeholder="Consulta general, limpieza, control..."
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Notas</label>
        <textarea name="notas" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('notas', $c->notas) }}</textarea>
    </div>
</div>

<div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        {{ $c->exists ? 'Guardar cambios' : 'Agendar cita' }}
    </button>
    <a href="{{ route('citas.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
</div>
