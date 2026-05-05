<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
protected $table = 'materiales';
protected $fillable = ['nombre', 'descripcion'];

    public function localizaciones()
    {
        return $this->belongsToMany(
            Localizacion::class,
            'localizacion_material',
            'material_id',
            'localizacion_id'
        );
    }
}
