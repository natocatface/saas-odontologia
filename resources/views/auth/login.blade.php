<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OdontoCRM - Iniciar Sesion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body{font-family:'Inter',sans-serif}
        [x-cloak]{display:none!important}
    </style>
</head>
<body class="h-full bg-slate-100">
<div class="min-h-full flex">

    <!-- Panel izquierdo (marca) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-blue-800 via-indigo-800 to-blue-900">
        <div class="absolute inset-0 opacity-[0.12]" style="background-image:radial-gradient(circle at 20% 25%, white 1.5px, transparent 1.5px),radial-gradient(circle at 75% 65%, white 1.5px, transparent 1.5px);background-size:56px 56px;"></div>
        <div class="absolute -top-24 -left-24 h-80 w-80 rounded-full bg-cyan-400/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-indigo-500/20 blur-3xl"></div>

        <div class="relative z-10 flex flex-col justify-center w-full px-12 xl:px-20 py-12 text-white">
            <!-- Logo -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center shadow-2xl shadow-cyan-500/30">
                    <svg class="h-11 w-11 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
                </div>
                <h1 class="mt-5 text-3xl font-900 font-extrabold tracking-tight">OdontoCRM</h1>
                <p class="mt-1 text-xs font-semibold tracking-[0.25em] text-cyan-300">SISTEMA DE GESTION DENTAL</p>
            </div>

            <!-- Features -->
            <div class="mt-12 space-y-6 max-w-md mx-auto w-full">
                @php
                    $features = [
                        ['M16 14a4 4 0 10-8 0M12 7a3 3 0 100 6 3 3 0 000-6zM3 20c0-2.5 2-4 5-4M21 20c0-2.5-2-4-5-4', 'Gestion de Pacientes', 'Historia clinica y datos centralizados'],
                        ['M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z', 'Citas y Agenda', 'Programa y controla tus citas'],
                        ['M5 20V10M12 20V4M19 20v-6M3 20h18', 'Reportes Avanzados', 'Metricas y estadisticas en tiempo real'],
                        ['M9 18h6M10 22h4M12 2a7 7 0 00-4 12.7c.6.5 1 1.3 1 2.1h6c0-.8.4-1.6 1-2.1A7 7 0 0012 2z', 'Tratamientos y Pagos', 'Presupuestos, cobros e ingresos'],
                    ];
                @endphp
                @foreach ($features as [$icon,$titulo,$desc])
                    <div class="flex items-center gap-4">
                        <div class="h-11 w-11 shrink-0 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center text-cyan-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-white">{{ $titulo }}</p>
                            <p class="text-sm text-blue-200">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Stats -->
            <div class="mt-12 grid grid-cols-3 gap-3 max-w-md mx-auto w-full">
                @foreach ([['+10k','Pacientes'],['98%','Satisfaccion'],['24/7','Soporte']] as [$num,$lbl])
                    <div class="rounded-2xl bg-white/5 border border-white/10 backdrop-blur px-3 py-4 text-center">
                        <p class="text-2xl font-extrabold text-white">{{ $num }}</p>
                        <p class="text-xs text-blue-200 mt-0.5">{{ $lbl }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Panel derecho (formulario) -->
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <!-- Marca movil -->
            <div class="lg:hidden flex flex-col items-center mb-8">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg">
                    <svg class="h-9 w-9 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
                </div>
                <p class="mt-3 text-xl font-extrabold text-slate-800">OdontoCRM</p>
            </div>

            <h2 class="text-3xl font-extrabold text-slate-800">Bienvenido de vuelta 👋</h2>
            <p class="mt-1 text-slate-500">Ingresa tus credenciales para acceder al sistema.</p>

            @if (session('status'))
                <div class="mt-6 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mt-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="mt-7 space-y-5" x-data="{ show:false }">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Correo Electronico</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white pl-10 pr-3 py-3 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"
                               placeholder="admin@odontocrm.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Contrasena</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/></svg>
                        </span>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white pl-10 pr-10 py-3 text-slate-800 placeholder-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"
                               placeholder="••••••••">
                        <button type="button" @click="show=!show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                        Recordarme
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">¿Olvidaste tu contrasena?</a>
                </div>

                <button type="submit"
                        class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 shadow-lg shadow-blue-600/25 transition flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4-4-4M15 12H3M9 4h8a2 2 0 012 2v12a2 2 0 01-2 2H9"/></svg>
                    Iniciar Sesion
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-8 flex items-center gap-3">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs text-slate-400 font-medium">Cuentas de demostracion</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <!-- Acceso rapido -->
            <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50/50 p-4">
                <p class="flex items-center gap-2 text-xs font-bold text-blue-700 mb-3">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a4 4 0 11-8 0 4 4 0 018 0zM7 11l-4 4 2 2M9 13l2 2"/></svg>
                    ACCESO RAPIDO
                </p>
                <div class="space-y-2">
                    @php
                        $demo = [
                            ['admin@odontocrm.com', 'Admin', 'bg-violet-100 text-violet-700'],
                            ['doctor1@odontocrm.com', 'Doctor', 'bg-emerald-100 text-emerald-700'],
                            ['recepcion@odontocrm.com', 'Recepcion', 'bg-rose-100 text-rose-700'],
                        ];
                    @endphp
                    @foreach ($demo as [$correo,$rol,$badge])
                        <button type="button" onclick="llenar('{{ $correo }}')"
                                class="w-full flex items-center justify-between rounded-xl bg-white border border-slate-100 hover:border-blue-300 hover:shadow-sm px-3 py-2.5 transition group">
                            <span class="text-sm text-slate-600 group-hover:text-slate-800">{{ $correo }}</span>
                            <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $badge }}">{{ $rol }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <p class="mt-6 text-center text-sm"><a href="{{ route('portal.login') }}" class="text-blue-600 font-semibold hover:text-blue-700">¿Eres paciente? Entra a tu portal</a></p>
            <p class="mt-4 text-center text-xs text-slate-400">&copy; {{ date('Y') }} OdontoCRM · Todos los derechos reservados.</p>
        </div>
    </div>
</div>

<script>
    function llenar(correo) {
        document.getElementById('email').value = correo;
        document.getElementById('password').value = 'password';
        document.getElementById('password').focus();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
