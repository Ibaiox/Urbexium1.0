<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localizacion extends Model
{
     protected $table = 'localizaciones';
    protected $fillable = [
        'user_id',
        'ciudad_id',
        'nombre',
        'descripcion',
        'latitud',
        'longitud',
        'dificultad',
        'estado',
        'verification_status',
        'visibility',
        'is_active',
        'deletion_requested',
        'reports_count',
    ];

    protected $casts = [
        'latitud'            => 'decimal:7',
        'longitud'           => 'decimal:7',
        'visibility'         => 'boolean',
        'is_active'          => 'boolean',
        'deletion_requested' => 'boolean',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenLocalizacion::class);
    }

    public function materiales()
    {
        return $this->belongsToMany(
            Material::class,
            'localizacion_material',
            'localizacion_id',
            'material_id'
        );
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    public function favoritadoPor()
    {
        return $this->belongsToMany(
            User::class,
            'favoritos',
            'localizacion_id',
            'user_id'
        )->withPivot('created_at');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function getImagenPrincipalAttribute(): ?string
    {
        return $this->imagenes->first()?->url;
    }
}
