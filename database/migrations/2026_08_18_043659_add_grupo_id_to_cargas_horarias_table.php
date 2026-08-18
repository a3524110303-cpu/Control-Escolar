<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargas_horarias', function (Blueprint $table) {
            $table->foreignId('grupo_id')->nullable()->after('grupo')
                ->constrained('grupos')->nullOnDelete();
            $table->unique(
                ['docente_id', 'materia_id', 'periodo_id', 'grupo_id'],
                'cargas_asignacion_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('cargas_horarias', function (Blueprint $table) {
            $table->dropUnique('cargas_asignacion_unique');
            $table->dropConstrainedForeignId('grupo_id');
        });
    }
};
