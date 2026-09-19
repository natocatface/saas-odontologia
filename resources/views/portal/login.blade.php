<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal del paciente · {{ \App\Models\Configuracion::valor('nombre_clinica', 'OdontoCRM') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body{font-family:'Inter',sans-serif}</style>
</head>
<body class="h-full bg-slate-100">
<div class="min-h-full flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-blue-600 flex items-center justify-center shadow-lg">
                <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C9 2 7.5 3.2 6 3.2S3.5 2.4 2.6 4C1.4 6 2 9.5 2.8 12.5c.5 1.9.7 3 1 4.4.4 1.9.6 4.6 2.1 4.6 1.6 0 1.5-3.2 2.4-5.1.5-1.1 1-1.8 1.7-1.8s1.2.7 1.7 1.8c.9 1.9.8 5.1 2.4 5.1 1.5 0 1.7-2.7 2.1-4.6.3-1.4.5-2.5 1-4.4C22 9.5 22.6 6 21.4 4c-.9-1.6-1.9-.8-3.4-.8S15 2 12 2Z"/></svg>
            </div>
            <p class="mt-3 text-lg font-extrabold text-slate-800">{{ \App\Models\Configuracion::valor('nombre_clinica', 'OdontoCRM') }}</p>
            <p class="text-sm text-slate-500">Portal del paciente</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-100 p-8">
            <h1 class="text-xl font-extrabold text-slate-800">Entra a tu portal</h1>
            <p class="mt-1 text-sm text-slate-500">Consulta tus citas, presupuestos y saldo.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('portal.login.attempt') }}" class="mt-6 space-y-5" x-data="{ show:false }">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Correo</label>
                    <input name="email" type="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white px-3 py-3 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"
                           placeholder="tu@correo.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Clave</label>
                    <div class="relative">
                        <input name="password" :type="show ? 'text' : 'password'" required
                               class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white px-3 pr-10 py-3 text-slate-800 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none transition"
                               placeholder="••••••••">
                        <button type="button" @click="show=!show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-400">
                    Recordarme
                </label>
                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 shadow-lg shadow-blue-600/25 transition">Ingresar</button>
            </form>

            <p class="mt-6 text-center text-xs text-slate-400">¿No tienes acceso? Solicítalo en la recepción de la clínica.</p>
        </div>

        <p class="mt-6 text-center text-sm"><a href="{{ route('login') }}" class="text-slate-400 hover:text-slate-600">Soy del personal de la clínica</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
