<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('accion');           // 'ban_usuario', 'aprobar_spot', 'rechazar_spot', 'resolver_reporte', 'cambiar_rol', etc.
            $table->string('entidad')->nullable(); // 'usuario', 'spot', 'reporte'
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('descripcion');      // Texto legible: "Baneó al usuario Juan García"
            $table->string('ip')->nullable();
            $table->timestamps();

            $table->index(['admin_id', 'created_at']);
            $table->index('accion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
