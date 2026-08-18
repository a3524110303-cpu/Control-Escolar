<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        Schema::create('tipos_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->boolean('obligatorio')->default(false);
            $table->timestamps();
        });

        Schema::create('documentos_alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('tipo_documento_id')->constrained('tipos_documentos')->restrictOnDelete();
            $table->string('ruta_archivo');
            $table->enum('estado_revision', ['pendiente', 'aprobado', 'rechazado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_subida')->useCurrent();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
        });

        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carga_horaria_id')->constrained('cargas_horarias')->cascadeOnDelete();
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('aula', 50)->default('AULA GENÉRICA');
            $table->timestamps();
        });

        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 150);
            $table->text('contenido');
            $table->foreignId('publicado_por')->constrained('users')->cascadeOnDelete();
            $table->enum('destinatario', ['todos', 'docentes', 'alumnos'])->default('todos');
            $table->timestamp('fecha_publicacion')->useCurrent();
            $table->timestamps();
        });

        Schema::create('bitacora_actividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('accion');
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_actividades');
        Schema::dropIfExists('avisos');
        Schema::dropIfExists('horarios');
        Schema::dropIfExists('documentos_alumnos');
        Schema::dropIfExists('tipos_documentos');
        Schema::dropIfExists('roles');
    }
};
