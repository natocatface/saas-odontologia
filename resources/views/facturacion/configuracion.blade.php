@extends('layouts.app')

@section('title','Facturacion Electronica')

@php
    $habilitada = ($config['fe_habilitada'] ?? '0') === '1';
    $driver = $config['fe_driver'] ?? 'ninguno';
    $entorno = $config['fe_entorno'] ?? 'beta';
    $driverLabel = ['ninguno' => 'Ninguno', 'demo' => 'Demo', 'greenter' => 'Greenter'][$driver] ?? $driver;
@endphp

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-400 mb-4">
    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Inicio</a><span>/</span>
    <span class="text-slate-600 font-medium">Facturacion Electronica</span>
</div>

<div class="max-w-4xl">
    <div class="mb-5">
        <h1 class="text-2xl font-extrabold text-slate-800">Facturacion Electronica</h1>
        <p class="text-slate-500">Emision de comprobantes electronicos ante SUNAT (Peru)</p>
    </div>

    @include('facturacion._tabs')

    {{-- Mensajes flash --}}
    @if (session('status'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 mb-5 flex items-start gap-2">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700 mb-5 flex items-start gap-2">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L14.7 3.9a2 2 0 00-3.4 0z"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700 mb-5">
            <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- ===== Banner de estado ===== --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-blue-600 to-blue-800 text-white shadow-lg mb-6">
        <div class="absolute -right-8 -top-10 h-40 w-40 rounded-full bg-white/10"></div>
        <div class="absolute right-16 bottom-0 h-24 w-24 rounded-full bg-white/5"></div>

        <div class="relative p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="h-14 w-14 shrink-0 rounded-2xl bg-white/15 flex items-center justify-center">
                        <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 2h12a1 1 0 011 1v18l-3-2-2 2-2-2-2 2-2-2-3 2V3a1 1 0 011-1zM9 8h6M9 12h6M9 16h3"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold flex items-center gap-2">
                            Facturacion Electronica
                            <span class="text-base">&#127477;&#127466;</span>
                            <span class="text-sm font-semibold text-blue-100">Peru</span>
                        </h2>
                        <p class="text-sm text-blue-100 max-w-xl mt-1">
                            Emision de comprobantes electronicos ante SUNAT &middot; UBL 2.1 &middot; Boletas, facturas y notas de credito.
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center rounded-lg bg-white/15 px-3 py-1.5 text-sm font-bold tracking-wide">SUNAT</span>
                    <p class="text-[11px] text-blue-100 mt-1.5">Comprobantes de Pago Electronicos</p>
                </div>
            </div>

            {{-- Chips de estado --}}
            <div class="flex flex-wrap items-center gap-2 mt-5">
                @if ($habilitada)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/20 text-emerald-50 ring-1 ring-emerald-300/40 px-3 py-1 text-xs font-semibold">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Habilitada
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 text-blue-50 ring-1 ring-white/20 px-3 py-1 text-xs font-semibold">
                        <span class="h-2 w-2 rounded-full bg-slate-300"></span> Deshabilitada
                    </span>
                @endif

                <span class="inline-flex items-center rounded-full bg-white/10 ring-1 ring-white/20 px-3 py-1 text-xs font-semibold">Driver: {{ $driverLabel }}</span>
                <span class="inline-flex items-center rounded-full bg-white/10 ring-1 ring-white/20 px-3 py-1 text-xs font-semibold">Modo: {{ $entorno }}</span>

                @if ($certExiste)
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-400/20 text-emerald-50 ring-1 ring-emerald-300/40 px-3 py-1 text-xs font-semibold">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Certificado encontrado
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-400/20 text-rose-50 ring-1 ring-rose-300/40 px-3 py-1 text-xs font-semibold">
                        <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18"/></svg>
                        Certificado no encontrado
                    </span>
                @endif

                {{-- Probar conexion (formulario independiente) --}}
                <form method="POST" action="{{ route('facturacion.config.probar') }}" class="ml-auto">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-white text-blue-700 hover:bg-blue-50 text-sm font-semibold px-4 py-2 shadow-sm transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                        Probar conexion con SUNAT
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== Formulario de configuracion ===== --}}
    <form method="POST" action="{{ route('facturacion.config.update') }}" class="space-y-5"
          x-data="{ driver: '{{ old('fe_driver', $driver) }}' }">
        @csrf
        @method('PUT')

        {{-- Estado y modo --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 2L3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Estado y modo</h2>
                    <p class="text-xs text-slate-400">Activacion, forma de emision y entorno de SUNAT</p>
                </div>
            </div>

            <div class="space-y-3">
                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                    <input type="checkbox" name="fe_habilitada" value="1" @checked(old('fe_habilitada', $config['fe_habilitada']) === '1')
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Habilitar facturacion electronica</span>
                        <span class="block text-xs text-slate-400">Si esta desactivada, las ventas/pagos no generan comprobante ante SUNAT.</span>
                    </span>
                </label>

                <label class="flex items-start gap-3 rounded-xl border border-slate-200 px-4 py-3 cursor-pointer hover:bg-slate-50 transition">
                    <input type="checkbox" name="fe_auto_emitir" value="1" @checked(old('fe_auto_emitir', $config['fe_auto_emitir']) === '1')
                           class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-700">Emitir automaticamente al registrar el pago</span>
                        <span class="block text-xs text-slate-400">Cada boleta o factura se envia apenas se registra el pago del paciente.</span>
                    </span>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Driver de emision</label>
                    <select name="fe_driver" x-model="driver"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="ninguno" @selected(old('fe_driver', $driver) === 'ninguno')>Ninguno (no emite, deja pendiente)</option>
                        <option value="demo" @selected(old('fe_driver', $driver) === 'demo')>Demo (emision simulada, sin SUNAT)</option>
                        <option value="greenter" @selected(old('fe_driver', $driver) === 'greenter')>Greenter (emision real ante SUNAT)</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-1" x-show="driver === 'greenter'" x-cloak>Requiere <code class="text-blue-600">composer require greenter/lite</code> y un certificado .pem valido.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Entorno SUNAT</label>
                    <select name="fe_entorno"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="beta" @selected(old('fe_entorno', $entorno) === 'beta')>Beta (homologacion / pruebas)</option>
                        <option value="produccion" @selected(old('fe_entorno', $entorno) === 'produccion')>Produccion (emision oficial)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Datos del emisor --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-9 w-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M6 21V8l6-4 6 4v13M9 21v-4h6v4M9 12h.01M15 12h.01"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Datos del emisor</h2>
                    <p class="text-xs text-slate-400">Aparecen en el comprobante electronico</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">RUC <span class="text-rose-500">*</span></label>
                    <input type="text" name="fe_ruc" value="{{ old('fe_ruc', $config['fe_ruc']) }}" maxlength="11" inputmode="numeric" placeholder="20123456789"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Razon social <span class="text-rose-500">*</span></label>
                    <input type="text" name="fe_razon_social" value="{{ old('fe_razon_social', $config['fe_razon_social']) }}" placeholder="CLINICA DENTAL S.A.C."
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Nombre comercial</label>
                    <input type="text" name="fe_nombre_comercial" value="{{ old('fe_nombre_comercial', $config['fe_nombre_comercial']) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Direccion fiscal</label>
                    <input type="text" name="fe_direccion" value="{{ old('fe_direccion', $config['fe_direccion']) }}" placeholder="Av. Principal 123"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ubigeo</label>
                    <input type="text" name="fe_ubigeo" value="{{ old('fe_ubigeo', $config['fe_ubigeo']) }}" maxlength="6" placeholder="150101"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Departamento</label>
                    <input type="text" name="fe_departamento" value="{{ old('fe_departamento', $config['fe_departamento']) }}" placeholder="LIMA"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Provincia</label>
                    <input type="text" name="fe_provincia" value="{{ old('fe_provincia', $config['fe_provincia']) }}" placeholder="LIMA"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Distrito</label>
                    <input type="text" name="fe_distrito" value="{{ old('fe_distrito', $config['fe_distrito']) }}" placeholder="LIMA"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>
        </div>

        {{-- Credenciales SUNAT --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-9 w-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a4 4 0 11-4 4l-6 6v3h3l1-1v-2h2l1-1v-2l1-1a4 4 0 013-5z"/></svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-800">Credenciales SUNAT</h2>
                    <p class="text-xs text-slate-400">Clave SOL y certificado digital</p>
                </div>
            </div>

            <div class="rounded-xl bg-blue-50 border border-blue-100 px-4 py-3 text-sm text-blue-700 mb-5 flex items-start gap-2">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-4m0-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z"/></svg>
                <span>En <b>beta</b> puedes usar RUC <b>20000000001</b> con usuario y clave <b>MODDATOS</b>.</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Usuario Clave SOL</label>
                    <input type="text" name="fe_usuario_sol" value="{{ old('fe_usuario_sol', $config['fe_usuario_sol']) }}" autocomplete="off" placeholder="MODDATOS"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Clave SOL</label>
                    <input type="password" name="fe_clave_sol" value="" autocomplete="new-password" placeholder="{{ ($config['fe_clave_sol'] ?? '') !== '' ? '•••••••• (sin cambios)' : '' }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <p class="text-xs text-slate-400 mt-1">Deja en blanco para conservar la clave actual.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Ruta del certificado (.pem)</label>
                    <input type="text" name="fe_cert_path" value="{{ old('fe_cert_path', $config['fe_cert_path']) }}" placeholder="C:\SAAS\saas_odontologia\storage\facturacion\pe\certificate.pem"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @if (! $certExiste && ($config['fe_cert_path'] ?? '') !== '')
                        <p class="text-xs text-rose-500 mt-1 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.9 1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L14.7 3.9a2 2 0 00-3.4 0z"/></svg>
                            No se encontro el certificado en la ruta indicada.
                        </p>
                    @endif
                    <p class="text-xs text-slate-400 mt-1">Certificado digital en formato PEM (clave privada + certificado). Guardalo fuera de la carpeta publica.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Clave del certificado</label>
                    <input type="password" name="fe_cert_pass" value="" autocomplete="new-password" placeholder="{{ ($config['fe_cert_pass'] ?? '') !== '' ? '•••••••• (sin cambios)' : 'Opcional' }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                </div>
            </div>
        </div>

        {{-- Barra de acciones --}}
        <div class="sticky bottom-0 bg-white/90 backdrop-blur rounded-2xl border border-slate-200 shadow-sm p-4 flex items-center justify-between gap-3">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-300 text-slate-600 text-sm font-semibold px-5 py-2.5 hover:bg-slate-50 transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Volver
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 shadow-sm transition">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                Guardar configuracion
            </button>
        </div>
    </form>
</div>
@endsection
