<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,      // 1. Roles (los necesita User)
            PaisSeeder::class,     // 2. Países y ciudades
            MaterialSeeder::class, // 3. Materiales recomendados
            ProductoSeeder::class,
        ]);

          $rolMod = Rol::where('nombre', 'moderador')->first();

        $mod = User::firstOrCreate(
            ['email' => 'moderadorUrbexium@gmail.com'],
            [
                'rol_id'   => $rolMod->id,
                'nombre'   => 'Moderador',
                'password' => Hash::make('12345678'),
                'baneado'  => false,
            ]
        );

        $this->command->info(
            $mod->wasRecentlyCreated
                ? ' Moderador creado:  moderadorUrbexium@gmail.com  /  12345678'
                : 'ℹ  Moderador ya existía, no se modificó.'
        );

        // ── Spots (necesita un usuario admin creado arriba) ───────────────
        $this->call([
            SpotSeeder::class,     // 5. Localizaciones
        ]);

    }
}
