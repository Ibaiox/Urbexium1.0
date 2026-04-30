<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pais extends Model
{
    protected $table = 'paises';

    protected $fillable = ['nombre'];

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'pais_id');
    }
}
