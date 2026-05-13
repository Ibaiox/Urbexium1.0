<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_actividad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('localizacion_id')->nullable()->constrained('localizaciones')->cascadeOnDelete();
            $table->enum('tipo', [
                'vista',        // entró a ver el spot
                'favorito_add', // marcó como favorito
                'favorito_rem', // quitó de favoritos
                'spot_creado',  // creó un nuevo spot
                'spot_editado', // editó un spot
                'spot_borrado', // borró un spot
                'comentario',   // dejó un comentario
                'valoracion',   // valoró un spot
            ]);
            $table->string('descripcion');      // texto legible: "Visitaste Fábrica Abandonada"
            $table->string('spot_nombre')->nullable(); // guardar el nombre por si el spot se borra
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['user_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_actividad');
    }
};
