<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CitaPublicaController extends Controller
{
    /** Pagina publica para que el paciente vea y confirme su cita. */
    public function show(string $token): View
    {
        $cita = Cita::with(['paciente', 'doctor'])->where('token', $token)->firstOrFail();

        return view('publico.cita', [
            'cita' => $cita,
            'config' => Configuracion::todas(),
        ]);
    }

    /** El paciente confirma su asistencia. */
    public function confirmar(string $token): RedirectResponse
    {
        $cita = Cita::where('token', $token)->firstOrFail();

        if (! in_array($cita->estado, ['completada', 'cancelada'], true)) {
            $cita->update(['estado' => 'confirmada']);
        }

        return redirect()->route('cita.confirmar', $token)->with('ok', 'confirmada');
    }

    /** El paciente solicita cancelar su cita. */
    public function cancelar(string $token): RedirectResponse
    {
        $cita = Cita::where('token', $token)->firstOrFail();

        if ($cita->estado !== 'completada') {
            $cita->update(['estado' => 'cancelada']);
        }

        return redirect()->route('cita.confirmar', $token)->with('ok', 'cancelada');
    }
}
