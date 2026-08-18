<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tramite extends Model
{
    use HasFactory;

    protected $fillable = ['alumno_id', 'ruta_pdf', 'estatus', 'observaciones'];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }
}
