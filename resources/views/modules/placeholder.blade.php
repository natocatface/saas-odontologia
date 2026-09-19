@extends('layouts.app')

@section('title', $meta['titulo'])

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a>
    <span>/</span>
    <span class="text-slate-600 font-medium">{{ $meta['titulo'] }}</span>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 text-center">
    <div class="mx-auto h-16 w-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M12 22a10 10 0 110-20 10 10 0 010 20z"/>
        </svg>
    </div>
    <h1 class="mt-5 text-2xl font-extrabold text-slate-800">{{ $meta['titulo'] }}</h1>
    <p class="mt-2 text-slate-500 max-w-xl mx-auto">{{ $meta['descripcion'] }}</p>

    <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-amber-50 text-amber-700 px-4 py-1.5 text-sm font-medium">
        <span class="h-2 w-2 rounded-full bg-amber-400"></span>
        Modulo en construccion
    </div>

    <div class="mt-8 max-w-xl mx-auto text-left bg-slate-50 border border-slate-200 rounded-xl p-5">
        <p class="text-sm font-semibold text-slate-700 mb-1">Que incluira este modulo</p>
        <p class="text-sm text-slate-500">{{ $meta['detalle'] }}</p>
    </div>

    <a href="{{ route('dashboard') }}" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        Volver al Dashboard
    </a>
</div>
@endsection
