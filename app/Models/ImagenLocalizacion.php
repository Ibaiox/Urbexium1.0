<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenLocalizacion extends Model
{
    protected $table = 'imagenes_localizacion';

    protected $fillable = ['localizacion_id', 'user_id', 'url'];

    public function localizacion()
    {
        return $this->belongsTo(Localizacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
