<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['administrador', 'moderador', 'usuario'];

        foreach ($roles as $nombre) {
            Rol::firstOrCreate(['nombre' => $nombre]);
        }
    }
}
