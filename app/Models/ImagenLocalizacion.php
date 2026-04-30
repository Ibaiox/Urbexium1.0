<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenLocalizacion extends Model
{
    protected $table = 'imagenes_localizacion';

    protected $fillable = ['localizacion_id', 'user_id', 'url'];

    public function localizacion(): BelongsTo
    {
        return $this->belongsTo(Localizacion::class, 'localizacion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
