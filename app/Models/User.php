<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['Administrador', 'Director', 'Docente', 'Alumno'];

    protected $fillable = [
        'identificador',
        'email_recuperacion',
        'password',
        'rol',
        'estatus',
        'primer_ingreso',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'estatus' => 'boolean',
            'primer_ingreso' => 'boolean',
        ];
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class, 'nia', 'identificador');
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class, 'curp', 'identificador');
    }

    public function hasAnyRole(string ...$roles): bool
    {
        return in_array($this->rol, $roles, true);
    }

    public function getDisplayNameAttribute(): string
    {
        $person = $this->relationLoaded('docente') ? $this->docente : null;
        $person ??= $this->relationLoaded('alumno') ? $this->alumno : null;

        return $person?->nombre_completo ?? $this->identificador;
    }

    public function getEmailForPasswordReset(): string
    {
        return $this->email_recuperacion;
    }

    public function routeNotificationForMail(mixed $notification): string
    {
        return $this->email_recuperacion;
    }
}
