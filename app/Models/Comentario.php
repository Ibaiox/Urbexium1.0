<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    protected $table = 'comentarios';

    protected $fillable = ['user_id', 'localizacion_id', 'contenido'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function localizacion(): BelongsTo
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_id');
    }
}
