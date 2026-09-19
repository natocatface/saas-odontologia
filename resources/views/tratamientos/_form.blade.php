@php $t = $tratamiento; @endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
        <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre del tratamiento <span class="text-rose-500">*</span></label>
        <input type="text" name="nombre" value="{{ old('nombre', $t->nombre) }}" required
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Categoria</label>
        <select name="categoria" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            <option value="">Sin categoria</option>
            @foreach ($categorias as $cat)
                <option value="{{ $cat }}" {{ old('categoria', $t->categoria)===$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Precio <span class="text-rose-500">*</span></label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">$</span>
                <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $t->precio) }}" required
                       class="w-full rounded-xl border border-slate-300 pl-7 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Duracion (min)</label>
            <input type="number" min="0" max="600" name="duracion_min" value="{{ old('duracion_min', $t->duracion_min) }}"
                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
        </div>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Descripcion</label>
        <textarea name="descripcion" rows="3" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">{{ old('descripcion', $t->descripcion) }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $t->activo ?? true) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
            Tratamiento activo
        </label>
    </div>
</div>

<div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        {{ $t->exists ? 'Guardar cambios' : 'Crear tratamiento' }}
    </button>
    <a href="{{ route('tratamientos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
</div>
