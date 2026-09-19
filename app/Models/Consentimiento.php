<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consentimiento extends Model
{
    use HasFactory, RegistraActividad;

    protected $table = 'consentimientos';

    public const TIPOS = [
        'general' => 'Consentimiento informado general',
        'cirugia' => 'Cirugia / Extraccion dental',
        'endodoncia' => 'Tratamiento de endodoncia',
        'ortodoncia' => 'Tratamiento de ortodoncia',
        'implante' => 'Implante dental',
        'blanqueamiento' => 'Blanqueamiento dental',
        'anestesia' => 'Administracion de anestesia',
    ];

    /** Texto base de cada tipo de consentimiento. */
    public const PLANTILLAS = [
        'general' => 'Declaro que he sido informado(a) de forma clara y comprensible sobre el diagnostico, el plan de tratamiento propuesto, sus alternativas, beneficios y posibles riesgos o complicaciones. He podido realizar todas las preguntas necesarias y han sido respondidas satisfactoriamente. Autorizo de manera libre y voluntaria al profesional y a su equipo a realizar los procedimientos odontologicos acordados.',
        'cirugia' => 'Autorizo la realizacion del procedimiento quirurgico / extraccion dental indicado. Se me ha informado sobre los riesgos asociados (dolor, inflamacion, sangrado, infeccion, lesion nerviosa temporal o permanente, entre otros) y las indicaciones postoperatorias. Comprendo la naturaleza del procedimiento y otorgo mi consentimiento.',
        'endodoncia' => 'Autorizo el tratamiento de endodoncia (tratamiento de conductos). Se me ha explicado que el objetivo es conservar la pieza dental y que existen riesgos como fractura, persistencia de molestias o la eventual necesidad de retratamiento o extraccion. Acepto el procedimiento.',
        'ortodoncia' => 'Autorizo el tratamiento de ortodoncia. Comprendo la duracion estimada, la importancia de la higiene y de asistir a los controles, asi como los posibles riesgos (descalcificacion, reabsorcion radicular, molestias). Me comprometo a seguir las indicaciones del profesional.',
        'implante' => 'Autorizo la colocacion de implante(s) dental(es). Se me ha informado sobre el procedimiento, los cuidados necesarios y los posibles riesgos (rechazo, infeccion, falla de osteointegracion). Otorgo mi consentimiento.',
        'blanqueamiento' => 'Autorizo el procedimiento de blanqueamiento dental. Comprendo que puede producir sensibilidad temporal y que los resultados varian segun cada paciente. Acepto el tratamiento.',
        'anestesia' => 'Autorizo la administracion de anestesia local para el procedimiento odontologico. He informado mis antecedentes medicos y alergias conocidas. Comprendo los posibles efectos secundarios.',
    ];

    protected $fillable = [
        'paciente_id',
        'user_id',
        'tipo',
        'titulo',
        'contenido',
        'firmado',
        'fecha_firma',
        'firmante',
    ];

    protected function casts(): array
    {
        return [
            'firmado' => 'boolean',
            'fecha_firma' => 'date',
        ];
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTipoNombreAttribute(): string
    {
        return self::TIPOS[$this->tipo] ?? ucfirst((string) $this->tipo);
    }
}
