<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'rol_id',
        'nombre',
        'email',
        'password',
        'avatar',
        'bio',
        'baneado',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'baneado'           => 'boolean',
        ];
    }

    // ─── Relaciones ────────────────────────────────────────────────────────────

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function localizaciones(): HasMany
    {
        return $this->hasMany(Localizacion::class, 'user_id');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'user_id');
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenLocalizacion::class, 'user_id');
    }

    public function reportes(): HasMany
    {
        return $this->hasMany(Reporte::class, 'user_id');
    }

    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(
            Localizacion::class,
            'favoritos',
            'user_id',
            'localizacion_id'
        )->withTimestamps();
    }

    // ─── Helpers ───────────────────────────────────────────────────────────────

    public function esAdmin(): bool
    {
        return $this->rol?->nombre === 'administrador';
    }

    public function esModerador(): bool
    {
        return $this->rol?->nombre === 'moderador';
    }

    public function estaBaneado(): bool
    {
        return (bool) $this->baneado;
    }
}
