<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'semestre', 'plan_estudio_id'];

    public function planEstudio(): BelongsTo
    {
        return $this->belongsTo(PlanEstudio::class);
    }

    public function cargasHorarias(): HasMany
    {
        return $this->hasMany(CargaHoraria::class);
    }
}
