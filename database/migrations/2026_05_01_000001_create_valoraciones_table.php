<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valoraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('localizacion_id')
                  ->constrained('localizaciones')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('puntuacion'); // 1-5
            $table->timestamps();

            // Un usuario solo puede valorar una vez cada spot
            $table->unique(['user_id', 'localizacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valoraciones');
    }
};
