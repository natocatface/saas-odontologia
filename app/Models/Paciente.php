<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Paciente extends Model implements AuthenticatableContract
{
    use AuthenticatableTrait, HasFactory, Notifiable, RegistraActividad;

    protected $table = 'pacientes';

    /** Antecedentes medicos frecuentes (campos estructurados). */
    public const ENFERMEDADES = [
        'diabetes' => 'Diabetes',
        'hipertension' => 'Hipertension',
        'cardiopatia' => 'Cardiopatia',
        'asma' => 'Asma',
        'epilepsia' => 'Epilepsia',
        'hepatitis' => 'Hepatitis',
        'vih' => 'VIH',
        'coagulacion' => 'Problemas de coagulacion',
        'anticoagulantes' => 'Toma anticoagulantes',
        'renal' => 'Enfermedad renal',
        'tiroides' => 'Tiroides',
        'cancer' => 'Cancer',
        'osteoporosis' => 'Osteoporosis',
        'embarazo' => 'Embarazo',
    ];

    public const HABITOS = [
        'tabaco' => 'Tabaco',
        'alcohol' => 'Alcohol',
        'bruxismo' => 'Bruxismo',
        'drogas' => 'Drogas',
    ];

    public const TIPOS_SANGRE = ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'telefono',
        'email',
        'fecha_nacimiento',
        'genero',
        'tipo_sangre',
        'password',
        'portal_activo',
        'direccion',
        'alergias',
        'enfermedades',
        'medicacion',
        'habitos',
        'antecedentes_notas',
        'observaciones',
        'odontograma',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'odontograma' => 'array',
            'enfermedades' => 'array',
            'habitos' => 'array',
            'activo' => 'boolean',
            'portal_activo' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function evoluciones(): HasMany
    {
        return $this->hasMany(Evolucion::class)->orderByDesc('fecha')->orderByDesc('id');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(Archivo::class)->orderByDesc('id');
    }

    public function consentimientos(): HasMany
    {
        return $this->hasMany(Consentimiento::class)->orderByDesc('id');
    }

    /** Nombres legibles de las enfermedades registradas. */
    public function getEnfermedadesNombresAttribute(): array
    {
        return collect($this->enfermedades ?? [])
            ->map(fn ($k) => self::ENFERMEDADES[$k] ?? $k)
            ->all();
    }

    /** Nombres legibles de los habitos registrados. */
    public function getHabitosNombresAttribute(): array
    {
        return collect($this->habitos ?? [])
            ->map(fn ($k) => self::HABITOS[$k] ?? $k)
            ->all();
    }

    public function presupuestos(): HasMany
    {
        return $this->hasMany(Presupuesto::class)->orderByDesc('fecha')->orderByDesc('id');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class)->orderByDesc('fecha')->orderByDesc('id');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    /** Total facturado: suma de presupuestos aprobados. */
    public function getTotalFacturadoAttribute(): float
    {
        return (float) $this->presupuestos->where('estado', 'aprobado')->sum('total');
    }

    /** Total cobrado al paciente. */
    public function getTotalPagadoAttribute(): float
    {
        return (float) $this->pagos->sum('monto');
    }

    /** Saldo pendiente del paciente (facturado - pagado, nunca negativo). */
    public function getSaldoAttribute(): float
    {
        return max($this->total_facturado - $this->total_pagado, 0);
    }

    public function getInicialesAttribute(): string
    {
        return Str::upper(Str::substr($this->nombre, 0, 1).Str::substr($this->apellido, 0, 1));
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }
}
