<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OdontoCRM — Gestiona tu clinica dental de forma inteligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="bg-slate-950 text-white antialiased">

<!-- Navbar -->
<header class="sticky top-0 z-50 backdrop-blur bg-slate-950/70 border-b border-white/5">
    <nav class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
        <a href="#" class="flex items-center gap-2.5">
            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center">
                <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <span class="text-lg font-extrabold tracking-tight">OdontoCRM</span>
        </a>
        <div class="hidden md:flex items-center gap-8 text-sm text-slate-300">
            <a href="#funciones" class="hover:text-white transition">Funciones</a>
            <a href="#precios" class="hover:text-white transition">Precios</a>
            <a href="{{ route('portal.login') }}" class="hover:text-white transition">Portal del paciente</a>
            <a href="{{ route('login') }}" class="hover:text-white transition">Iniciar sesion</a>
        </div>
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white text-sm font-semibold px-4 py-2 shadow-lg shadow-blue-600/30 transition">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M18 12H4"/></svg>
            Prueba gratis
        </a>
    </nav>
</header>

<!-- Hero -->
<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-indigo-950 to-slate-950"></div>
    <div class="absolute inset-0 opacity-[0.10]" style="background-image:radial-gradient(circle at 25% 20%, white 1.5px, transparent 1.5px),radial-gradient(circle at 75% 60%, white 1.5px, transparent 1.5px);background-size:60px 60px;"></div>
    <div class="absolute -top-32 left-1/4 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl"></div>
    <div class="absolute top-20 right-10 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl"></div>

    <div class="relative max-w-4xl mx-auto px-6 pt-20 pb-24 text-center">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 border border-white/15 backdrop-blur px-4 py-1.5 text-sm font-medium text-cyan-200">
            🦷 Plataforma SaaS #1 para Clinicas Dentales
        </span>
        <h1 class="mt-8 text-5xl sm:text-6xl font-extrabold leading-[1.05] tracking-tight">
            Gestiona tu clinica dental
            <span class="block mt-2 bg-gradient-to-r from-cyan-300 via-sky-300 to-blue-300 bg-clip-text text-transparent">de forma inteligente</span>
        </h1>
        <p class="mt-6 text-lg text-slate-300 max-w-2xl mx-auto">
            Todo lo que necesitas para administrar pacientes, citas, tratamientos, presupuestos y pagos. Sin complicaciones, desde cualquier dispositivo.
        </p>
        <div class="mt-9 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-500 hover:to-cyan-400 text-white font-bold px-7 py-3.5 shadow-xl shadow-blue-600/30 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M18 12H4"/></svg>
                Comenzar gratis — 30 dias
            </a>
            <a href="#precios" class="inline-flex items-center gap-2 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 backdrop-blur text-white font-semibold px-7 py-3.5 transition">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a2 2 0 011.4.6l7 7a2 2 0 010 2.8l-5.6 5.6a2 2 0 01-2.8 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                Ver precios
            </a>
        </div>

        <!-- Stats -->
        <div class="mt-16 grid grid-cols-2 sm:grid-cols-4 gap-8 max-w-3xl mx-auto">
            @foreach ([['500+','Clinicas activas'],['50k+','Pacientes gestionados'],['99.9%','Uptime garantizado'],['30 dias','Prueba gratuita']] as [$num,$lbl])
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-white to-slate-300 bg-clip-text text-transparent">{{ $num }}</p>
                    <p class="mt-1 text-sm text-slate-400">{{ $lbl }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Funciones -->
<section id="funciones" class="bg-slate-950 py-24">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-2xl mx-auto">
            <span class="text-sm font-semibold text-cyan-400 uppercase tracking-wider">Funciones</span>
            <h2 class="mt-3 text-3xl sm:text-4xl font-extrabold">Todo lo que tu clinica necesita</h2>
            <p class="mt-4 text-slate-400">Un sistema completo, pensado para el dia a dia de una clinica odontologica.</p>
        </div>

        <div class="mt-14 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
                $items = [
                    ['from-blue-500 to-blue-600','M16 14a4 4 0 10-8 0M12 7a3 3 0 100 6 3 3 0 000-6zM3 20c0-2.5 2-4 5-4M21 20c0-2.5-2-4-5-4','Pacientes','Historia clinica completa, alergias, contacto y seguimiento de cada paciente.'],
                    ['from-teal-500 to-emerald-600','M7 3v3M17 3v3M4 8h16M5 6h14a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V7a1 1 0 011-1z','Citas y Agenda','Programa citas por doctor, controla estados y evita cruces de horario.'],
                    ['from-cyan-500 to-blue-500','M9 4h6v2H9zM7 4H6a1 1 0 00-1 1v15a1 1 0 001 1h12a1 1 0 001-1V5a1 1 0 00-1-1h-1','Tratamientos','Catalogo de procedimientos con precios y duracion siempre a la mano.'],
                    ['from-violet-500 to-purple-600','M7 3h7l5 5v12a1 1 0 01-1 1H7a1 1 0 01-1-1V4a1 1 0 011-1z','Presupuestos','Cotiza tratamientos con lineas de detalle y controla aprobaciones.'],
                    ['from-emerald-500 to-teal-600','M12 3v18M16 7H10a2 2 0 100 4h4a2 2 0 110 4H8','Pagos e Ingresos','Registra cobros por metodo y conoce el saldo de cada presupuesto.'],
                    ['from-indigo-500 to-blue-600','M5 20V10M12 20V4M19 20v-6M3 20h18','Reportes','KPIs, graficas y productividad por doctor con filtros por fecha.'],
                ];
            @endphp
            @foreach ($items as [$grad,$icon,$titulo,$desc])
                <div class="group rounded-2xl bg-white/[0.03] border border-white/10 hover:border-cyan-400/40 hover:bg-white/[0.06] p-6 transition">
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br {{ $grad }} flex items-center justify-center shadow-lg">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-bold">{{ $titulo }}</h3>
                    <p class="mt-2 text-sm text-slate-400 leading-relaxed">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Precios / CTA -->
<section id="precios" class="bg-slate-950 pb-24">
    <div class="max-w-5xl mx-auto px-6">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-indigo-700 to-blue-800 p-10 sm:p-14 text-center">
            <div class="absolute -top-20 -right-16 h-72 w-72 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="relative">
                <h2 class="text-3xl sm:text-4xl font-extrabold">Empieza hoy, sin tarjeta de credito</h2>
                <p class="mt-4 text-blue-100 max-w-xl mx-auto">Prueba OdontoCRM gratis por 30 dias. Configura tu clinica en minutos y digitaliza toda tu operacion.</p>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white text-blue-700 hover:bg-blue-50 font-bold px-7 py-3.5 shadow-lg transition">
                        Comenzar ahora
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M18 12H4"/></svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-7 py-3.5 transition">
                        Iniciar sesion
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="border-t border-white/5 bg-slate-950">
    <div class="max-w-7xl mx-auto px-6 py-10 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center">
                <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <span class="font-bold">OdontoCRM</span>
        </div>
        <p class="text-sm text-slate-500">&copy; {{ date('Y') }} OdontoCRM · Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
