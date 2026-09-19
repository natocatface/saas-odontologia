@php $i = $insumo; @endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
        <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre <span class="text-rose-500">*</span></label>
        <input type="text" name="nombre" value="{{ old('nombre', $i->nombre) }}" required placeholder="Guantes de nitrilo, anestesia lidocaina..."
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Categoria</label>
        <select name="categoria" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            @foreach (\App\Models\Insumo::CATEGORIAS as $k=>$v)
                <option value="{{ $k }}" {{ old('categoria', $i->categoria ?? 'general')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Unidad</label>
        <select name="unidad" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            @foreach (\App\Models\Insumo::UNIDADES as $u)
                <option value="{{ $u }}" {{ old('unidad', $i->unidad ?? 'unidad')===$u?'selected':'' }}>{{ ucfirst($u) }}</option>
            @endforeach
        </select>
    </div>
    @unless($i->exists)
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Stock inicial</label>
            <input type="number" step="0.01" min="0" name="stock" value="{{ old('stock', 0) }}"
                   class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>
    @endunless
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Stock minimo (alerta)</label>
        <input type="number" step="0.01" min="0" name="stock_minimo" value="{{ old('stock_minimo', $i->stock_minimo ?? 0) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Costo unitario</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">$</span>
            <input type="number" step="0.01" min="0" name="costo_unitario" value="{{ old('costo_unitario', $i->costo_unitario) }}"
                   class="w-full rounded-xl border border-slate-300 pl-7 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Proveedor</label>
        <input type="text" name="proveedor" value="{{ old('proveedor', $i->proveedor) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
    <div class="md:col-span-2">
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $i->activo ?? true) ? 'checked' : '' }}
                   class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
            Insumo activo
        </label>
    </div>
</div>

<div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
    <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        {{ $i->exists ? 'Guardar cambios' : 'Crear insumo' }}
    </button>
    <a href="{{ $i->exists ? route('insumos.show', $i) : route('insumos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
</div>
