<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'curp',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo_electronico',
        'telefono',
        'direccion',
    ];

    public function cargasHorarias(): HasMany
    {
        return $this->hasMany(CargaHoraria::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'identificador', 'curp');
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
