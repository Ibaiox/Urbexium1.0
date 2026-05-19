<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    protected $fillable = [
        'name',
        'city',
        'description',
        'image',
        'created_by',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────

    /**
     * Usuario que creó la comunidad.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Miembros de la comunidad (tabla pivote community_user).
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_user')
                    ->withPivot(['role', 'joined_at'])
                    ->withTimestamps();
    }

    /**
     * Mensajes del chat de la comunidad.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(CommunityMessage::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Comprueba si un usuario es miembro de la comunidad.
     */
    public function hasMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Devuelve el rol del usuario dentro de la comunidad, o null si no pertenece.
     */
    public function memberRole(User $user): ?string
    {
        $pivot = $this->members()->where('user_id', $user->id)->first();
        return $pivot?->pivot->role;
    }
}
