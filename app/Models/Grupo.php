<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'semestre', 'turno'];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }

    public function cargasHorarias(): HasMany
    {
        return $this->hasMany(CargaHoraria::class);
    }

    public function getEtiquetaAttribute(): string
    {
        return "{$this->semestre}° {$this->nombre} · {$this->turno}";
    }
}
