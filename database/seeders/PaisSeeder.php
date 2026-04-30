<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;
use App\Models\Ciudad;

class PaisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'España' => [
                ['nombre' => 'Madrid',    'region' => 'Comunidad de Madrid'],
                ['nombre' => 'Barcelona', 'region' => 'Cataluña'],
                ['nombre' => 'Valencia',  'region' => 'Comunidad Valenciana'],
                ['nombre' => 'Sevilla',   'region' => 'Andalucía'],
                ['nombre' => 'Bilbao',    'region' => 'País Vasco'],
                ['nombre' => 'Zaragoza',  'region' => 'Aragón'],
                ['nombre' => 'Málaga',    'region' => 'Andalucía'],
                ['nombre' => 'Murcia',    'region' => 'Región de Murcia'],
            ],
            'Francia' => [
                ['nombre' => 'París',     'region' => 'Île-de-France'],
                ['nombre' => 'Lyon',      'region' => 'Auvergne-Rhône-Alpes'],
                ['nombre' => 'Marsella',  'region' => 'Provenza-Alpes-Costa Azul'],
                ['nombre' => 'Toulouse',  'region' => 'Occitania'],
                ['nombre' => 'Burdeos',   'region' => 'Nueva Aquitania'],
                ['nombre' => 'Lille',     'region' => 'Altos de Francia'],
                ['nombre' => 'Estrasburgo', 'region' => 'Grand Est'],
            ],
        ];

        foreach ($data as $nombrePais => $ciudades) {
            $pais = Pais::firstOrCreate(['nombre' => $nombrePais]);

            foreach ($ciudades as $ciudad) {
                Ciudad::firstOrCreate(
                    ['nombre' => $ciudad['nombre'], 'pais_id' => $pais->id],
                    ['region' => $ciudad['region']]
                );
            }
        }
    }
}
