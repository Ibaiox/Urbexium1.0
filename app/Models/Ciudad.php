<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = ['pais_id', 'nombre', 'region'];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function localizaciones(): HasMany
    {
        return $this->hasMany(Localizacion::class, 'ciudad_id');
    }
}
