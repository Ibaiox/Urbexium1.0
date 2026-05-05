<?php
// ============================================================
// database/migrations/2026_05_03_000003_create_notificaciones_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('localizacion_id')->nullable()->constrained('localizaciones')->cascadeOnDelete();
            $table->enum('tipo', [
                'spot_verificado',
                'spot_rechazado',
                'spot_pendiente',
                'sancion',
                'aviso',
                'ban',
                'info',
            ])->default('info');
            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
