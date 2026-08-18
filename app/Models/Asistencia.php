<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $fillable = ['alumno_id', 'carga_horaria_id', 'fecha', 'estatus'];

    protected function casts(): array
    {
        return ['fecha' => 'date'];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function cargaHoraria(): BelongsTo
    {
        return $this->belongsTo(CargaHoraria::class);
    }
}
