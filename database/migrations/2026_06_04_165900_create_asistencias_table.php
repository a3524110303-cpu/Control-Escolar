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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();

            // Relaciones obligatorias
            // 1. ¿A qué alumno se le está tomando asistencia?
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');

            // 2. ¿A qué clase/carga horaria pertenece esta asistencia?
            $table->foreignId('carga_horaria_id')->constrained('cargas_horarias')->onDelete('cascade');

            // Fecha exacta del pase de lista
            $table->date('fecha');

            // Estatus de la asistencia: 'Asistencia', 'Falta', 'Justificado'
            $table->string('estatus');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
