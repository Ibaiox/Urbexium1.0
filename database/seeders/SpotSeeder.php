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
 * Fuentes: bilbaosecreto.com, carte-urbex.com, abandonedspain.com
 * Muchos de estos sitios son conocidos y están documentados públicamente.
 * Respeta siempre las leyes locales y la propiedad privada.
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

        // ── Asegurar que España y las ciudades existen ──
        $espana = Pais::firstOrCreate(['nombre' => 'España']);

        $ciudadesData = [
            // Ciudad                    => región
            'Bilbao'                    => 'País Vasco',
            'Basauri'                   => 'País Vasco',
            'Lemoiz'                    => 'País Vasco',
            'Abadiño'                   => 'País Vasco',
            'Güeñes'                    => 'País Vasco',
            'Gorliz'                    => 'País Vasco',
            'Sopelana'                  => 'País Vasco',
            'Getxo'                     => 'País Vasco',
            'Barakaldo'                 => 'País Vasco',
            'Vitoria-Gasteiz'           => 'País Vasco',
            'Zaragoza'                  => 'Aragón',
            'Toledo'                    => 'Castilla-La Mancha',
            'Segovia'                   => 'Castilla y León',
            'Madrid'                    => 'Comunidad de Madrid',
            'Barcelona'                 => 'Cataluña',
            'Valencia'                  => 'Comunitat Valenciana',
            'Sevilla'                   => 'Andalucía',
        ];

        $ciudades = [];
        foreach ($ciudadesData as $nombre => $region) {
            $ciudades[$nombre] = Ciudad::firstOrCreate(
                ['nombre' => $nombre, 'pais_id' => $espana->id],
                ['region' => $region]
            );
        }

        // ── Definición de los spots ──
        $spots = [

            // ══════════════════════════════════════════
            //  BILBAO / BIZKAIA (zona principal)
            // ══════════════════════════════════════════

            [
                'nombre'      => 'Harinera Grandes Molinos Vascos',
                'ciudad'      => 'Bilbao',
                'latitud'     => 43.2568,
                'longitud'    => -2.9521,
                'descripcion' => 'Imponente fábrica de harina construida en 1925 en el barrio de Zorroza, Bilbao. Cerró solo cuatro años después de su inauguración debido a las malas cosechas y el alza del precio del trigo. Declarada Bien de Interés Cultural en 2009 por su espectacular arquitectura industrial de hormigón armado. Destacan sus grandes silos en la fachada trasera y el montacargas interior. Una de las estampas industriales más reconocibles de Bilbao.',
                'dificultad'  => 'media',
                'estado'      => 'Abandonado — BIC desde 2009',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco'],
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
            ],
            [
                'nombre'      => 'Central Nuclear de Lemoiz',
                'ciudad'      => 'Lemoiz',
                'latitud'     => 43.4108,
                'longitud'    => -2.9897,
                'descripcion' => 'Quizás el lugar abandonado más emblemático del País Vasco. Dos reactores nucleares que nunca llegaron a funcionar, paralizados en los años 80 por protestas ecologistas y la presión de ETA. Sus miles de metros cúbicos de hormigón ocupan el equivalente a cinco campos de fútbol frente a la costa de Lemoiz. Los esqueletos de los reactores siguen en pie, creando una imagen post-apocalíptica única. El Gobierno Vasco estudia reconvertirlo en piscifactoría.',
                'dificultad'  => 'alta',
                'estado'      => 'Vigilado — nunca operativo',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Guantes', 'Equipo de primeros auxilios'],
            ],
            [
                'nombre'      => 'Cantera de Atxarte',
                'ciudad'      => 'Abadiño',
                'latitud'     => 43.0988,
                'longitud'    => -2.6312,
                'descripcion' => 'Cantera y cementera abandonada en el paraje natural de Atxarte, dentro del Parque Natural de Urkiola. Cesó su actividad definitivamente en 1995 tras una explotación de décadas. Entre robles y hayas se encuentran las infraestructuras de hormigón, camiones oxidados y maquinaria pesada en descomposición. Un contraste impresionante entre la naturaleza que lo recupera y los restos industriales. Entorno especialmente fotogénico.',
                'dificultad'  => 'media',
                'estado'      => 'Abandonado desde 1995',
                'materiales'  => ['Calzado resistente', 'Linterna'],
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
            ],
            [
                'nombre'      => 'Búnker de Sopelana',
                'ciudad'      => 'Sopelana',
                'latitud'     => 43.3884,
                'longitud'    => -2.9883,
                'descripcion' => 'Búnker de hormigón de la Guerra Civil situado frente a la playa de Sopelana, en un promontorio con vistas impresionantes al Cantábrico. Forma parte de la línea defensiva costera del País Vasco. Espectacular por su ubicación: el contraste entre la playa, el mar y la estructura militar oxidada es único. Muy frecuentado por fotógrafos al atardecer. Acceso por la playa de Atxabiribil.',
                'dificultad'  => 'baja',
                'estado'      => 'Accesible — frente a la playa',
                'materiales'  => ['Calzado resistente'],
            ],
            [
                'nombre'      => 'Punta Lucero — Baterías Costeras',
                'ciudad'      => 'Getxo',
                'latitud'     => 43.3847,
                'longitud'    => -3.0512,
                'descripcion' => 'Complejo de baterías costeras en Punta Lucero, zona estratégica desde el siglo XVI y reforzada durante la Segunda Guerra Mundial. Las instalaciones militares abandonadas ofrecen una vista panorámica extraordinaria de la bocana de la ría de Bilbao y el Cantábrico. Galerías subterráneas, fortines y cañones oxidados conviven con una naturaleza salvaje. Atardecer icónico desde este punto.',
                'dificultad'  => 'media',
                'estado'      => 'Acceso restringido — zona militar histórica',
                'materiales'  => ['Linterna', 'Calzado resistente'],
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
            ],
            [
                'nombre'      => 'Fábrica abandonada Ugao-Miraballes',
                'ciudad'      => 'Bilbao',
                'latitud'     => 43.1732,
                'longitud'    => -2.8743,
                'descripcion' => 'Antigua nave industrial en la zona minera del interior de Bizkaia. Ejemplo característico de la industria auxiliar que florecía alrededor de la minería del hierro en el siglo XIX. El edificio conserva maquinaria original, estructuras de madera en avanzado estado de deterioro y grafitis artísticos. Entorno verde que contrasta con el abandono interior.',
                'dificultad'  => 'media',
                'estado'      => 'Abandonado — estructura inestable',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco'],
            ],

            // ══════════════════════════════════════════
            //  RESTO DE ESPAÑA
            // ══════════════════════════════════════════

            [
                'nombre'      => 'Hospital del Tórax (Terrassa)',
                'ciudad'      => 'Barcelona',
                'latitud'     => 41.5601,
                'longitud'    => 2.0083,
                'descripcion' => 'Antiguo sanatorio antituberculoso de los años 30 en Terrassa (Barcelona), uno de los lugares abandonados más fotografiados de España. Enorme complejo de pabellones neocoloniales con jardines, capilla y salas de tratamiento. Cerrado en los años 90 y abandonado desde entonces. Conserva camas, equipamiento médico y una atmósfera inquietante. Conocido mundialmente en la comunidad urbex.',
                'dificultad'  => 'alta',
                'estado'      => 'Abandonado desde los 90 — vigilado',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Mascarilla'],
            ],
            [
                'nombre'      => 'Parque de Atracciones de Zaragoza',
                'ciudad'      => 'Zaragoza',
                'latitud'     => 41.6734,
                'longitud'    => -0.9302,
                'descripcion' => 'Antiguo parque de atracciones abandonado a las afueras de Zaragoza. Atracciones oxidadas, carritos de montaña rusa invadidos por la vegetación y cabinas pintadas por el tiempo crean una estética post-apocalíptica muy buscada por los fotógrafos urbex. Las atracciones permanecen en pie como fantasmas de un pasado de diversión. Especialmente impactante en días de niebla.',
                'dificultad'  => 'media',
                'estado'      => 'Cerrado — estructura deteriorada',
                'materiales'  => ['Calzado resistente', 'Linterna'],
            ],
            [
                'nombre'      => 'Ciudad del Aire (Alcalá de Henares)',
                'ciudad'      => 'Madrid',
                'latitud'     => 40.5100,
                'longitud'    => -3.2850,
                'descripcion' => 'Antigua base militar de la Fuerza Aérea Española en Alcalá de Henares, abandonada tras el cierre del aeródromo. Enormes hangares de aviación, barracones militares, pistas de aterrizaje cubiertas de maleza y una torre de control inoperativa. Uno de los mayores complejos militares abandonados de España. Muy codiciado por los exploradores urbanos por la escala de sus instalaciones.',
                'dificultad'  => 'alta',
                'estado'      => 'Militar abandonado — vigilancia esporádica',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco', 'Guantes'],
            ],
            [
                'nombre'      => 'Manicomio de Conxo (Santiago)',
                'ciudad'      => 'Santiago de Compostela',
                'latitud'     => 42.8570,
                'longitud'    => -8.5550,
                'descripcion' => 'Antiguo hospital psiquiátrico de Santiago de Compostela, parcialmente abandonado. Edificio del siglo XIX con jardines monumentales, capilla y pabellones históricos en ruinas. Algunos pabellones aún se utilizan mientras otros llevan décadas cerrados. La mezcla de uso activo y abandono crea un ambiente surreal. Patrimonio histórico en proceso de deterioro.',
                'dificultad'  => 'media',
                'estado'      => 'Parcialmente activo — alas en ruinas',
                'materiales'  => ['Calzado resistente', 'Linterna'],
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
            ],
            [
                'nombre'      => 'Balneario de Panticosa (zona abandonada)',
                'ciudad'      => 'Zaragoza',
                'latitud'     => 42.7203,
                'longitud'    => -0.2461,
                'descripcion' => 'El complejo termal de Panticosa en el Pirineo aragonés combina instalaciones activas con un ala histórica completamente abandonada desde los años 80. Los edificios del siglo XIX en ruinas, con sus fachadas de piedra y ventanas vacías frente al circo glaciar, crean un contraste dramático. El entorno natural de alta montaña hace de este uno de los spots más fotogénicos de España.',
                'dificultad'  => 'baja',
                'estado'      => 'Ala histórica abandonada',
                'materiales'  => ['Calzado resistente', 'Ropa de abrigo'],
            ],
            [
                'nombre'      => 'Fábrica de Cementos El Clot del Moro',
                'ciudad'      => 'Barcelona',
                'latitud'     => 41.6234,
                'longitud'    => 1.8723,
                'descripcion' => 'Monumental fábrica de cemento de principios del siglo XX en Castellnou de Bages (Barcelona). Considerada una de las mejores muestras de arquitectura industrial modernista de España. Sus hornos, chimeneas y silos de hormigón artístico siguen en pie. Declarada Bien Cultural de Interés Nacional. El interior está parcialmente accesible y conserva maquinaria original de la época.',
                'dificultad'  => 'media',
                'estado'      => 'Protegida — visitas esporádicas organizadas',
                'materiales'  => ['Calzado resistente', 'Linterna', 'Casco'],
            ],
            [
                'nombre'      => 'Castillo de Toloño (Labastida)',
                'ciudad'      => 'Vitoria-Gasteiz',
                'latitud'     => 42.5691,
                'longitud'    => -2.6543,
                'descripcion' => 'Ruinas medievales del castillo de Toloño en la Sierra de Cantabria, sobre La Rioja Alavesa. Construido en el siglo XI y destruido en guerras sucesivas, hoy solo quedan los muros exteriores y algunas torres. La ascensión a pie desde Labastida regala vistas espectaculares del valle del Ebro y los Pirineos en días despejados. Uno de los castillos más fotogénicos del País Vasco.',
                'dificultad'  => 'media',
                'estado'      => 'Ruinas medievales — acceso libre',
                'materiales'  => ['Calzado resistente', 'Agua'],
            ],
        ];

        // ── Obtener IDs de materiales ──
        $materialesDB = DB::table('materiales')->pluck('id', 'nombre');

        // ── Insertar spots ──
        $creados = 0;
        foreach ($spots as $spotData) {
            $ciudadNombre = $spotData['ciudad'];

            if (!isset($ciudades[$ciudadNombre])) {
                // Ciudad no prevista: crearla bajo España
                $ciudades[$ciudadNombre] = Ciudad::firstOrCreate(
                    ['nombre' => $ciudadNombre, 'pais_id' => $espana->id],
                    ['region' => 'España']
                );
            }

            // Evitar duplicados por nombre
            if (Localizacion::where('nombre', $spotData['nombre'])->exists()) {
                $this->command->line("  ⏭  Ya existe: {$spotData['nombre']}");
                continue;
            }

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
            $creados++;
        }

        $this->command->newLine();
        $this->command->info("SpotSeeder completado: {$creados} spots creados.");
        $this->command->line('  → Ejecuta "php artisan storage:link" si las imágenes no se ven.');
    }
}
