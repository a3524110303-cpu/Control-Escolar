<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CargaHoraria extends Model
{
    use HasFactory;

    protected $table = 'cargas_horarias';

    protected $fillable = [
        'grado',
        'grupo',
        'grupo_id',
        'docente_id',
        'materia_id',
        'periodo_id',
    ];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function periodo(): BelongsTo
    {
        return $this->belongsTo(Periodo::class);
    }

    public function grupoEscolar(): BelongsTo
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class);
    }
}
