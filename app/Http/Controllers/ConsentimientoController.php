<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Consentimiento;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ConsentimientoController extends Controller
{
    public function store(Request $request, Paciente $paciente): RedirectResponse
    {
        $data = $request->validate([
            'tipo' => ['required', Rule::in(array_keys(Consentimiento::TIPOS))],
            'contenido' => ['nullable', 'string', 'max:5000'],
        ], [
            'tipo.required' => 'Selecciona el tipo de consentimiento.',
        ]);

        $paciente->consentimientos()->create([
            'user_id' => $request->user()->id,
            'tipo' => $data['tipo'],
            'titulo' => Consentimiento::TIPOS[$data['tipo']],
            'contenido' => $data['contenido'] ?: (Consentimiento::PLANTILLAS[$data['tipo']] ?? ''),
            'firmado' => false,
        ]);

        return redirect()->route('pacientes.show', ['paciente' => $paciente, 'tab' => 'consentimientos'])
            ->with('status', 'Consentimiento generado.');
    }

    public function firmar(Request $request, Consentimiento $consentimiento): RedirectResponse
    {
        $data = $request->validate([
            'firmante' => ['required', 'string', 'max:150'],
        ], [
            'firmante.required' => 'Indica el nombre de quien firma.',
        ]);

        $consentimiento->update([
            'firmado' => true,
            'fecha_firma' => Carbon::today(),
            'firmante' => $data['firmante'],
        ]);

        return redirect()->route('pacientes.show', ['paciente' => $consentimiento->paciente_id, 'tab' => 'consentimientos'])
            ->with('status', 'Consentimiento marcado como firmado.');
    }

    public function imprimir(Consentimiento $consentimiento): View
    {
        $consentimiento->load('paciente');

        return view('consentimientos.imprimir', [
            'consentimiento' => $consentimiento,
            'config' => Configuracion::todas(),
        ]);
    }

    public function destroy(Consentimiento $consentimiento): RedirectResponse
    {
        $pacienteId = $consentimiento->paciente_id;
        $consentimiento->delete();

        return redirect()->route('pacientes.show', ['paciente' => $pacienteId, 'tab' => 'consentimientos'])
            ->with('status', 'Consentimiento eliminado.');
    }
}
