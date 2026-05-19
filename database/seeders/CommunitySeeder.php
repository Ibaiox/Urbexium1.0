<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Community;
use App\Models\CommunityMessage;
use App\Models\User;
use App\Models\Rol;

class CommunitySeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Rol usuario (necesario para los usuarios de prueba) ────────
        $rolUsuario = Rol::where('nombre', 'usuario')->first();
        $rolAdmin   = Rol::where('nombre', 'admin')->first();

        // ── 2. Usuarios de prueba para poblar chats ───────────────────────
        $usuarios = [
            [
                'nombre'   => 'Dani Sombras',
                'email'    => 'dani@urbexium.test',
                'location' => 'Madrid',
            ],
            [
                'nombre'   => 'Laia Fosca',
                'email'    => 'laia@urbexium.test',
                'location' => 'Barcelona',
            ],
            [
                'nombre'   => 'Txema Underground',
                'email'    => 'txema@urbexium.test',
                'location' => 'Bilbao',
            ],
            [
                'nombre'   => 'Raquel Ruinas',
                'email'    => 'raquel@urbexium.test',
                'location' => 'Valencia',
            ],
            [
                'nombre'   => 'Iñaki Decay',
                'email'    => 'inaki@urbexium.test',
                'location' => 'Zaragoza',
            ],
        ];

        $createdUsers = [];
        foreach ($usuarios as $u) {
            $createdUsers[] = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'rol_id'   => $rolUsuario->id,
                    'nombre'   => $u['nombre'],
                    'password' => Hash::make('12345678'),
                    'location' => $u['location'],
                    'baneado'  => false,
                ]
            );
        }

        $admin = User::whereHas('rol', fn($q) => $q->where('nombre', 'admin'))->first();

        // ── 3. Comunidades ────────────────────────────────────────────────
        $communities = [
            [
                'name'        => 'Urbex Madrid',
                'city'        => 'Madrid',
                'description' => 'Comunidad para exploradores de la capital. Compartimos spots industriales abandonados, hospitales y estaciones olvidadas de la Comunidad de Madrid.',
                'members'     => [$createdUsers[0], $createdUsers[3]],
                'messages'    => [
                    [$createdUsers[0], '¿Alguien ha estado por los túneles de la M-30 vieja? Tengo unas fotos bastante buenas.'],
                    [$createdUsers[3], 'Yo fui hace dos meses, cuidado que hay vigilancia los fines de semana.'],
                    [$createdUsers[0], 'Gracias por el aviso. ¿Quedamos algún miércoles por la mañana?'],
                    [$createdUsers[3], '¡Me apunto! Mandadme un mensaje cuando tengáis fecha.'],
                ],
            ],
            [
                'name'        => 'Exploradors BCN',
                'city'        => 'Barcelona',
                'description' => 'Grupo de urbex en Barcelona y alrededores. Fábricas textiles, bunkers del 36 y arquitectura modernista en desuso. Respetamos el patrimonio.',
                'members'     => [$createdUsers[1], $createdUsers[0]],
                'messages'    => [
                    [$createdUsers[1], 'Nueva entrada en el blog: fábrica textil de Poble Sec. Os dejo el enlace en el grupo de Telegram.'],
                    [$createdUsers[0], 'Increíbles las fotos, sobre todo la sala de máquinas. ¿Sigue accesible?'],
                    [$createdUsers[1], 'Por ahora sí, pero han vallado la entrada principal. Hay una alternativa por el lateral.'],
                ],
            ],
            [
                'name'        => 'Bilbao Decay',
                'city'        => 'Bilbao',
                'description' => 'La ría de Bilbao esconde décadas de historia industrial. Altos hornos, astilleros y naves abandonadas esperan a quienes saben mirar. Comunidad activa del País Vasco.',
                'members'     => [$createdUsers[2], $createdUsers[4]],
                'messages'    => [
                    [$createdUsers[2], 'El astillero de la margen izquierda sigue siendo una pasada. Ideal para fotografía nocturna.'],
                    [$createdUsers[4], 'He visto que están derribando una de las naves viejas. ¿Alguien ha documentado el interior?'],
                    [$createdUsers[2], 'Yo fui el mes pasado. Subo las fotos esta semana.'],
                    [$createdUsers[4], '¡Perfecto! Habrá que darse prisa antes de que desaparezca todo.'],
                    [$createdUsers[2], 'Exacto. La historia de estos lugares hay que preservarla aunque sea en imagen.'],
                ],
            ],
            [
                'name'        => 'Valencia Abandonada',
                'city'        => 'Valencia',
                'description' => 'Descubrimos los rincones olvidados de la Comunitat Valenciana: fábricas de cerámica, masías en ruinas, balnearios art nouveau y cine de los 70.',
                'members'     => [$createdUsers[3], $createdUsers[1]],
                'messages'    => [
                    [$createdUsers[3], '¿Alguien conoce el estado actual del balneario de Cofrentes? Vi unas fotos de hace 3 años.'],
                    [$createdUsers[1], 'Fui el verano pasado. Sigue en pie pero con el techo bastante comprometido en la zona del spa.'],
                    [$createdUsers[3], 'Entendido, iré con casco y linterna. Gracias por el aviso.'],
                ],
            ],
            [
                'name'        => 'Aragón Olvidado',
                'city'        => 'Zaragoza',
                'description' => 'Pueblos deshabitados, castillos medievales en ruinas y minas abandonadas del Pirineo. Exploramos Aragón con respeto y sin dejar rastro.',
                'members'     => [$createdUsers[4], $createdUsers[2]],
                'messages'    => [
                    [$createdUsers[4], 'Este fin de semana voy a Ainielle, el pueblo que inspiró a Llamazares. Si alguien se anima...'],
                    [$createdUsers[2], 'Ojalá pudiera, pero tengo compromiso. Tráeme fotos del campanario.'],
                    [$createdUsers[4], 'Sin falta. También paso por Ligüerre de Cinca, que sigue inundado a medias.'],
                ],
            ],
        ];

        foreach ($communities as $data) {
            /** @var Community $community */
            $community = Community::firstOrCreate(
                ['name' => $data['name'], 'city' => $data['city']],
                [
                    'description' => $data['description'],
                    'image'       => null,
                    'created_by'  => $admin?->id,
                ]
            );

            // Adjuntar miembros (evita duplicados gracias a syncWithoutDetaching)
            $memberIds = [];
            foreach ($data['members'] as $i => $user) {
                $memberIds[$user->id] = [
                    'role'      => $i === 0 ? 'moderator' : 'member',
                    'joined_at' => now()->subDays(rand(5, 60)),
                ];
            }
            $community->members()->syncWithoutDetaching($memberIds);

            // Insertar mensajes solo si el chat está vacío
            if ($community->messages()->count() === 0) {
                foreach ($data['messages'] as $msg) {
                    [$author, $text] = $msg;
                    CommunityMessage::create([
                        'community_id' => $community->id,
                        'user_id'      => $author->id,
                        'message'      => $text,
                        'created_at'   => now()->subMinutes(rand(10, 1440)),
                        'updated_at'   => now()->subMinutes(rand(1, 10)),
                    ]);
                }
            }
        }

        $this->command->info('✅  CommunitySeeder: ' . count($communities) . ' comunidades creadas con miembros y mensajes de prueba.');
        $this->command->info('   Usuarios de prueba (password: 12345678):');
        foreach ($usuarios as $u) {
            $this->command->info('   · ' . $u['email'] . '  —  ' . $u['nombre']);
        }
    }
}
