<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
     protected $table = 'ciudades';
    protected $fillable = ['pais_id', 'nombre', 'region'];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function localizaciones()
    {
        return $this->hasMany(Localizacion::class);
    }
}
