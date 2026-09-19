<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\ComisionController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ----- Landing page publica -----
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('welcome');
})->name('home');

// ----- DEV: poblar datos de demostracion del dashboard (solo entorno local) -----
// Visita http://127.0.0.1:8090/__demo/seed en el navegador para regenerar
// ~10+ registros por modulo repartidos en 6 meses + citas de HOY (panel flecha roja).
// Se puede eliminar esta ruta cuando ya no la necesites.
Route::get('/__demo/seed', function () {
    abort_unless(app()->isLocal(), 404);

    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'DashboardDemoSeeder',
        '--force' => true,
    ]);

    $hoy = \Illuminate\Support\Carbon::today();
    $resumen = [
        'Pacientes' => \App\Models\Paciente::count(),
        'Citas (total)' => \App\Models\Cita::count(),
        'Citas de HOY' => \App\Models\Cita::whereDate('fecha', $hoy)->count(),
        'Presupuestos' => \App\Models\Presupuesto::count(),
        'Pagos' => \App\Models\Pago::count(),
        'Cuotas' => \App\Models\Cuota::count(),
        'Gastos' => \App\Models\Gasto::count(),
        'Insumos' => \App\Models\Insumo::count(),
        'Evoluciones' => \App\Models\Evolucion::count(),
    ];

    $filas = collect($resumen)
        ->map(fn ($v, $k) => "<tr><td style='padding:6px 18px 6px 0'>{$k}</td><td style='font-weight:600'>{$v}</td></tr>")
        ->implode('');

    return <<<HTML
        <div style="font-family:system-ui,Segoe UI,Arial;max-width:520px;margin:60px auto;padding:28px;border:1px solid #e2e8f0;border-radius:16px">
            <h2 style="margin:0 0 4px;color:#0f172a">✅ Datos de demostracion generados</h2>
            <p style="color:#64748b;margin:0 0 18px">Se regeneraron los registros y se crearon citas para HOY ({$hoy->format('d/m/Y')}).</p>
            <table style="border-collapse:collapse;color:#334155;font-size:14px">{$filas}</table>
            <a href="/dashboard" style="display:inline-block;margin-top:22px;background:#2563eb;color:#fff;text-decoration:none;padding:10px 18px;border-radius:10px;font-weight:600">Ir al Dashboard →</a>
        </div>
    HTML;
})->name('demo.seed');

// ----- Confirmacion publica de citas (sin login) -----
Route::get('/cita/confirmar/{token}', [\App\Http\Controllers\CitaPublicaController::class, 'show'])->name('cita.confirmar');
Route::post('/cita/confirmar/{token}', [\App\Http\Controllers\CitaPublicaController::class, 'confirmar'])->name('cita.confirmar.post');
Route::post('/cita/cancelar/{token}', [\App\Http\Controllers\CitaPublicaController::class, 'cancelar'])->name('cita.cancelar.post');

// ----- Portal del paciente -----
Route::prefix('portal')->group(function () {
    Route::get('login', [\App\Http\Controllers\Portal\PortalAuthController::class, 'showLogin'])->name('portal.login');
    Route::post('login', [\App\Http\Controllers\Portal\PortalAuthController::class, 'login'])->name('portal.login.attempt');

    Route::middleware('auth:paciente')->group(function () {
        Route::post('logout', [\App\Http\Controllers\Portal\PortalAuthController::class, 'logout'])->name('portal.logout');
        Route::get('/', [\App\Http\Controllers\Portal\PortalController::class, 'dashboard'])->name('portal.dashboard');
        Route::get('citas', [\App\Http\Controllers\Portal\PortalController::class, 'citas'])->name('portal.citas');
        Route::get('presupuestos', [\App\Http\Controllers\Portal\PortalController::class, 'presupuestos'])->name('portal.presupuestos');
        Route::get('estado-cuenta', [\App\Http\Controllers\Portal\PortalController::class, 'estadoCuenta'])->name('portal.estado-cuenta');
        Route::get('recibo/{pago}', [\App\Http\Controllers\Portal\PortalController::class, 'recibo'])->name('portal.recibo');
    });
});

