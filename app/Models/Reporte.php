<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reporte extends Model
{
    protected $table = 'reportes';

    protected $fillable = [
        'user_id',
        'localizacion_id',
        'motivo',
        'descripcion',
        'estado',
    ];

    // Motivos disponibles como constantes para evitar strings sueltos
    const MOTIVOS = [
        'ubicación falsa',
        'acceso sellado',
        'demolido',
        'peligroso',
        'spam',
        'coordenadas erróneas',
    ];

    const ESTADOS = ['pendiente', 'revisado', 'rechazado', 'resuelto'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function localizacion(): BelongsTo
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeResueltos($query)
    {
        return $query->where('estado', 'resuelto');
    }
}
