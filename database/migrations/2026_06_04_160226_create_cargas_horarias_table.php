<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cargas_horarias', function (Blueprint $table) {
            $table->id();

            // Grado y Grupo (ej. "1", "A" / "3", "B")
            $table->string('grado');
            $table->string('grupo');

            // Relaciones obligatorias (Llaves foráneas)
            // 1. ¿Qué docente imparte la clase?
            $table->foreignId('docente_id')->constrained('docentes')->onDelete('restrict');

            // 2. ¿Qué materia se está impartiendo?
            $table->foreignId('materia_id')->constrained('materias')->onDelete('restrict');

            // 3. ¿A qué ciclo/periodo escolar pertenece esta carga?
            $table->foreignId('periodo_id')->constrained('periodos')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargas_horarias');
    }
};
