<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Configuracion de Facturacion Electronica (clave-valor, editable desde la UI).
 *
 * Tolerante a que la tabla no exista todavia (Schema::hasTable + try/catch),
 * igual que App\Models\Configuracion, para no romper la app antes de migrar.
 */
class FacturacionConfig extends Model
{
    protected $table = 'facturacion_configuraciones';

    protected $fillable = ['clave', 'valor'];

    public $timestamps = true;

    /** Claves cuyo valor NO debe mostrarse tal cual en logs / respuestas. */
    public const SECRETAS = ['fe_clave_sol', 'fe_cert_pass'];

    /** Valores por defecto. Beta de SUNAT lista para pruebas de homologacion. */
    public const DEFAULTS = [
        // Estado y modo
        'fe_habilitada'        => '0',        // '1' emite comprobantes ante SUNAT
        'fe_auto_emitir'       => '0',        // emitir al cerrar la venta/pago
        'fe_driver'            => 'ninguno',  // ninguno | demo | greenter
        'fe_entorno'           => 'beta',     // beta | produccion

        // Datos del emisor (aparecen en el comprobante)
        'fe_ruc'               => '',
        'fe_razon_social'      => '',
        'fe_nombre_comercial'  => '',
        'fe_direccion'         => '',
        'fe_ubigeo'            => '',
        'fe_departamento'      => '',
        'fe_provincia'         => '',
        'fe_distrito'          => '',

        // Credenciales SUNAT (Clave SOL + certificado digital .pem)
        'fe_usuario_sol'       => '',
        'fe_clave_sol'         => '',
        'fe_cert_path'         => '',
        'fe_cert_pass'         => '',
    ];

    /** Endpoints oficiales del servicio de comprobantes (billService). */
    public const ENDPOINTS = [
        'beta'       => 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService',
        'produccion' => 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService',
    ];

    /** Cache en memoria durante el request. */
    protected static ?array $cache = null;

    /** Devuelve todas las claves combinadas con los valores por defecto. */
    public static function todas(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        $almacenadas = [];
        try {
            if (Schema::hasTable('facturacion_configuraciones')) {
                $almacenadas = static::query()->pluck('valor', 'clave')->toArray();
            }
        } catch (\Throwable $e) {
            $almacenadas = [];
        }

        return static::$cache = array_merge(
            self::DEFAULTS,
            array_filter($almacenadas, fn ($v) => $v !== null)
        );
    }

    /** Devuelve un valor con fallback a DEFAULTS. */
    public static function valor(string $clave, ?string $default = null): ?string
    {
        return static::todas()[$clave] ?? $default ?? (self::DEFAULTS[$clave] ?? null);
    }

    /** Guarda un conjunto de pares clave => valor. */
    public static function guardar(array $pares): void
    {
        foreach ($pares as $clave => $valor) {
            static::updateOrCreate(['clave' => $clave], ['valor' => (string) $valor]);
        }

        static::$cache = null;
    }

    // ---- Helpers de conveniencia -------------------------------------------

    public static function habilitada(): bool
    {
        return static::valor('fe_habilitada') === '1';
    }

    public static function autoEmitir(): bool
    {
        return static::valor('fe_auto_emitir') === '1';
    }

    public static function driver(): string
    {
        return static::valor('fe_driver', 'ninguno');
    }

    public static function entorno(): string
    {
        return static::valor('fe_entorno', 'beta');
    }

    public static function endpoint(): string
    {
        return self::ENDPOINTS[static::entorno()] ?? self::ENDPOINTS['beta'];
    }

    /** ¿Existe el archivo de certificado en la ruta indicada? */
    public static function certificadoExiste(): bool
    {
        $ruta = static::valor('fe_cert_path');

        return $ruta !== '' && $ruta !== null && is_file($ruta);
    }

    /**
     * Parametros normalizados que consume el transmisor SUNAT / Greenter.
     *
     * @return array<string, mixed>
     */
    public static function parametrosSunat(): array
    {
        $c = static::todas();

        return [
            'habilitada'       => $c['fe_habilitada'] === '1',
            'auto_emitir'      => $c['fe_auto_emitir'] === '1',
            'driver'           => $c['fe_driver'],
            'entorno'          => $c['fe_entorno'],
            'endpoint'         => self::ENDPOINTS[$c['fe_entorno']] ?? self::ENDPOINTS['beta'],
            'ruc'              => $c['fe_ruc'],
            'razon_social'     => $c['fe_razon_social'],
            'nombre_comercial' => $c['fe_nombre_comercial'],
            'direccion'        => $c['fe_direccion'],
            'ubigeo'           => $c['fe_ubigeo'],
            'departamento'     => $c['fe_departamento'],
            'provincia'        => $c['fe_provincia'],
            'distrito'         => $c['fe_distrito'],
            'usuario_sol'      => $c['fe_usuario_sol'],
            'clave_sol'        => $c['fe_clave_sol'],
            'cert_path'        => $c['fe_cert_path'],
            'cert_pass'        => $c['fe_cert_pass'],
        ];
    }
}
