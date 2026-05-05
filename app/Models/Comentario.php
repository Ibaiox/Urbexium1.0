<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = ['user_id', 'localizacion_id', 'contenido'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function localizacion()
    {
        return $this->belongsTo(Localizacion::class);
    }
}
