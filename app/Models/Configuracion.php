<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Configuracion extends Model
{
    protected $table = 'configuraciones';

    protected $fillable = ['clave', 'valor'];

    public $timestamps = true;

    /** Valores por defecto del sistema. */
    public const DEFAULTS = [
        'nombre_clinica' => 'OdontoCRM',
        'nit' => '',
        'telefono' => '',
        'email' => '',
        'direccion' => '',
        'ciudad' => '',
        'moneda' => '$',
        'impuesto' => '0',
        'num_sillas' => '3',
        'horario' => 'Lunes a Viernes 08:00 - 18:00',
        'mensaje_recordatorio' => 'Le recordamos su cita en OdontoCRM. Por favor confirme su asistencia.',
    ];

    /** Cache en memoria durante el request. */
    protected static ?array $cache = null;

    /** Devuelve un valor de configuracion (con fallback a DEFAULTS). */
    public static function valor(string $clave, ?string $default = null): ?string
    {
        $todas = static::todas();

        return $todas[$clave] ?? $default ?? (self::DEFAULTS[$clave] ?? null);
    }

    /** Devuelve todas las configuraciones combinadas con los valores por defecto. */
    public static function todas(): array
    {
        if (static::$cache !== null) {
            return static::$cache;
        }

        $almacenadas = [];
        try {
            if (Schema::hasTable('configuraciones')) {
                $almacenadas = static::query()->pluck('valor', 'clave')->toArray();
            }
        } catch (\Throwable $e) {
            $almacenadas = [];
        }

        return static::$cache = array_merge(self::DEFAULTS, array_filter($almacenadas, fn ($v) => $v !== null));
    }

    /** Guarda un conjunto de pares clave => valor. */
    public static function guardar(array $pares): void
    {
        foreach ($pares as $clave => $valor) {
            static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
        }

        static::$cache = null;
    }
}
