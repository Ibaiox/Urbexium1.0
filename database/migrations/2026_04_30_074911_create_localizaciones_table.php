<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('localizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('ciudad_id')->constrained('ciudades')->restrictOnDelete();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->enum('dificultad', ['baja', 'media', 'alta'])->default('media');
            $table->string('estado')->nullable();
            $table->enum('verification_status', ['pendiente', 'verificada', 'rechazada', 'dudosa'])->default('pendiente');
            $table->boolean('visibility')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('deletion_requested')->default(false);
            $table->unsignedInteger('reports_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('localizaciones');
    }
};
