<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlanEstudio extends Model
{
    use HasFactory;

    protected $table = 'planes_estudio';

    protected $fillable = ['nombre', 'vigente'];

    protected function casts(): array
    {
        return ['vigente' => 'boolean'];
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }
}
