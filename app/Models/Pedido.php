<?php
// app/Models/Pedido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'estado',
        'direccion_envio',
        'metodo_pago',
        'stripe_payment_intent',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // ── Labels para la UI ──────────────────────────────────────────────────

    const ESTADOS = [
        'pendiente'  => ['label' => 'Pendiente',   'color' => '#f59e0b'],
        'procesando' => ['label' => 'Procesando',  'color' => '#3b82f6'],
        'enviado'    => ['label' => 'Enviado',      'color' => '#8b5cf6'],
        'entregado'  => ['label' => 'Entregado',   'color' => '#22c55e'],
        'cancelado'  => ['label' => 'Cancelado',   'color' => '#ef4444'],
    ];

    public function estadoLabel(): string
    {
        return self::ESTADOS[$this->estado]['label'] ?? $this->estado;
    }

    public function estadoColor(): string
    {
        return self::ESTADOS[$this->estado]['color'] ?? '#6b7280';
    }

    // ── Relaciones ─────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
