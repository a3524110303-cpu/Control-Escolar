<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    protected $fillable = ['carga_horaria_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'aula'];

    public function cargaHoraria(): BelongsTo
    {
        return $this->belongsTo(CargaHoraria::class);
    }
}
