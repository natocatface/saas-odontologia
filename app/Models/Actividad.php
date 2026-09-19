<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Actividad extends Model
{
    protected $table = 'actividades';

    public const ACCIONES = [
        'creado' => 'Creo',
        'actualizado' => 'Actualizo',
        'eliminado' => 'Elimino',
        'acceso' => 'Inicio sesion',
        'salida' => 'Cerro sesion',
    ];

    protected $fillable = [
        'user_id',
        'accion',
        'modulo',
        'modelo_id',
        'descripcion',
        'ip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Registra una entrada en la bitacora de forma segura. */
    public static function registrar(string $accion, ?Model $modelo = null, ?string $descripcion = null): void
    {
        try {
            if (! Schema::hasTable('actividades')) {
                return;
            }

            $modulo = null;
            $modeloId = null;

            if ($modelo) {
                $modulo = class_basename($modelo);
                $modeloId = $modelo->getKey();

                if (! $descripcion) {
                    foreach (['nombre_completo', 'codigo', 'nombre', 'name', 'motivo'] as $attr) {
                        $val = $modelo->{$attr} ?? null;
                        if (! empty($val)) {
                            $descripcion = (string) $val;
                            break;
                        }
                    }
                }
            }

            static::create([
                'user_id' => auth()->id(),
                'accion' => $accion,
                'modulo' => $modulo,
                'modelo_id' => $modeloId,
                'descripcion' => $descripcion,
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // La auditoria nunca debe interrumpir la operacion principal.
        }
    }

    public function getAccionNombreAttribute(): string
    {
        return self::ACCIONES[$this->accion] ?? ucfirst((string) $this->accion);
    }
}
