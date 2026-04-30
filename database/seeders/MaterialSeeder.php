<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materiales = [
            [
                'nombre'      => 'Linterna',
                'descripcion' => 'Imprescindible para explorar zonas sin luz. Se recomienda llevar una de repuesto.',
            ],
            [
                'nombre'      => 'Casco',
                'descripcion' => 'Protección craneal ante derrumbes, vigas bajas o escombros.',
            ],
            [
                'nombre'      => 'Guantes',
                'descripcion' => 'Protegen las manos del polvo, cristales rotos y superficies contaminadas.',
            ],
            [
                'nombre'      => 'Mascarilla FFP2',
                'descripcion' => 'Filtra el polvo, el amianto y otros contaminantes presentes en edificios abandonados.',
            ],
            [
                'nombre'      => 'Botas de seguridad',
                'descripcion' => 'Calzado reforzado con puntera de acero y suela antipinchazos.',
            ],
            [
                'nombre'      => 'Ropa de abrigo',
                'descripcion' => 'Necesaria en localizaciones sin calefacción, especialmente en invierno.',
            ],
            [
                'nombre'      => 'Botiquín básico',
                'descripcion' => 'Vendas, desinfectante y tiritas para pequeños cortes o raspaduras.',
            ],
            [
                'nombre'      => 'Cuerda',
                'descripcion' => 'Útil para descensos o zonas con desniveles peligrosos.',
            ],
            [
                'nombre'      => 'Walkie-talkie',
                'descripcion' => 'Para comunicarse en interiores donde la cobertura móvil falla.',
            ],
            [
                'nombre'      => 'Cámara de fotos',
                'descripcion' => 'Para documentar la exploración. Se recomienda una con buena sensibilidad ISO.',
            ],
        ];

        foreach ($materiales as $material) {
            Material::firstOrCreate(
                ['nombre' => $material['nombre']],
                ['descripcion' => $material['descripcion']]
            );
        }
    }
}
