<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use App\Models\Paciente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ArchivoController extends Controller
{
    public function store(Request $request, Paciente $paciente): RedirectResponse
    {
        $request->validate([
            'archivo' => ['required', 'file', 'max:8192', 'mimes:jpg,jpeg,png,webp,gif,pdf'],
            'categoria' => ['required', Rule::in(array_keys(Archivo::CATEGORIAS))],
        ], [
            'archivo.required' => 'Selecciona un archivo.',
            'archivo.max' => 'El archivo no puede superar 8 MB.',
            'archivo.mimes' => 'Formato no permitido (usa imagen o PDF).',
        ]);

        $file = $request->file('archivo');
        $ruta = $file->store('pacientes/'.$paciente->id, 'public');

        $paciente->archivos()->create([
            'nombre' => $file->getClientOriginalName(),
            'ruta' => $ruta,
            'mime' => $file->getClientMimeType(),
            'tamano' => $file->getSize(),
            'categoria' => $request->input('categoria'),
        ]);

        return redirect()->route('pacientes.show', ['paciente' => $paciente, 'tab' => 'archivos'])
            ->with('status', 'Archivo subido correctamente.');
    }

    public function destroy(Archivo $archivo): RedirectResponse
    {
        $paciente = $archivo->paciente;
        Storage::disk('public')->delete($archivo->ruta);
        $archivo->delete();

        return redirect()->route('pacientes.show', ['paciente' => $paciente, 'tab' => 'archivos'])
            ->with('status', 'Archivo eliminado.');
    }
}
