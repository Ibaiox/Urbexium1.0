<?php
// app/Models/AdminActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'accion',
        'entidad',
        'entidad_id',
        'descripcion',
        'ip',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Helper estático para registrar acciones ───────────────────────────

    public static function registrar(string $accion, string $descripcion, ?string $entidad = null, ?int $entidadId = null): void
    {
        static::create([
            'admin_id'    => auth()->id(),
            'accion'      => $accion,
            'descripcion' => $descripcion,
            'entidad'     => $entidad,
            'entidad_id'  => $entidadId,
            'ip'          => request()->ip(),
        ]);
    }

    // ── Etiquetas de acción legibles ──────────────────────────────────────

    public function accionLabel(): string
    {
        return match($this->accion) {
            'ban_usuario'      => 'Baneó usuario',
            'desban_usuario'   => 'Desbaneó usuario',
            'cambiar_rol'      => 'Cambió rol',
            'aprobar_spot'     => 'Aprobó spot',
            'rechazar_spot'    => 'Rechazó spot',
            'eliminar_spot'    => 'Eliminó spot',
            'resolver_reporte' => 'Resolvió reporte',
            'guardar_ajustes'  => 'Editó ajustes',
            default            => $this->accion,
        };
    }

    public function accionColor(): string
    {
        return match($this->accion) {
            'ban_usuario'                  => '#ef4444',
            'desban_usuario'               => '#22c55e',
            'aprobar_spot'                 => '#22c55e',
            'rechazar_spot', 'eliminar_spot' => '#f59e0b',
            'resolver_reporte'             => '#3b82f6',
            'guardar_ajustes'              => '#8b5cf6',
            default                        => '#6b7280',
        };
    }
}
