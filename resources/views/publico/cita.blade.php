<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tu cita · {{ $config['nombre_clinica'] ?? 'OdontoCRM' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
@php
    $estadoBadge = [
        'pendiente'  => 'bg-amber-100 text-amber-700',
        'confirmada' => 'bg-emerald-100 text-emerald-700',
        'completada' => 'bg-blue-100 text-blue-700',
        'cancelada'  => 'bg-rose-100 text-rose-700',
    ];
@endphp
<body class="h-full bg-slate-100">
<div class="min-h-full flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg">
                <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <p class="mt-3 text-lg font-extrabold text-slate-800">{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-5 text-white">
                <p class="text-sm text-blue-100">Hola{{ $cita->paciente ? ', '.$cita->paciente->nombre : '' }} 👋</p>
                <h1 class="text-xl font-extrabold">Detalle de tu cita</h1>
            </div>

            <div class="p-6">
                @if (session('ok') === 'confirmada')
                    <div class="mb-5 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 font-medium">¡Gracias! Tu asistencia quedo confirmada.</div>
                @elseif (session('ok') === 'cancelada')
                    <div class="mb-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-700 font-medium">Tu cita fue cancelada. Si fue un error, comunicate con la clinica.</div>
                @endif

                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Estado</span>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $estadoBadge[$cita->estado] ?? 'bg-slate-100 text-slate-600' }}">{{ $cita->estado_nombre }}</span>
                </div>

                <dl class="space-y-3 text-sm border-t border-slate-100 pt-4">
                    <div class="flex justify-between gap-3"><dt class="text-slate-400">Fecha</dt><dd class="text-slate-700 font-semibold text-right">{{ $cita->fecha->translatedFormat('l d \d\e F \d\e Y') }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-400">Hora</dt><dd class="text-slate-700 font-semibold text-right">{{ $cita->hora ? \Illuminate\Support\Str::of($cita->hora)->substr(0,5) : 'Por confirmar' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-400">Doctor</dt><dd class="text-slate-700 font-semibold text-right">{{ $cita->doctor->name ?? 'Por asignar' }}</dd></div>
                    @if($cita->motivo)<div class="flex justify-between gap-3"><dt class="text-slate-400">Motivo</dt><dd class="text-slate-700 font-semibold text-right">{{ $cita->motivo }}</dd></div>@endif
                </dl>

                @if(in_array($cita->estado, ['completada', 'cancelada'], true))
                    <p class="mt-6 text-center text-sm text-slate-400">Esta cita ya no admite cambios en linea.</p>
                @else
                    <div class="mt-6 grid grid-cols-1 gap-3">
                        @if($cita->estado !== 'confirmada')
                            <form method="POST" action="{{ route('cita.confirmar.post', $cita->token) }}">
                                @csrf
                                <button class="w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 shadow-lg shadow-emerald-600/25 transition">Confirmar asistencia</button>
                            </form>
                        @else
                            <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 text-center font-medium">Tu cita ya esta confirmada ✓</div>
                        @endif
                        <form method="POST" action="{{ route('cita.cancelar.post', $cita->token) }}" onsubmit="return confirm('¿Seguro que deseas cancelar tu cita?');">
                            @csrf
                            <button class="w-full rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 font-semibold py-3 transition">No podre asistir / cancelar</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            @if(!empty($config['telefono']))Dudas: {{ $config['telefono'] }} · @endif{{ $config['nombre_clinica'] ?? 'OdontoCRM' }}
        </p>
    </div>
</div>
</body>
</html>
