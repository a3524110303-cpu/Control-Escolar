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
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();

            // Conexión con el Login (Su NIA único)
            $table->string('nia')->unique();

            // Datos Personales
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable(); // Nullable por si no tiene segundo apellido
            $table->string('curp', 18)->unique();
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);

            // Control Administrativo y Académico
            // Aquí "jalamos" la tabla de planes de estudio que guardaste hace un momento:
            $table->foreignId('plan_estudio_id')->constrained('planes_estudio')->onDelete('restrict');

            $table->string('semestre_actual')->default('1');
            $table->string('grupo_actual', 2)->nullable();       // A, B, C...
            $table->enum('turno', ['Matutino', 'Vespertino']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
