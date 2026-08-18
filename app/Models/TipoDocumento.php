<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoDocumento extends Model
{
    protected $table = 'tipos_documentos';

    protected $fillable = ['nombre', 'obligatorio'];

    protected function casts(): array
    {
        return ['obligatorio' => 'boolean'];
    }

    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoAlumno::class);
    }
}
