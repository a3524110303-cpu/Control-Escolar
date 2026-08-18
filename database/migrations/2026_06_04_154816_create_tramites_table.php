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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();

            // Relación obligatoria: ¿De qué alumno es este trámite?
            // Si se borra el alumno de la base de datos, se borran sus trámites en cascada
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');

            // Ruta del archivo PDF guardado de forma segura en el servidor
            $table->string('ruta_pdf');

            // Control de Estatus del trámite mediante un texto corto (string)
            // Valores posibles: 'Pendiente', 'Aceptado', 'Rechazado'
            $table->string('estatus')->default('Pendiente');

            // Contenedor de texto para las observaciones de la administración si es rechazado
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tramites');
    }
};
