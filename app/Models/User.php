<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'baneado'           => 'boolean',
        'password'          => 'hashed',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function localizaciones()
    {
        return $this->hasMany(Localizacion::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function reportes()
    {
        return $this->hasMany(Reporte::class);
    }

    public function favoritos()
    {
        return $this->belongsToMany(
            Localizacion::class,
            'favoritos',
            'user_id',
            'localizacion_id'
        )->withPivot('created_at');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class);
    }

    public function actividad()
    {
        return $this->hasMany(\App\Models\UserActividad::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ImagenLocalizacion::class);
    }

    public function valoraciones()
    {
        return $this->hasMany(\App\Models\Valoracion::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->rol?->nombre === 'admin';
    }

    public function isBanned(): bool
    {
        return (bool) $this->baneado;
    }

    public function esAdmin(): bool
    {
        return $this->rol?->nombre === 'admin';
    }

    public function esModerador(): bool
    {
        return $this->rol?->nombre === 'moderador';
    }
}
