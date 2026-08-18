<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nia',
        'curp',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'fecha_nacimiento',
        'genero',
        'plan_estudio_id',
        'grupo_id',
        'semestre_actual',
        'grupo_actual',
        'turno',
        'correo_electronico',
    ];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    public function planEstudio(): BelongsTo
    {
        return $this->belongsTo(PlanEstudio::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function tramites(): HasMany
    {
        return $this->hasMany(Tramite::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoAlumno::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'identificador', 'nia');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->nombre,
            $this->apellido_paterno,
            $this->apellido_materno,
        ])));
    }
}
