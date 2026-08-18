<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    use HasFactory;

    protected $table = 'calificaciones';

    protected $fillable = [
        'alumno_id',
        'carga_horaria_id',
        'parcial_1',
        'parcial_2',
        'parcial_3',
        'promedio_final',
        'observaciones_parcial_1',
        'observaciones_parcial_2',
        'observaciones_parcial_3',
    ];

    protected function casts(): array
    {
        return [
            'parcial_1' => 'decimal:2',
            'parcial_2' => 'decimal:2',
            'parcial_3' => 'decimal:2',
            'promedio_final' => 'decimal:2',
        ];
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
