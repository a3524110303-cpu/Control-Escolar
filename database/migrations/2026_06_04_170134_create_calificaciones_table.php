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
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();

            // Relaciones obligatorias
            // 1. ¿A qué alumno pertenece esta calificación?
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');

            // 2. ¿A qué materia, docente y periodo corresponde? (Lo unificamos mediante la carga horaria)
            $table->foreignId('carga_horaria_id')->constrained('cargas_horarias')->onDelete('cascade');

            // Calificaciones de los 3 Parciales (permiten decimales, ej. 8.5)
            // Son nullable porque al inicio del semestre el docente aún no las captura
            $table->decimal('parcial_1', 4, 2)->nullable();
            $table->decimal('parcial_2', 4, 2)->nullable();
            $table->decimal('parcial_3', 4, 2)->nullable();

            // Promedio final calculado automáticamente por el sistema
            $table->decimal('promedio_final', 4, 2)->nullable();

            // Observaciones cualitativas o de conducta por alumno
            $table->text('observaciones_parcial_1')->nullable();
            $table->text('observaciones_parcial_2')->nullable();
            $table->text('observaciones_parcial_3')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};
