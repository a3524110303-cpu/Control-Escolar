<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->unique(['alumno_id', 'carga_horaria_id', 'fecha'], 'asistencia_diaria_unique');
        });

        Schema::table('calificaciones', function (Blueprint $table) {
            $table->unique(['alumno_id', 'carga_horaria_id'], 'calificacion_alumno_carga_unique');
        });
    }

    public function down(): void
    {
        Schema::table('asistencias', function (Blueprint $table) {
            $table->dropUnique('asistencia_diaria_unique');
        });

        Schema::table('calificaciones', function (Blueprint $table) {
            $table->dropUnique('calificacion_alumno_carga_unique');
        });
    }
};
