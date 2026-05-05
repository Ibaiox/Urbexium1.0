<?php
// ============================================================
// database/migrations/2026_05_03_000001_create_productos_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 8, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->enum('categoria', ['equipo', 'ropa', 'seguridad', 'accesorios'])->default('accesorios');
            $table->string('imagen')->nullable();        // path en storage
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
