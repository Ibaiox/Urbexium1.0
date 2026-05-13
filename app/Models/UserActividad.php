<?php
// app/Models/UserActividad.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserActividad extends Model
{
    protected $table = 'user_actividad';

    protected $fillable = [
        'user_id',
        'localizacion_id',
        'tipo',
        'descripcion',
        'spot_nombre',
    ];

    // ── Relaciones ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localizacion()
    {
        return $this->belongsTo(Localizacion::class);
    }

    // ── Helper estático para registrar desde cualquier controlador ──────────

    public static function registrar(
        string $tipo,
        string $descripcion,
        ?Localizacion $spot = null,
        ?int $userId = null
    ): void {
        $uid = $userId ?? Auth::id();
        if (!$uid) return;

        // Para vistas: evitar spam — no registrar si ya visitó este spot en los últimos 30 min
        if ($tipo === 'vista' && $spot) {
            $reciente = static::where('user_id', $uid)
                ->where('localizacion_id', $spot->id)
                ->where('tipo', 'vista')
                ->where('created_at', '>=', now()->subMinutes(30))
                ->exists();
            if ($reciente) return;
        }

        static::create([
            'user_id'         => $uid,
            'localizacion_id' => $spot?->id,
            'tipo'            => $tipo,
            'descripcion'     => $descripcion,
            'spot_nombre'     => $spot?->nombre,
        ]);
    }

    // ── Icono y color según tipo ────────────────────────────────────────────

    public function icono(): string
    {
        return match($this->tipo) {
            'vista'        => 'eye',
            'favorito_add' => 'heart',
            'favorito_rem' => 'heart-off',
            'spot_creado'  => 'map-pin',
            'spot_editado' => 'pencil',
            'spot_borrado' => 'trash',
            'comentario'   => 'message-circle',
            'valoracion'   => 'star',
            default        => 'activity',
        };
    }

    public function color(): string
    {
        return match($this->tipo) {
            'vista'        => 'var(--primary)',
            'favorito_add' => '#ef4444',
            'favorito_rem' => 'var(--muted-foreground)',
            'spot_creado'  => '#22c55e',
            'spot_editado' => '#f59e0b',
            'spot_borrado' => '#ef4444',
            'comentario'   => '#3b82f6',
            'valoracion'   => '#f59e0b',
            default        => 'var(--muted-foreground)',
        };
    }
}
