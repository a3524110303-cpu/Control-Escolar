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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();

            // Nombre del ciclo (ej. "Semestre A - 2026", "Semestre B - 2026")
            $table->string('nombre_ciclo');
            $table->boolean('activo')->default(true); // Solo un periodo puede estar activo a la vez

            // Fechas límite para el Parcial 1
            $table->date('inicio_parcial_1');
            $table->date('fin_parcial_1');

            // Fechas límite para el Parcial 2
            $table->date('inicio_parcial_2');
            $table->date('fin_parcial_2');

            // Fechas límite para el Parcial 3
            $table->date('inicio_parcial_3');
            $table->date('fin_parcial_3');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
