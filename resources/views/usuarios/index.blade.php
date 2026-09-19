@extends('layouts.app')

@section('title','Usuarios')

@section('content')
@php
    $rolBadge = ['admin'=>'bg-violet-100 text-violet-700','doctor'=>'bg-blue-100 text-blue-700','recepcion'=>'bg-slate-100 text-slate-600'];
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Usuarios</h1>
        <p class="text-slate-500">Administracion de usuarios, roles y accesos</p>
    </div>
    <a href="{{ route('usuarios.create') }}"
       class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2.5 shadow-lg shadow-blue-600/20 transition">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
        Nuevo usuario
    </a>
</div>

<!-- Stats -->
<div class="mt-5 grid grid-cols-2 lg:grid-cols-4 gap-4">
    @foreach ([['Total',$stats['total'],'bg-slate-100 text-slate-600'],['Administradores',$stats['admin'],'bg-violet-100 text-violet-600'],['Doctores',$stats['doctor'],'bg-blue-100 text-blue-600'],['Recepcion',$stats['recepcion'],'bg-emerald-100 text-emerald-600']] as [$label,$val,$cls])
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <div class="flex items-center gap-2">
                <span class="h-8 w-8 rounded-lg {{ $cls }} flex items-center justify-center text-sm font-bold">{{ $val }}</span>
                <p class="text-xs text-slate-500">{{ $label }}</p>
            </div>
        </div>
    @endforeach
</div>

<!-- Filtros -->
<form method="GET" class="mt-5 bg-white rounded-2xl border border-slate-200 p-4 shadow-sm flex flex-col sm:flex-row gap-3">
    <div class="relative flex-1">
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3M11 18a7 7 0 100-14 7 7 0 000 14z"/></svg></span>
        <input type="text" name="buscar" value="{{ $buscar }}" placeholder="Buscar por nombre o correo..."
               class="w-full rounded-xl border border-slate-300 pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
    </div>
    <select name="rol" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
        <option value="">Todos los roles</option>
        @foreach (\App\Models\User::ROLES as $k=>$v)
            <option value="{{ $k }}" {{ $rol===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
    </select>
    <button class="rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 transition">Filtrar</button>
    @if($buscar||$rol)
        <a href="{{ route('usuarios.index') }}" class="rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition text-center">Limpiar</a>
    @endif
</form>

<!-- Tabla -->
<div class="mt-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-500 border-b border-slate-100 bg-slate-50/60">
                    <th class="px-5 py-3 font-semibold">Usuario</th>
                    <th class="px-5 py-3 font-semibold">Rol</th>
                    <th class="px-5 py-3 font-semibold">Contacto</th>
                    <th class="px-5 py-3 font-semibold">Estado</th>
                    <th class="px-5 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($usuarios as $u)
                    <tr class="hover:bg-slate-50/70">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white text-xs font-semibold flex items-center justify-center shrink-0">{{ $u->iniciales }}</div>
                                <div>
                                    <p class="font-medium text-slate-700">{{ $u->name }}
                                        @if($u->id===auth()->id())<span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 ml-1">Tu</span>@endif
                                    </p>
                                    <p class="text-xs text-slate-400">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3"><span class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $rolBadge[$u->rol] ?? 'bg-slate-100 text-slate-600' }}">{{ $u->rol_nombre }}</span></td>
                        <td class="px-5 py-3 text-slate-600">
                            {{ $u->telefono ?: '—' }}
                            @if($u->especialidad)<p class="text-xs text-slate-400">{{ $u->especialidad }}</p>@endif
                        </td>
                        <td class="px-5 py-3">
                            @if($u->id===auth()->id())
                                <span class="text-[11px] font-medium px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">Activo</span>
                            @else
                                <form method="POST" action="{{ route('usuarios.estado', $u) }}">
                                    @csrf @method('PATCH')
                                    <button class="text-[11px] font-medium px-2 py-0.5 rounded-full {{ $u->activo ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">{{ $u->activo ? 'Activo' : 'Inactivo' }}</button>
                                </form>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('usuarios.edit', $u) }}" title="Editar" class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.5 5.5l3 3M4 20l4-1 9.5-9.5a2.1 2.1 0 00-3-3L5 16l-1 4z"/></svg></a>
                                @if($u->id!==auth()->id())
                                    <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar al usuario {{ $u->name }}?');">
                                        @csrf @method('DELETE')
                                        <button title="Eliminar" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 011-1h4a1 1 0 011 1v2m-7 0v12a1 1 0 001 1h6a1 1 0 001-1V7"/></svg></button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-slate-400">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($usuarios->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
