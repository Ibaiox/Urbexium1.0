<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [

            // ── EQUIPO ────────────────────────────────────────────────────
            [
                'nombre'      => 'Linterna táctica LED 1000lm',
                'descripcion' => 'Linterna de alta potencia con 3 modos de iluminación, resistente al agua IPX6. Imprescindible para exploración nocturna.',
                'precio'      => 34.99,
                'stock'       => 25,
                'categoria'   => 'equipo',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Cámara de acción 4K Urbex Edition',
                'descripcion' => 'Cámara compacta con carcasa antipolvo, gran angular 170° y estabilización de imagen. Perfecta para documentar tus exploraciones.',
                'precio'      => 89.99,
                'stock'       => 10,
                'categoria'   => 'equipo',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Kit de herramientas multiusos 18 en 1',
                'descripcion' => 'Multiherramienta compacta de acero inoxidable con alicates, cuchillo, destornilladores y más. Funda de cuero incluida.',
                'precio'      => 27.50,
                'stock'       => 40,
                'categoria'   => 'equipo',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Walkie-talkie 5W rango 8km',
                'descripcion' => 'Par de walkies con batería de larga duración, 16 canales y función VOX. Ideales para explorar en grupo.',
                'precio'      => 49.99,
                'stock'       => 15,
                'categoria'   => 'equipo',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Mochila táctica 45L',
                'descripcion' => 'Mochila con sistema MOLLE, compartimentos organizadores, asa de carga rápida y material resistente al desgarro.',
                'precio'      => 64.99,
                'stock'       => 20,
                'categoria'   => 'equipo',
                'activo'      => true,
            ],

            // ── SEGURIDAD ─────────────────────────────────────────────────
            [
                'nombre'      => 'Mascarilla FFP3 con válvula (pack 5)',
                'descripcion' => 'Mascarillas de alta filtración para ambientes con polvo, amianto y partículas finas. Certificación CE.',
                'precio'      => 18.99,
                'stock'       => 60,
                'categoria'   => 'seguridad',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Casco de seguridad ajustable',
                'descripcion' => 'Casco homologado EN 397 con suspensión de 6 puntos y ala completa. Ligero y cómodo para largas sesiones.',
                'precio'      => 22.00,
                'stock'       => 35,
                'categoria'   => 'seguridad',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Guantes anticorte nivel 5',
                'descripcion' => 'Guantes de protección contra cortes con revestimiento de nitrilo. Tacto sensible y agarre en superficies húmedas.',
                'precio'      => 14.50,
                'stock'       => 50,
                'categoria'   => 'seguridad',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Silbato de emergencia ultrasonido',
                'descripcion' => 'Silbato de 120dB audible a más de 1km. Sin bola interior para funcionar en cualquier condición climática.',
                'precio'      => 6.99,
                'stock'       => 100,
                'categoria'   => 'seguridad',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Kit de primeros auxilios compacto',
                'descripcion' => 'Botiquín con 42 elementos: vendas, gasas, antiséptico, tijeras, pinzas y manta de emergencia. Bolsa impermeable.',
                'precio'      => 19.99,
                'stock'       => 30,
                'categoria'   => 'seguridad',
                'activo'      => true,
            ],

            // ── ROPA ──────────────────────────────────────────────────────
            [
                'nombre'      => 'Pantalón táctico cargo Urbexium',
                'descripcion' => 'Pantalón con 8 bolsillos, tejido ripstop resistente a roces y rodilleras acolchadas extraíbles. Disponible en negro y verde oliva.',
                'precio'      => 54.99,
                'stock'       => 25,
                'categoria'   => 'ropa',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Sudadera técnica con capucha',
                'descripcion' => 'Sudadera slim fit con tejido transpirable, bolsillo frontal y logo bordado Urbexium. Perfecta para exploración urbana.',
                'precio'      => 39.99,
                'stock'       => 30,
                'categoria'   => 'ropa',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Camiseta de manga larga Dryfit',
                'descripcion' => 'Camiseta de compresión con tejido que elimina la humedad y protección UV50+. Ideal bajo el equipo de seguridad.',
                'precio'      => 24.99,
                'stock'       => 45,
                'categoria'   => 'ropa',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Botas de exploración impermeables',
                'descripcion' => 'Botas con membrana impermeable, suela antideslizante Vibram y puntera reforzada. Certificación S1P.',
                'precio'      => 99.99,
                'stock'       => 18,
                'categoria'   => 'ropa',
                'activo'      => true,
            ],

            // ── ACCESORIOS ────────────────────────────────────────────────
            [
                'nombre'      => 'Parche bordado Urbexium (pack 3)',
                'descripcion' => 'Pack de 3 parches termoadhesivos y cosibles con el logo Urbexium, mapa y calavera explorador.',
                'precio'      => 9.99,
                'stock'       => 80,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Powerbank solar 20000mAh',
                'descripcion' => 'Batería externa con panel solar integrado, 2 puertos USB-A y 1 USB-C. Carga inalámbrica Qi incluida.',
                'precio'      => 44.99,
                'stock'       => 22,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Cordino paracord 10m',
                'descripcion' => 'Cuerda de paracord 550 trenzada de 7 hilos internos. Resistencia de 250kg. Múltiples usos en campo.',
                'precio'      => 7.99,
                'stock'       => 90,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Cuaderno impermeable A5',
                'descripcion' => 'Libreta con páginas resistentes al agua, tapa dura y bolígrafo de barro incluido. Para tus notas en cualquier condición.',
                'precio'      => 12.99,
                'stock'       => 55,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Stickers pack Urbexium (20 uds)',
                'descripcion' => 'Pack de 20 stickers vinilo premium resistentes al agua con diseños exclusivos de la comunidad Urbexium.',
                'precio'      => 5.99,
                'stock'       => 120,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
            [
                'nombre'      => 'Brújula de precisión militar',
                'descripcion' => 'Brújula líquida con limbo graduado, espejo de señalización y clinómetro. Funciona sin batería en cualquier entorno.',
                'precio'      => 29.99,
                'stock'       => 28,
                'categoria'   => 'accesorios',
                'activo'      => true,
            ],
        ];

        foreach ($productos as $data) {
            Producto::firstOrCreate(
                ['nombre' => $data['nombre']],
                $data
            );
        }

        $this->command->info('✅ ' . count($productos) . ' productos creados correctamente.');
    }
}
