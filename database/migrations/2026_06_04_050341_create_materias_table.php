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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();

            // Nombre de la materia (ej. "Matemáticas I", "Química", "Programación")
            $table->string('nombre');

            // Semestre o grado al que corresponde (Primero, Segundo, Tercero...)
            $table->string('semestre');

            // Relación obligatoria: Cada materia pertenece a un Plan de Estudios específico
            // Si se borra un plan de estudios, el sistema restringirá el borrado si tiene materias asociadas
            $foreignId = $table->foreignId('plan_estudio_id')->constrained('planes_estudio')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
