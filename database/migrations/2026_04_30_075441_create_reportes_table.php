<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('localizacion_id')->constrained('localizaciones')->cascadeOnDelete();
            $table->enum('motivo', [
                'ubicación falsa',
                'acceso sellado',
                'demolido',
                'peligroso',
                'spam',
                'coordenadas erróneas',
            ]);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['pendiente', 'revisado', 'rechazado', 'resuelto'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
