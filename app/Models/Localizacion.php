<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected function casts(): array
    {
        return [
            'latitud'            => 'float',
            'longitud'           => 'float',
            'visibility'         => 'boolean',
            'is_active'          => 'boolean',
            'deletion_requested' => 'boolean',
            'reports_count'      => 'integer',
        ];
    }

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenLocalizacion::class, 'localizacion_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'localizacion_id');
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'localizacion_id');
    }

    public function materiales(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
            'localizacion_material',
            'localizacion_id',
            'material_id'
        );
    }

    public function usuariosQueLaGuardaron(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'favoritos',
            'localizacion_id',
            'user_id'
        )->withTimestamps();
    }

    // ─── Scopes ────────────────────────────────────────────────────────────────

    public function scopeVisibles($query)
    {
        return $query->where('visibility', true)->where('is_active', true);
    }

    public function scopeVerificadas($query)
    {
        return $query->where('verification_status', 'verificada');
    }

    public function scopePendientes($query)
    {
        return $query->where('verification_status', 'pendiente');
    }
}
