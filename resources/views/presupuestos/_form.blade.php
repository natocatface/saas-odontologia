@php $pr = $presupuesto; @endphp
@php
    if (old('items')) {
        $initItems = collect(old('items'))->map(fn ($i) => [
            'tratamiento_id' => $i['tratamiento_id'] ?? '',
            'descripcion' => $i['descripcion'] ?? '',
            'cantidad' => (int) ($i['cantidad'] ?? 1),
            'precio_unitario' => (float) ($i['precio_unitario'] ?? 0),
        ])->values();
    } elseif ($pr->exists && $pr->items->count()) {
        $initItems = $pr->items->map(fn ($i) => [
            'tratamiento_id' => $i->tratamiento_id ?? '',
            'descripcion' => $i->descripcion,
            'cantidad' => (int) $i->cantidad,
            'precio_unitario' => (float) $i->precio_unitario,
        ])->values();
    } else {
        $initItems = [['tratamiento_id' => '', 'descripcion' => '', 'cantidad' => 1, 'precio_unitario' => 0]];
    }
@endphp

@if ($errors->any())
    <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
        <p class="font-semibold mb-1">Revisa los siguientes campos:</p>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<!-- Datos generales -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Paciente <span class="text-rose-500">*</span></label>
        <select name="paciente_id" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <option value="">Selecciona...</option>
            @foreach ($pacientes as $pac)
                <option value="{{ $pac->id }}" {{ (string)old('paciente_id', $pr->paciente_id)===(string)$pac->id?'selected':'' }}>{{ $pac->nombre }} {{ $pac->apellido }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Doctor</label>
        <select name="doctor_id" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <option value="">Sin asignar</option>
            @foreach ($doctores as $doc)
                <option value="{{ $doc->id }}" {{ (string)old('doctor_id', $pr->doctor_id)===(string)$doc->id?'selected':'' }}>{{ $doc->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha <span class="text-rose-500">*</span></label>
        <input type="date" name="fecha" required value="{{ old('fecha', optional($pr->fecha)->format('Y-m-d') ?? $pr->fecha) }}"
               class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Estado</label>
        <select name="estado" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            @foreach ($estados as $k=>$v)
                <option value="{{ $k }}" {{ old('estado', $pr->estado ?? 'borrador')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Lineas de detalle -->
<div x-data="presupuestoForm(@js($initItems), @js($tratamientos), {{ (float) old('descuento', $pr->descuento ?? 0) }})" class="mt-6">
    <div class="flex items-center justify-between mb-2">
        <h3 class="font-bold text-slate-800">Tratamientos</h3>
        <button type="button" @click="addRow()" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-sm font-semibold px-3 py-1.5 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            Agregar linea
        </button>
    </div>

    <div class="border border-slate-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-left">
                        <th class="px-3 py-2 font-semibold w-1/3">Tratamiento / descripcion</th>
                        <th class="px-3 py-2 font-semibold w-20">Cant.</th>
                        <th class="px-3 py-2 font-semibold w-32">P. unitario</th>
                        <th class="px-3 py-2 font-semibold w-32 text-right">Subtotal</th>
                        <th class="px-3 py-2 w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in items" :key="index">
                        <tr class="border-t border-slate-100 align-top">
                            <td class="px-3 py-2">
                                <select :name="`items[${index}][tratamiento_id]`" x-model="item.tratamiento_id" @change="applyTratamiento(item)"
                                        class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm mb-1 outline-none focus:border-blue-500">
                                    <option value="">— Personalizado —</option>
                                    @foreach ($tratamientos as $t)
                                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                </select>
                                <input type="text" :name="`items[${index}][descripcion]`" x-model="item.descripcion" placeholder="Descripcion" required
                                       class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm outline-none focus:border-blue-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="1" :name="`items[${index}][cantidad]`" x-model.number="item.cantidad"
                                       class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm outline-none focus:border-blue-500">
                            </td>
                            <td class="px-3 py-2">
                                <input type="number" min="0" step="0.01" :name="`items[${index}][precio_unitario]`" x-model.number="item.precio_unitario"
                                       class="w-full rounded-lg border border-slate-300 px-2 py-1.5 text-sm outline-none focus:border-blue-500">
                            </td>
                            <td class="px-3 py-2 text-right font-medium text-slate-700" x-text="formatMoney(rowSubtotal(item))"></td>
                            <td class="px-3 py-2 text-center">
                                <button type="button" @click="removeRow(index)" class="text-slate-400 hover:text-rose-600" x-show="items.length > 1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Totales -->
    <div class="mt-4 flex flex-col sm:flex-row sm:justify-end gap-4">
        <div class="sm:w-72 space-y-2">
            <div class="flex justify-between text-sm text-slate-600"><span>Subtotal</span><span x-text="formatMoney(subtotal())" class="font-medium"></span></div>
            <div class="flex justify-between items-center text-sm text-slate-600">
                <span>Descuento</span>
                <div class="relative w-32">
                    <span class="absolute inset-y-0 left-0 pl-2 flex items-center text-slate-400 text-xs">$</span>
                    <input type="number" min="0" step="0.01" name="descuento" x-model.number="descuento"
                           class="w-full rounded-lg border border-slate-300 pl-6 pr-2 py-1.5 text-sm text-right outline-none focus:border-blue-500">
                </div>
            </div>
            <div class="flex justify-between text-base font-extrabold text-slate-800 border-t border-slate-200 pt-2"><span>Total</span><span x-text="formatMoney(total())"></span></div>
        </div>
    </div>

    <div class="mt-5">
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Notas</label>
        <textarea name="notas" rows="2" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('notas', $pr->notas) }}</textarea>
    </div>

    <div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
        <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
            {{ $pr->exists ? 'Guardar cambios' : 'Crear presupuesto' }}
        </button>
        <a href="{{ $pr->exists ? route('presupuestos.show', $pr) : route('presupuestos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
    </div>
</div>

<script>
    function presupuestoForm(items, tratamientos, descuento) {
        return {
            items: items.length ? items : [{ tratamiento_id: '', descripcion: '', cantidad: 1, precio_unitario: 0 }],
            tratamientos: tratamientos,
            descuento: Number(descuento) || 0,
            addRow() {
                this.items.push({ tratamiento_id: '', descripcion: '', cantidad: 1, precio_unitario: 0 });
            },
            removeRow(i) {
                this.items.splice(i, 1);
                if (!this.items.length) this.addRow();
            },
            applyTratamiento(item) {
                const t = this.tratamientos.find(x => String(x.id) === String(item.tratamiento_id));
                if (t) {
                    item.descripcion = t.nombre;
                    item.precio_unitario = Number(t.precio);
                }
            },
            rowSubtotal(item) {
                return (Number(item.cantidad) || 0) * (Number(item.precio_unitario) || 0);
            },
            subtotal() {
                return this.items.reduce((s, it) => s + this.rowSubtotal(it), 0);
            },
            total() {
                return Math.max(this.subtotal() - (Number(this.descuento) || 0), 0);
            },
            formatMoney(n) {
                return '$' + (Number(n) || 0).toLocaleString('es', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }
        };
    }
</script>
