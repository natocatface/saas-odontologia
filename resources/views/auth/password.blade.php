@extends('layouts.app')

@section('title','Cambiar clave')

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a>
    <span>/</span>
    <span class="text-slate-600 font-medium">Cambiar clave</span>
</div>

<div class="max-w-lg">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <h1 class="text-xl font-extrabold text-slate-800">Cambiar contrasena</h1>
        <p class="mt-1 text-sm text-slate-500">Actualiza tu contrasena de acceso al sistema.</p>

        @if ($errors->any())
            <div class="mt-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Contrasena actual</label>
                <input type="password" name="current_password" required
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nueva contrasena</label>
                <input type="password" name="password" required
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
                <p class="mt-1 text-xs text-slate-400">Minimo 8 caracteres.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmar nueva contrasena</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition">
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">Guardar cambios</button>
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
