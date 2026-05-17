<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Localizacion;
use App\Models\Ciudad;
use App\Models\Pais;
use App\Models\User;

/**
 * SpotSeeder — Lugares abandonados reales de España para urbex
 *
 * Seeder reducido: contiene únicamente las localizaciones para las que ya se han generado imágenes.
 *
 * Uso:
 *   php artisan db:seed --class=SpotSeeder
 *   (o añadir SpotSeeder al DatabaseSeeder)
 */
class SpotSeeder extends Seeder
{
    public function run(): void
    {
        // ── Obtener usuario administrador/moderador para asignar los spots ──
        $admin = User::whereHas('rol', fn($q) => $q->whereIn('nombre', ['administrador', 'moderador']))
            ->first();

        if (!$admin) {
            // Si no hay admin, usar el primer usuario disponible
            $admin = User::first();
        }

        if (!$admin) {
            $this->command->warn('No hay usuarios en la base de datos. Crea uno primero.');
            return;
        }

        // ── Asegurar que España y las ciudades necesarias existen ──
        $espana = Pais::firstOrCreate(['nombre' => 'España']);

        $ciudadesData = [
            'Bilbao'          => 'País Vasco',
            'Basauri'         => 'País Vasco',
            'Lemoiz'          => 'País Vasco',
            'Güeñes'          => 'País Vasco',
            'Gorliz'          => 'País Vasco',
            'Barakaldo'       => 'País Vasco',
            'Vitoria-Gasteiz' => 'País Vasco',
            'Barcelona'       => 'Cataluña',
        ];

        $ciudades = [];
        foreach ($ciudadesData as $nombre => $region) {
            $ciudades[$nombre] = Ciudad::firstOrCreate(
                ['nombre' => $nombre, 'pais_id' => $espana->id],
                ['region' => $region]
            );
        }

        // ── Definición de spots con imagen generada ──
        $spots = [
            [
                'nombre'      => 'Central Nuclear de Lemoiz',
                'ciudad'      => 'Lemoiz',
                'latitud'     => 43.4108,
                'longitud'    => -2.9897,
                'descripcion' => 'Quizás el lugar abandonado más emblemático del País Vasco. Dos reactores nucleares que nunca llegaron a funcionar, paralizados en los años 80 por protestas ecologistas y la presión de ETA. Sus miles de metros cúbicos de hormigón ocupan el equivalente a cinco campos de fútbol frente a la costa de Lemoiz. Los esqueletos de los reactores siguen en pie, creando una imagen post-apocalíptica única. El Gobierno Vasco estudia reconvertirlo en piscifactoría.',
                'dificultad'  => 'alta',
                'estado'      => 'Vigilado — nunca operativo',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Guantes', 'Equipo de primeros auxilios'],
                'imagen'      => '/images/spots/1.png',
            ],
            [
                'nombre'      => 'Harinera Grandes Molinos Vascos',
                'ciudad'      => 'Bilbao',
                'latitud'     => 43.2568,
                'longitud'    => -2.9521,
                'descripcion' => 'Imponente fábrica de harina construida en 1925 en el barrio de Zorroza, Bilbao. Cerró solo cuatro años después de su inauguración debido a las malas cosechas y el alza del precio del trigo. Declarada Bien de Interés Cultural en 2009 por su espectacular arquitectura industrial de hormigón armado. Destacan sus grandes silos en la fachada trasera y el montacargas interior. Una de las estampas industriales más reconocibles de Bilbao.',
                'dificultad'  => 'media',
                'estado'      => 'Abandonado — BIC desde 2009',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco'],
                'imagen'      => '/images/spots/2.png',
            ],
            [
                'nombre'      => 'Fábrica La Basconia (Basauri)',
                'ciudad'      => 'Basauri',
                'latitud'     => 43.2371,
                'longitud'    => -2.8933,
                'descripcion' => 'Una de las fábricas metalúrgicas más importantes de Bizkaia desde el siglo XIX hasta el XX. Ubicada en Basauri, a pocos kilómetros de Bilbao. Sus enormes naves oxidadas y la maquinaria abandonada ofrecen una experiencia urbex única. Fue motor económico de la región durante décadas antes de cerrar definitivamente. Especialmente impresionante la nave principal con su estructura de acero.',
                'dificultad'  => 'alta',
                'estado'      => 'En ruinas — acceso complejo',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Guantes'],
                'imagen'      => '/images/spots/3.png',
            ],
            [
                'nombre'      => 'Palacio de las Brujas (Hurtado de Amézaga)',
                'ciudad'      => 'Güeñes',
                'latitud'     => 43.1542,
                'longitud'    => -3.0771,
                'descripcion' => 'Palacio de estilo barroco del siglo XVIII abandonado en Güeñes, conocido popularmente como el "Palacio de las Brujas". Según la leyenda, los seis hijos del Marqués Baltasar Hurtado de Amézaga intentaron terminar las obras tras la muerte del padre, y todos fallecieron antes de ver el proyecto concluido. El misterio y la decadencia de sus paredes hacen de este lugar uno de los más inquietantes de Bizkaia. Rodeado de vegetación que ha comenzado a reclamarlo.',
                'dificultad'  => 'baja',
                'estado'      => 'Abandonado desde el s. XVIII',
                'materiales'  => ['Calzado resistente'],
                'imagen'      => '/images/spots/4.png',
            ],
            [
                'nombre'      => 'Búnker de Cabo Billano (Gorliz)',
                'ciudad'      => 'Gorliz',
                'latitud'     => 43.4296,
                'longitud'    => -2.9388,
                'descripcion' => 'Batería de costa de la Segunda Guerra Mundial junto al faro de Gorliz. Conserva puestos de tiro de hormigón, una red de galerías subterráneas y un viejo cañón en desuso. Construido en los años 40 como parte del sistema defensivo del litoral vasco. Vista espectacular del mar Cantábrico. De fácil acceso y perfecto para iniciarse en el urbex, aunque hay que respetar las estructuras históricas.',
                'dificultad'  => 'baja',
                'estado'      => 'Accesible — patrimonio militar',
                'materiales'  => ['Linterna', 'Calzado resistente'],
                'imagen'      => '/images/spots/5.png',
            ],
            [
                'nombre'      => 'Altos Hornos de Vizcaya (ruinas)',
                'ciudad'      => 'Barakaldo',
                'latitud'     => 43.2914,
                'longitud'    => -2.9876,
                'descripcion' => 'Restos de los legendarios Altos Hornos de Vizcaya en Barakaldo, símbolo de la industrialización vasca. Fundados en 1902 y cerrados en 1996, fueron durante décadas el mayor complejo siderúrgico de España. Aunque gran parte del recinto ha sido reconvertido (zona de Galindo), aún quedan estructuras abandonadas e históricas. El paisaje industrial que dejaron es referencia del patrimonio minero-industrial del País Vasco.',
                'dificultad'  => 'alta',
                'estado'      => 'Parcialmente demolido — zonas en ruinas',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Guantes'],
                'imagen'      => '/images/spots/6.png',
            ],
            [
                'nombre'      => 'Hospital del Tórax (Terrassa)',
                'ciudad'      => 'Barcelona',
                'latitud'     => 41.5601,
                'longitud'    => 2.0083,
                'descripcion' => 'Antiguo sanatorio antituberculoso de los años 30 en Terrassa (Barcelona), uno de los lugares abandonados más fotografiados de España. Enorme complejo de pabellones neocoloniales con jardines, capilla y salas de tratamiento. Cerrado en los años 90 y abandonado desde entonces. Conserva camas, equipamiento médico y una atmósfera inquietante. Conocido mundialmente en la comunidad urbex.',
                'dificultad'  => 'alta',
                'estado'      => 'Abandonado desde los 90 — vigilado',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Mascarilla'],
                'imagen'      => '/images/spots/7.png',
            ],
            [
                'nombre'      => 'Pueblo Fantasma de Salinas de Oro',
                'ciudad'      => 'Vitoria-Gasteiz',
                'latitud'     => 42.7412,
                'longitud'    => -1.9623,
                'descripcion' => 'Pequeño pueblo casi deshabitado en Navarra, con casas de piedra abandonadas y una iglesia románica sin techo. El éxodo rural de los años 60-70 vació prácticamente esta localidad. Las calles empedradas están cubiertas de hierba y algunas fachadas mantienen intactos los marcos de ventanas de madera. Un viaje en el tiempo a la España rural del siglo pasado.',
                'dificultad'  => 'baja',
                'estado'      => 'Semi-abandonado — 2-3 habitantes',
                'materiales'  => ['Calzado resistente'],
                'imagen'      => '/images/spots/8.png',
            ],
        ];

        // ── Obtener IDs de materiales ──
        $materialesDB = DB::table('materiales')->pluck('id', 'nombre');

        // ── Insertar spots ──
        $procesados = 0;
        foreach ($spots as $spotData) {
            $ciudadNombre = $spotData['ciudad'];

            if (!isset($ciudades[$ciudadNombre])) {
                $ciudades[$ciudadNombre] = Ciudad::firstOrCreate(
                    ['nombre' => $ciudadNombre, 'pais_id' => $espana->id],
                    ['region' => 'España']
                );
            }

            // Crear o reutilizar la localización si ya existe
            $spot = Localizacion::where('nombre', $spotData['nombre'])->first();

            if ($spot) {
                $this->command->line("  ⏭  Ya existe: {$spotData['nombre']} — se actualizará/asociará su imagen");
            } else {
                $spot = Localizacion::create([
                'user_id'             => $admin->id,
                'ciudad_id'           => $ciudades[$ciudadNombre]->id,
                'nombre'              => $spotData['nombre'],
                'descripcion'         => $spotData['descripcion'],
                'latitud'             => $spotData['latitud'],
                'longitud'            => $spotData['longitud'],
                'dificultad'          => $spotData['dificultad'],
                'estado'              => $spotData['estado'],
                'verification_status' => 'verificada',
                'visibility'          => true,
                'is_active'           => true,
                ]);
            }

            // Insertar o mantener imagen asociada en imagenes_localizacion
            if (!empty($spotData['imagen'])) {
                DB::table('imagenes_localizacion')->updateOrInsert(
                    [
                        'localizacion_id' => $spot->id,
                        'url'             => $spotData['imagen'],
                    ],
                    [
                        'user_id'    => $admin->id,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            // Asignar materiales si existen en la BD
            if (!empty($spotData['materiales'])) {
                $matIds = [];
                foreach ($spotData['materiales'] as $matNombre) {
                    if (isset($materialesDB[$matNombre])) {
                        $matIds[] = $materialesDB[$matNombre];
                    }
                }

                if (!empty($matIds)) {
                    $spot->materiales()->sync($matIds);
                }
            }

            $this->command->info("  ✓  Creado: {$spot->nombre}");
            $procesados++;
        }

        $this->command->newLine();
        $this->command->info("SpotSeeder completado: {$procesados} spots procesados con imagen.");
        $this->command->line('  → Coloca las imágenes en public/images/spots/ con los nombres indicados en el campo imagen.');
    }
}
