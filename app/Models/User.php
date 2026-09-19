<?php

namespace App\Models;

use App\Models\Concerns\RegistraActividad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, RegistraActividad;

    /** Roles disponibles en el sistema. */
    public const ROLES = [
        'admin' => 'Administrador',
        'doctor' => 'Doctor / Odontologo',
        'recepcion' => 'Recepcion',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'telefono',
        'especialidad',
        'comision',
        'avatar',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'comision' => 'decimal:2',
        ];
    }

    /** Citas asignadas a este usuario como doctor. */
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'doctor_id');
    }

    /** Nombre legible del rol. */
    public function getRolNombreAttribute(): string
    {
        return self::ROLES[$this->rol] ?? ucfirst((string) $this->rol);
    }

    /** Iniciales para el avatar. */
    public function getInicialesAttribute(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name));
        $first = Str::substr($parts[0] ?? '', 0, 1);
        $last = count($parts) > 1 ? Str::substr(end($parts), 0, 1) : '';

        return Str::upper($first.$last);
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esDoctor(): bool
    {
        return $this->rol === 'doctor';
    }
}
