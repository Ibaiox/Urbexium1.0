<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';

    protected $fillable = [
        'user_id',
        'localizacion_id',
        'puntuacion',
    ];

    protected $casts = [
        'puntuacion' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localizacion()
    {
        return $this->belongsTo(Localizacion::class);
    }
}
