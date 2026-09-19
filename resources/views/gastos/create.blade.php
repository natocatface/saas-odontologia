@extends('layouts.app')

@section('title','Registrar gasto')

@section('content')
@php $g = $gasto; @endphp
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('gastos.index') }}" class="hover:text-blue-600">Gastos</a><span>/</span>
    <span class="text-slate-600 font-medium">Nuevo</span>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <h1 class="text-xl font-extrabold text-slate-800 mb-6">Registrar gasto</h1>

        @if ($errors->any())
            <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
                <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('gastos.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Descripcion <span class="text-rose-500">*</span></label>
                    <input type="text" name="descripcion" value="{{ old('descripcion', $g->descripcion) }}" required placeholder="Compra de guantes, pago de luz..."
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Categoria <span class="text-rose-500">*</span></label>
                    <select name="categoria" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        @foreach (\App\Models\Gasto::CATEGORIAS as $k=>$v)
                            <option value="{{ $k }}" {{ old('categoria', $g->categoria ?? 'insumos')===$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Monto <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">$</span>
                        <input type="number" step="0.01" min="0.01" name="monto" value="{{ old('monto', $g->monto) }}" required
                               class="w-full rounded-xl border border-slate-300 pl-7 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Fecha <span class="text-rose-500">*</span></label>
                    <input type="date" name="fecha" required value="{{ old('fecha', optional($g->fecha)->format('Y-m-d') ?? $g->fecha) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Metodo de pago</label>
                    <select name="metodo" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        @foreach (\App\Models\Gasto::METODOS as $k=>$v)
                            <option value="{{ $k }}" {{ old('metodo', $g->metodo ?? 'efectivo')===$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6 mt-6 border-t border-slate-100">
                <button type="submit" class="rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 transition">Registrar gasto</button>
                <a href="{{ route('gastos.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