// ----- Autenticacion -----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

    // Recuperar contrasena
    Route::get('/forgot-password', [PasswordController::class, 'showForgot'])->name('password.request');
    Route::post('/forgot-password', [PasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordController::class, 'showReset'])->name('password.reset');
    Route::post('/reset-password', [PasswordController::class, 'reset'])->name('password.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ----- Area autenticada -----
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cambiar contrasena
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    // ----- Modulos funcionales -----
    Route::resource('pacientes', PacienteController::class);
    Route::patch('pacientes/{paciente}/estado', [PacienteController::class, 'toggleEstado'])->name('pacientes.estado');
    Route::get('pacientes/{paciente}/estado-cuenta', [PacienteController::class, 'estadoCuenta'])->name('pacientes.estado-cuenta');
    Route::get('pacientes/{paciente}/odontograma', [PacienteController::class, 'odontograma'])->name('pacientes.odontograma');
    Route::put('pacientes/{paciente}/odontograma', [PacienteController::class, 'guardarOdontograma'])->name('pacientes.odontograma.update');

    // Evolucion clinica y archivos del paciente
    Route::post('pacientes/{paciente}/evoluciones', [\App\Http\Controllers\EvolucionController::class, 'store'])->name('evoluciones.store');
    Route::delete('evoluciones/{evolucion}', [\App\Http\Controllers\EvolucionController::class, 'destroy'])->name('evoluciones.destroy');
    Route::post('pacientes/{paciente}/archivos', [\App\Http\Controllers\ArchivoController::class, 'store'])->name('archivos.store');
    Route::delete('archivos/{archivo}', [\App\Http\Controllers\ArchivoController::class, 'destroy'])->name('archivos.destroy');

    // Consentimientos informados
    Route::post('pacientes/{paciente}/consentimientos', [\App\Http\Controllers\ConsentimientoController::class, 'store'])->name('consentimientos.store');
    Route::patch('consentimientos/{consentimiento}/firmar', [\App\Http\Controllers\ConsentimientoController::class, 'firmar'])->name('consentimientos.firmar');
    Route::get('consentimientos/{consentimiento}/imprimir', [\App\Http\Controllers\ConsentimientoController::class, 'imprimir'])->name('consentimientos.imprimir');
    Route::delete('consentimientos/{consentimiento}', [\App\Http\Controllers\ConsentimientoController::class, 'destroy'])->name('consentimientos.destroy');

    Route::get('citas/calendario', [CitaController::class, 'calendario'])->name('citas.calendario');
    Route::get('citas/disponibilidad', [CitaController::class, 'disponibilidad'])->name('citas.disponibilidad');
    Route::resource('citas', CitaController::class)->except('show');
    Route::patch('citas/{cita}/estado', [CitaController::class, 'cambiarEstado'])->name('citas.estado');
    Route::post('citas/{cita}/recordatorio', [CitaController::class, 'enviarRecordatorio'])->name('citas.recordatorio');

    Route::resource('tratamientos', TratamientoController::class)->except('show');
    Route::patch('tratamientos/{tratamiento}/estado', [TratamientoController::class, 'toggleEstado'])->name('tratamientos.estado');

    Route::resource('presupuestos', PresupuestoController::class);
    Route::patch('presupuestos/{presupuesto}/estado', [PresupuestoController::class, 'cambiarEstado'])->name('presupuestos.estado');
    Route::patch('presupuesto-items/{item}/realizado', [PresupuestoController::class, 'toggleItem'])->name('presupuestos.item.realizado');

    Route::resource('pagos', PagoController::class)->except('edit', 'update', 'show');
    Route::get('pagos/{pago}/recibo', [PagoController::class, 'recibo'])->name('pagos.recibo');

    // Plan de pago en cuotas
    Route::post('presupuestos/{presupuesto}/cuotas', [CuotaController::class, 'generar'])->name('cuotas.generar');
    Route::delete('presupuestos/{presupuesto}/cuotas', [CuotaController::class, 'destroyPlan'])->name('cuotas.destroyPlan');
    Route::post('cuotas/{cuota}/pagar', [CuotaController::class, 'pagar'])->name('cuotas.pagar');

    // Gastos y caja
    Route::resource('gastos', GastoController::class)->except('edit', 'update', 'show');
    Route::get('caja', [CajaController::class, 'index'])->name('caja.index');

    // Inventario de insumos
    Route::resource('insumos', InsumoController::class);
    Route::post('insumos/{insumo}/movimiento', [InsumoController::class, 'movimiento'])->name('insumos.movimiento');

    // Exportaciones (CSV / PDF)
    Route::get('export/pacientes', [\App\Http\Controllers\ExportController::class, 'pacientes'])->name('export.pacientes');
    Route::get('export/pagos', [\App\Http\Controllers\ExportController::class, 'pagos'])->name('export.pagos');
    Route::get('export/citas', [\App\Http\Controllers\ExportController::class, 'citas'])->name('export.citas');
    Route::get('export/gastos', [\App\Http\Controllers\ExportController::class, 'gastos'])->name('export.gastos');
    Route::get('export/insumos', [\App\Http\Controllers\ExportController::class, 'insumos'])->name('export.insumos');

    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');

    // Usuarios: solo administradores
    Route::middleware('role:admin')->group(function () {
        Route::resource('usuarios', UserController::class)->except('show')->parameters(['usuarios' => 'user']);
        Route::patch('usuarios/{user}/estado', [UserController::class, 'toggleEstado'])->name('usuarios.estado');

        Route::get('configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
        Route::put('configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

        // Facturacion Electronica (Peru / SUNAT)
        Route::get('facturacion/comprobantes', [\App\Http\Controllers\FacturacionComprobanteController::class, 'index'])->name('facturacion.comprobantes.index');
        Route::get('facturacion/comprobantes/{documento}', [\App\Http\Controllers\FacturacionComprobanteController::class, 'representacion'])->name('facturacion.comprobantes.representacion');
        Route::get('facturacion/comprobantes/{documento}/xml', [\App\Http\Controllers\FacturacionComprobanteController::class, 'descargarXml'])->name('facturacion.comprobantes.xml');
        Route::get('facturacion/comprobantes/{documento}/cdr', [\App\Http\Controllers\FacturacionComprobanteController::class, 'descargarCdr'])->name('facturacion.comprobantes.cdr');
        Route::post('facturacion/comprobantes/{documento}/anular', [\App\Http\Controllers\FacturacionComprobanteController::class, 'anular'])->name('facturacion.comprobantes.anular');

        Route::get('facturacion/configuracion', [\App\Http\Controllers\FacturacionConfigController::class, 'edit'])->name('facturacion.config.edit');
        Route::put('facturacion/configuracion', [\App\Http\Controllers\FacturacionConfigController::class, 'update'])->name('facturacion.config.update');
        Route::post('facturacion/configuracion/probar', [\App\Http\Controllers\FacturacionConfigController::class, 'probar'])->name('facturacion.config.probar');

        Route::get('comisiones', [ComisionController::class, 'index'])->name('comisiones.index');

        Route::get('actividad', [ActividadController::class, 'index'])->name('actividad.index');

        Route::get('mantenimiento', [MantenimientoController::class, 'index'])->name('mantenimiento.index');
        Route::post('mantenimiento/cache', [MantenimientoController::class, 'limpiarCache'])->name('mantenimiento.cache');
        Route::get('mantenimiento/respaldo', [MantenimientoController::class, 'respaldo'])->name('mantenimiento.respaldo');
        Route::post('mantenimiento/restaurar', [MantenimientoController::class, 'restaurar'])->name('mantenimiento.restaurar');
    });

    // Modulos (placeholders navegables aun no implementados)
    Route::get('/m/{modulo}', [ModuleController::class, 'show'])->name('modulo');
});
