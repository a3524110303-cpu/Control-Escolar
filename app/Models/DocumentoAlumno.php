<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentoAlumno extends Model
{
    protected $table = 'documentos_alumnos';

    protected $fillable = [
        'alumno_id',
        'tipo_documento_id',
        'ruta_archivo',
        'estado_revision',
        'observaciones',
        'revisado_por',
        'fecha_subida',
        'fecha_revision',
    ];

    protected function casts(): array
    {
        return ['fecha_subida' => 'datetime', 'fecha_revision' => 'datetime'];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }
}
