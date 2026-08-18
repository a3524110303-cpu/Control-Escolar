<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20);
            $table->unsignedTinyInteger('semestre');
            $table->enum('turno', ['Matutino', 'Vespertino']);
            $table->timestamps();

            $table->unique(['nombre', 'semestre', 'turno']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
