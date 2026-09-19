<?php

namespace App\Http\Controllers;

use App\Models\FacturacionConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Configuracion de Facturacion Electronica (Peru / SUNAT).
 * Solo administradores (ruta protegida por middleware role:admin).
 */
class FacturacionConfigController extends Controller
{
    public function edit(): View
    {
        return view('facturacion.configuracion', [
            'config'     => FacturacionConfig::todas(),
            'certExiste' => FacturacionConfig::certificadoExiste(),
            'endpoint'   => FacturacionConfig::endpoint(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'fe_habilitada'       => ['nullable', 'boolean'],
            'fe_auto_emitir'      => ['nullable', 'boolean'],
            'fe_driver'           => ['required', 'in:ninguno,demo,greenter'],
            'fe_entorno'          => ['required', 'in:beta,produccion'],

            'fe_ruc'              => ['nullable', 'string', 'size:11'],
            'fe_razon_social'     => ['nullable', 'string', 'max:200'],
            'fe_nombre_comercial' => ['nullable', 'string', 'max:200'],
            'fe_direccion'        => ['nullable', 'string', 'max:200'],
            'fe_ubigeo'           => ['nullable', 'string', 'max:6'],
            'fe_departamento'     => ['nullable', 'string', 'max:60'],
            'fe_provincia'        => ['nullable', 'string', 'max:60'],
            'fe_distrito'         => ['nullable', 'string', 'max:60'],

            'fe_usuario_sol'      => ['nullable', 'string', 'max:60'],
            'fe_clave_sol'        => ['nullable', 'string', 'max:100'],
            'fe_cert_path'        => ['nullable', 'string', 'max:255'],
            'fe_cert_pass'        => ['nullable', 'string', 'max:100'],
        ], [
            'fe_ruc.size'      => 'El RUC debe tener 11 digitos.',
            'fe_driver.in'     => 'Driver de emision no valido.',
            'fe_entorno.in'    => 'Entorno SUNAT no valido.',
        ]);

        // Los checkboxes no marcados no llegan en el request: normalizamos a 0/1.
        $data['fe_habilitada']  = $request->boolean('fe_habilitada') ? '1' : '0';
        $data['fe_auto_emitir'] = $request->boolean('fe_auto_emitir') ? '1' : '0';

        // Si el campo de clave llega vacio, conservamos la clave guardada
        // (para no borrarla al reeditar el formulario).
        foreach (['fe_clave_sol', 'fe_cert_pass'] as $secreta) {
            if (($data[$secreta] ?? '') === '') {
                unset($data[$secreta]);
            }
        }

        FacturacionConfig::guardar($data);

        return redirect()
            ->route('facturacion.config.edit')
            ->with('status', 'Configuracion de facturacion guardada correctamente.');
    }

    /**
     * Prueba la configuracion: valida campos minimos, existencia del
     * certificado y, si el driver es Greenter, que la libreria pueda
     * cargar el certificado y las credenciales.
     */
    public function probar(): RedirectResponse
    {
        $p = FacturacionConfig::parametrosSunat();
        $problemas = [];

        if (strlen($p['ruc']) !== 11) {
            $problemas[] = 'El RUC debe tener 11 digitos.';
        }
        if ($p['usuario_sol'] === '' || $p['clave_sol'] === '') {
            $problemas[] = 'Faltan las credenciales de Clave SOL (usuario y/o clave).';
        }
        if ($p['cert_path'] === '' || ! is_file($p['cert_path'])) {
            $problemas[] = 'No se encontro el certificado digital en la ruta indicada.';
        }

        if ($problemas) {
            return back()->with('error', 'No se pudo validar la conexion: '.implode(' ', $problemas));
        }

        if ($p['driver'] === 'greenter') {
            if (! class_exists(\Greenter\See::class)) {
                return back()->with('error', 'El driver Greenter no esta instalado. Ejecuta: composer require greenter/lite');
            }

            try {
                $see = new \Greenter\See();
                $see->setCertificate(file_get_contents($p['cert_path']));
                $see->setClaveSOL($p['ruc'], $p['usuario_sol'], $p['clave_sol']);
                $see->setService($p['entorno'] === 'produccion'
                    ? \Greenter\Ws\Services\SunatEndpoints::FE_PRODUCCION
                    : \Greenter\Ws\Services\SunatEndpoints::FE_BETA);
            } catch (\Throwable $e) {
                return back()->with('error', 'El certificado o las credenciales no son validos: '.$e->getMessage());
            }

            return back()->with('status', 'Configuracion valida. Certificado y credenciales cargados correctamente para el entorno '.strtoupper($p['entorno']).'.');
        }

        $modo = $p['driver'] === 'demo' ? 'modo DEMO (emision simulada)' : 'sin driver (los comprobantes quedan pendientes)';

        return back()->with('status', "Datos basicos correctos. Actualmente estas en {$modo}. Selecciona el driver Greenter para emitir realmente ante SUNAT.");
    }
}
