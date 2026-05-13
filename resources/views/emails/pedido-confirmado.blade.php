{{-- resources/views/emails/pedido-confirmado.blade.php --}}
@extends('emails.layout')

@section('email-title', 'Pedido confirmado — Urbexium')

@section('email-body')
<h1 class="title"> Pedido confirmado</h1>
<p class="subtitle">Gracias por tu compra en la tienda Urbexium</p>

<p>
    Hola <span class="highlight">{{ $user->nombre }}</span>, hemos recibido tu pedido y
    lo estamos preparando. Aquí tienes el resumen:
</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label"> Nº de pedido</span>
        <span class="info-value">#{{ $pedido->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Fecha</span>
        <span class="info-value">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Estado</span>
        <span class="info-value" style="color:#4ade80;">{{ ucfirst($pedido->estado ?? 'Confirmado') }}</span>
    </div>
</div>

@if($pedido->items && $pedido->items->isNotEmpty())
<p><strong style="color:#fff;">Productos:</strong></p>
<div class="info-box">
    @foreach($pedido->items as $item)
    <div class="info-row">
        <span class="info-label">{{ $item->producto->nombre ?? 'Producto' }} × {{ $item->cantidad }}</span>
        <span class="info-value">{{ number_format($item->precio_unitario * $item->cantidad, 2) }} €</span>
    </div>
    @endforeach
    <div class="info-row" style="border-top:1px solid #2d3148; margin-top:0.5rem; padding-top:0.75rem;">
        <span class="info-label" style="font-weight:700; color:#fff;">Total</span>
        <span class="info-value" style="color:#4ade80; font-weight:700; font-size:1rem;">
            {{ number_format($pedido->total ?? $pedido->items->sum(fn($i) => $i->precio_unitario * $i->cantidad), 2) }} €
        </span>
    </div>
</div>
@endif

<div style="text-align:center; margin:2rem 0;">
    <a href="{{ config('app.url') }}/tienda/pedidos/{{ $pedido->id }}" class="btn">
        Ver mi pedido →
    </a>
</div>

<p style="font-size:0.875rem; color:#8892a4;">
    Si tienes alguna duda sobre tu pedido, no dudes en
    <a href="{{ config('app.url') }}/contacto" style="color:#4ade80;">contactarnos</a>.
</p>
@endsection
