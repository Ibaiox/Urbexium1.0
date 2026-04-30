<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    protected $table = 'materiales';

    protected $fillable = ['nombre', 'descripcion'];

    public function localizaciones(): BelongsToMany
    {
        return $this->belongsToMany(
            Localizacion::class,
            'localizacion_material',
            'material_id',
            'localizacion_id'
        );
    }
}
