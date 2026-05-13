{{-- resources/views/tienda/pago-exitoso.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Pago completado')

@section('content')
<div style="max-width:560px; margin:0 auto; display:flex; flex-direction:column; align-items:center; gap:2rem; text-align:center; padding-top:2rem;">

    {{-- Icono éxito --}}
    <div style="
        width:6rem; height:6rem; border-radius:50%;
        background:color-mix(in oklch, var(--primary) 12%, transparent);
        display:flex; align-items:center; justify-content:center;
    ">
        <i data-lucide="check-circle" style="width:3rem; height:3rem; color:var(--primary);"></i>
    </div>

    <div>
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.625rem;">
            ¡Pedido confirmado!
        </h1>
        <p style="color:var(--muted-foreground); font-size:0.9375rem; margin:0; line-height:1.6;">
            Hemos recibido tu pedido <strong style="color:var(--foreground);">#{{ $pedido->id }}</strong>
            por un total de <strong style="color:var(--primary);">{{ number_format($pedido->total, 2, ',', '.') }} €</strong>.
            Te mantendremos informado de su estado.
        </p>
    </div>

    {{-- Detalles del pedido --}}
    <div style="
        width:100%; background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius); overflow:hidden;
    ">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border); text-align:left;">
            <p style="font-weight:600; margin:0; font-size:0.9375rem;">Resumen del pedido</p>
        </div>
        @foreach($pedido->items as $item)
        <div style="
            display:flex; align-items:center; justify-content:space-between;
            padding:0.875rem 1.5rem; border-bottom:1px solid var(--border);
            gap:1rem; text-align:left;
        ">
            <div style="display:flex; align-items:center; gap:0.75rem; min-width:0;">
                <div style="
                    width:2.75rem; height:2.75rem; border-radius:calc(var(--radius)-4px);
                    background:var(--secondary); overflow:hidden; flex-shrink:0;
                    display:flex; align-items:center; justify-content:center;
                ">
                    @if($item->producto->imagen)
                        <img src="{{ Storage::url($item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <i data-lucide="package" style="width:1.25rem;height:1.25rem;color:var(--muted-foreground);opacity:0.4;"></i>
                    @endif
                </div>
                <div>
                    <p style="font-weight:500; font-size:0.875rem; margin:0;">{{ $item->producto->nombre }}</p>
                    <p style="color:var(--muted-foreground); font-size:0.8125rem; margin:0;">×{{ $item->cantidad }}</p>
                </div>
            </div>
            <p style="font-weight:600; font-size:0.875rem; flex-shrink:0; margin:0;">
                {{ number_format($item->precio_unitario * $item->cantidad, 2, ',', '.') }} €
            </p>
        </div>
        @endforeach
        <div style="padding:1rem 1.5rem; display:flex; justify-content:space-between; font-weight:700; font-size:1rem;">
            <span>Total</span>
            <span style="color:var(--primary);">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
        </div>
    </div>

    {{-- CTAs --}}
    <div style="display:flex; flex-direction:column; gap:0.75rem; width:100%;">
        <a href="{{ route('tienda.pedidos.show', $pedido) }}" class="btn btn-primary" style="height:2.875rem; justify-content:center; font-size:0.9375rem;">
            Ver detalle del pedido
        </a>
        <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.875rem; justify-content:center; font-size:0.9375rem;">
            Seguir comprando
        </a>
    </div>
</div>

<script>
// Limpiar carrito tras pago exitoso
localStorage.removeItem('urbexium_cart');
</script>
@endsection
