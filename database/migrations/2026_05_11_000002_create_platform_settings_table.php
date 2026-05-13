<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->string('clave')->primary();
            $table->text('valor')->nullable();
            $table->string('tipo')->default('string'); // string, boolean, integer
            $table->string('descripcion')->nullable();
            $table->timestamps();
        });

        // Valores por defecto
        DB::table('platform_settings')->insert([
            ['clave' => 'modo_mantenimiento',    'valor' => 'false',  'tipo' => 'boolean', 'descripcion' => 'Activa el modo mantenimiento (bloquea acceso a usuarios)', 'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'registro_abierto',      'valor' => 'true',   'tipo' => 'boolean', 'descripcion' => 'Permite nuevos registros de usuarios',                     'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'limite_spots_usuario',  'valor' => '20',     'tipo' => 'integer', 'descripcion' => 'Número máximo de spots que puede crear un usuario',        'created_at' => now(), 'updated_at' => now()],
            ['clave' => 'mensaje_aviso_global',  'valor' => '',       'tipo' => 'string',  'descripcion' => 'Mensaje de aviso mostrado en la cabecera del sitio',       'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
