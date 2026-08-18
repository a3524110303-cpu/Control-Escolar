<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_ciclo',
        'activo',
        'inicio_parcial_1',
        'fin_parcial_1',
        'inicio_parcial_2',
        'fin_parcial_2',
        'inicio_parcial_3',
        'fin_parcial_3',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'inicio_parcial_1' => 'date',
            'fin_parcial_1' => 'date',
            'inicio_parcial_2' => 'date',
            'fin_parcial_2' => 'date',
            'inicio_parcial_3' => 'date',
            'fin_parcial_3' => 'date',
        ];
    }

    public function cargasHorarias(): HasMany
    {
        return $this->hasMany(CargaHoraria::class);
    }
}
