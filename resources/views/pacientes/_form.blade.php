@php $p = $paciente; @endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre <span class="text-rose-500">*</span></label>
        <input type="text" name="nombre" value="{{ old('nombre', $p->nombre) }}" required
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Apellido <span class="text-rose-500">*</span></label>
        <input type="text" name="apellido" value="{{ old('apellido', $p->apellido) }}" required
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Documento</label>
        <input type="text" name="documento" value="{{ old('documento', $p->documento) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Telefono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $p->telefono) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Correo electronico</label>
        <input type="email" name="email" value="{{ old('email', $p->email) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', optional($p->fecha_nacimiento)->format('Y-m-d')) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Genero</label>
        <select name="genero" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            @php $g = old('genero', $p->genero); @endphp
            <option value="">Sin especificar</option>
            <option value="M" {{ $g==='M'?'selected':'' }}>Masculino</option>
            <option value="F" {{ $g==='F'?'selected':'' }}>Femenino</option>
            <option value="O" {{ $g==='O'?'selected':'' }}>Otro</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipo de sangre</label>
        <select name="tipo_sangre" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            @php $ts = old('tipo_sangre', $p->tipo_sangre); @endphp
            <option value="">Sin especificar</option>
            @foreach (\App\Models\Paciente::TIPOS_SANGRE as $t)
                <option value="{{ $t }}" {{ $ts===$t?'selected':'' }}>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Direccion</label>
        <input type="text" name="direccion" value="{{ old('direccion', $p->direccion) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>

    <!-- Antecedentes medicos -->
    <div class="md:col-span-2 border-t border-slate-100 pt-5">
        <p class="text-sm font-bold text-slate-700">Antecedentes medicos</p>
        <p class="text-xs text-slate-400">Marca las condiciones que apliquen al paciente.</p>
    </div>
    <div class="md:col-span-2">
        @php $enfSel = old('enfermedades', $p->enfermedades ?? []); $enfSel = is_array($enfSel) ? $enfSel : []; @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            @foreach (\App\Models\Paciente::ENFERMEDADES as $k=>$v)
                <label class="inline-flex items-center gap-2 text-sm text-slate-600 rounded-lg border border-slate-200 px-3 py-2 hover:bg-slate-50 cursor-pointer">
                    <input type="checkbox" name="enfermedades[]" value="{{ $k }}" {{ in_array($k, $enfSel) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                    {{ $v }}
                </label>
            @endforeach
        </div>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Alergias</label>
        <textarea name="alergias" rows="2" placeholder="Penicilina, latex, anestesia local..."
                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('alergias', $p->alergias) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Medicacion actual</label>
        <textarea name="medicacion" rows="2" placeholder="Medicamentos que toma actualmente y dosis..."
                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('medicacion', $p->medicacion) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Habitos</label>
        @php $habSel = old('habitos', $p->habitos ?? []); $habSel = is_array($habSel) ? $habSel : []; @endphp
        <div class="flex flex-wrap gap-2">
            @foreach (\App\Models\Paciente::HABITOS as $k=>$v)
                <label class="inline-flex items-center gap-2 text-sm text-slate-600 rounded-lg border border-slate-200 px-3 py-2 hover:bg-slate-50 cursor-pointer">
                    <input type="checkbox" name="habitos[]" value="{{ $k }}" {{ in_array($k, $habSel) ? 'checked' : '' }}
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                    {{ $v }}
                </label>
            @endforeach
        </div>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Notas de antecedentes</label>
        <textarea name="antecedentes_notas" rows="2" placeholder="Cirugias previas, antecedentes familiares, otros..."
                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('antecedentes_notas', $p->antecedentes_notas) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Observaciones</label>
        <textarea name="observaciones" rows="3"
                  class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('observaciones', $p->observaciones) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $p->activo ?? true) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
            Paciente activo
        </label>
    </div>

    <!-- Acceso al portal del paciente -->
    <div class="md:col-span-2 border-t border-slate-100 pt-5">
        <p class="text-sm font-bold text-slate-700">Acceso al portal del paciente</p>
        <p class="text-xs text-slate-400">El paciente inicia sesion con su correo y esta clave en el portal.</p>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="portal_activo" value="0">
            <input type="checkbox" name="portal_activo" value="1" {{ old('portal_activo', $p->portal_activo ?? false) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
            Habilitar acceso al portal
        </label>
        @if($p->exists && $p->password)
            <p class="text-xs text-emerald-600 mt-1">Este paciente ya tiene una clave configurada. Escribe una nueva abajo solo si deseas cambiarla.</p>
        @endif
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $p->exists && $p->password ? 'Nueva clave del portal' : 'Clave del portal' }}</label>
        <input type="text" name="portal_password" value="{{ old('portal_password') }}" placeholder="Minimo 6 caracteres (requiere correo del paciente)"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
</div>

<div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        {{ $p->exists ? 'Guardar cambios' : 'Registrar paciente' }}
    </button>
    <a href="{{ $p->exists ? route('pacientes.show', $p) : route('pacientes.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
</div>
