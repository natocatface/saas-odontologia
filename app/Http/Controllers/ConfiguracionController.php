<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    public function edit(): View
    {
        return view('configuracion.edit', ['config' => Configuracion::todas()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre_clinica' => ['required', 'string', 'max:120'],
            'nit' => ['nullable', 'string', 'max:50'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:200'],
            'ciudad' => ['nullable', 'string', 'max:100'],
            'moneda' => ['required', 'string', 'max:5'],
            'impuesto' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'num_sillas' => ['nullable', 'integer', 'min:1', 'max:50'],
            'horario' => ['nullable', 'string', 'max:200'],
            'mensaje_recordatorio' => ['nullable', 'string', 'max:500'],
        ], [
            'nombre_clinica.required' => 'El nombre de la clinica es obligatorio.',
            'moneda.required' => 'Indica el simbolo de moneda.',
        ]);

        Configuracion::guardar($data);

        return redirect()->route('configuracion.edit')->with('status', 'Configuracion guardada correctamente.');
    }
}
