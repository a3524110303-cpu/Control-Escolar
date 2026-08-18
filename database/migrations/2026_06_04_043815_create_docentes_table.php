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
        Schema::create('docentes', function (Blueprint $table) {
            $table->id();

            // Identificador único de acceso para Docentes y Administradores
            $table->string('curp', 18)->unique();

            // Datos Personales
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable(); // Nullable por si no tiene segundo apellido

            // Contacto Obligatorio (Clave para la recuperación de contraseña por CURP)
            $table->string('correo_electronico')->unique();
            $table->string('telefono', 15)->nullable();
            $table->string('direccion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
