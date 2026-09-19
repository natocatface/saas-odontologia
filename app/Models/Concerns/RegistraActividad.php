<?php

namespace App\Models\Concerns;

use App\Models\Actividad;

/**
 * Registra automaticamente en la bitacora las altas, cambios y bajas
 * del modelo que use este trait.
 */
trait RegistraActividad
{
    public static function bootRegistraActividad(): void
    {
        static::created(fn ($modelo) => Actividad::registrar('creado', $modelo));
        static::updated(fn ($modelo) => Actividad::registrar('actualizado', $modelo));
        static::deleted(fn ($modelo) => Actividad::registrar('eliminado', $modelo));
    }
}
