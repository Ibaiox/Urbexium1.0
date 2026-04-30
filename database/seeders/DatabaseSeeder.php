<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolSeeder::class,      // 1. Roles (los necesita User)
            PaisSeeder::class,     // 2. Países y ciudades
            MaterialSeeder::class, // 3. Materiales recomendados
        ]);
    }
}
