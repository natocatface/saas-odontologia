@php $u = $usuario; @endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div x-data="{ rol: '{{ old('rol', $u->rol ?? 'recepcion') }}' }" class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre completo <span class="text-rose-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $u->name) }}" required
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Correo electronico <span class="text-rose-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $u->email) }}" required
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Rol <span class="text-rose-500">*</span></label>
        <select name="rol" x-model="rol" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            @foreach (\App\Models\User::ROLES as $k=>$v)
                <option value="{{ $k }}">{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Telefono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $u->telefono) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div x-show="rol === 'doctor'" x-cloak>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Especialidad</label>
        <input type="text" name="especialidad" value="{{ old('especialidad', $u->especialidad) }}" placeholder="Ortodoncia, Endodoncia, Cirugia Oral..."
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div x-show="rol === 'doctor'" x-cloak>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Comision (%)</label>
        <input type="number" name="comision" step="0.01" min="0" max="100" value="{{ old('comision', $u->comision ?? 0) }}" placeholder="Ej. 30"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
        <p class="text-xs text-slate-400 mt-1">Porcentaje sobre lo cobrado de sus presupuestos.</p>
    </div>

    <div class="md:col-span-2 border-t border-slate-100 pt-5">
        <p class="text-sm font-semibold text-slate-700">{{ $u->exists ? 'Cambiar contrasena (opcional)' : 'Contrasena' }}</p>
        <p class="text-xs text-slate-400 mb-3">{{ $u->exists ? 'Deja en blanco para mantener la contrasena actual.' : 'Minimo 8 caracteres.' }}</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Contrasena @unless($u->exists)<span class="text-rose-500">*</span>@endunless</label>
        <input type="password" name="password" {{ $u->exists ? '' : 'required' }}
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar contrasena</label>
        <input type="password" name="password_confirmation"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>

    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $u->activo ?? true) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
            Usuario activo (puede iniciar sesion)
        </label>
    </div>
</div>

<div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        {{ $u->exists ? 'Guardar cambios' : 'Crear usuario' }}
    </button>
    <a href="{{ route('usuarios.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
</div>
