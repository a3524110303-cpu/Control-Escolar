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
        Schema::create('planes_estudio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej. "Plan de Estudios 2018", "Plan de Estudios 2023"
            $table->boolean('vigente')->default(true); // true = Vigente, false = Obsoleto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_estudio');
    }
};
