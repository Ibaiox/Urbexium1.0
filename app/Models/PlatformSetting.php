<?php
// app/Models/PlatformSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformSetting extends Model
{
    protected $primaryKey = 'clave';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = ['clave', 'valor', 'tipo', 'descripcion'];

    // ── Acceso tipado ─────────────────────────────────────────────────────

    public function getValorCasteadoAttribute(): mixed
    {
        return match($this->tipo) {
            'boolean' => filter_var($this->valor, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $this->valor,
            default   => $this->valor,
        };
    }

    // ── Helpers estáticos ─────────────────────────────────────────────────

    public static function get(string $clave, mixed $default = null): mixed
    {
        $setting = static::find($clave);
        return $setting ? $setting->valor_casteado : $default;
    }

    public static function set(string $clave, mixed $valor): void
    {
        static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => (string) $valor]
        );
    }

    /** Devuelve todos los ajustes como array clave => valorCasteado */
    public static function allSettings(): array
    {
        return static::all()->pluck('valor_casteado', 'clave')->toArray();
    }
}
