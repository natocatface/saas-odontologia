@extends('layouts.app')

@section('title','Nuevo presupuesto')

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <a href="{{ route('presupuestos.index') }}" class="hover:text-blue-600">Presupuestos</a><span>/</span>
    <span class="text-slate-600 font-medium">Nuevo</span>
</div>

<div class="max-w-4xl">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-8">
        <h1 class="text-xl font-extrabold text-slate-800 mb-6">Nuevo presupuesto</h1>
        <form method="POST" action="{{ route('presupuestos.store') }}">
            @csrf
            @include('presupuestos._form')
        </form>
    </div>
</div>
@endsection
