<?php

namespace App\Http\Controllers;

use App\Models\Evolucion;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EvolucionController extends Controller
{
    public function store(Request $request, Paciente $paciente): RedirectResponse
    {
        $data = $request->validate([
            'fecha' => ['required', 'date'],
            'diente' => ['nullable', 'string', 'max:10'],
            'descripcion' => ['required', 'string', 'max:2000'],
        ], [
            'fecha.required' => 'Indica la fecha.',
            'descripcion.required' => 'Escribe la evolucion.',
        ]);

        $paciente->evoluciones()->create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return redirect()->route('pacientes.show', ['paciente' => $paciente, 'tab' => 'evolucion'])
            ->with('status', 'Evolucion registrada.');
    }

    public function destroy(Evolucion $evolucion): RedirectResponse
    {
        $paciente = $evolucion->paciente;
        $evolucion->delete();

        return redirect()->route('pacientes.show', ['paciente' => $paciente, 'tab' => 'evolucion'])
            ->with('status', 'Evolucion eliminada.');
    }
}
