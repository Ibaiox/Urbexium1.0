{{-- resources/views/tienda/pedido.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Pedido #' . $pedido->id)

@section('content')
<div style="max-width:760px; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                Pedido #{{ $pedido->id }}
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.875rem;">
                Realizado el {{ $pedido->created_at->format('d \d\e F \d\e Y \a \l\a\s H:i') }}
            </p>
        </div>
        <span style="
            display:inline-flex; align-items:center; gap:0.5rem;
            padding:0.375rem 1rem; border-radius:9999px; font-size:0.8125rem; font-weight:600;
            background:{{ $pedido->estadoColor() }}22;
            color:{{ $pedido->estadoColor() }};
            border:1px solid {{ $pedido->estadoColor() }}44;
        ">
            <span style="width:0.5rem; height:0.5rem; border-radius:50%; background:{{ $pedido->estadoColor() }};"></span>
            {{ $pedido->estadoLabel() }}
        </span>
    </div>

    {{-- Progreso --}}
    @php
        $pasos = ['pendiente','procesando','enviado','entregado'];
        $idx   = array_search($pedido->estado, $pasos);
    @endphp
    @if($pedido->estado !== 'cancelado')
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem;">
        <div style="display:flex; align-items:flex-start; gap:0;">
            @foreach($pasos as $i => $paso)
            @php
                $done   = $idx !== false && $i <= $idx;
                $active = $idx !== false && $i === $idx;
                $labels = ['pendiente'=>'Pendiente','procesando'=>'Procesando','enviado'=>'En camino','entregado'=>'Entregado'];
                $icons  = ['pendiente'=>'clock','procesando'=>'settings','enviado'=>'truck','entregado'=>'check-circle'];
            @endphp
            <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:0.5rem; position:relative;">
                {{-- Línea conectora --}}
                @if($i < count($pasos)-1)
                <div style="
                    position:absolute; top:1.25rem; left:50%; width:100%;
                    height:2px; z-index:0;
                    background:{{ ($idx !== false && $i < $idx) ? 'var(--primary)' : 'var(--border)' }};
                "></div>
                @endif

                {{-- Círculo --}}
                <div style="
                    position:relative; z-index:1;
                    width:2.5rem; height:2.5rem; border-radius:50%;
                    display:flex; align-items:center; justify-content:center;
                    background:{{ $done ? 'var(--primary)' : 'var(--secondary)' }};
                    border:2px solid {{ $active ? 'var(--primary)' : ($done ? 'var(--primary)' : 'var(--border)') }};
                    transition:all 300ms;
                ">
                    <i data-lucide="{{ $icons[$paso] }}" style="width:1rem; height:1rem; color:{{ $done ? 'var(--primary-foreground)' : 'var(--muted-foreground)' }};"></i>
                </div>

                <span style="font-size:0.75rem; font-weight:{{ $active ? '700' : '400' }}; color:{{ $done ? 'var(--foreground)' : 'var(--muted-foreground)' }}; text-align:center;">
                    {{ $labels[$paso] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Productos --}}
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden;">
        <div style="padding:1rem 1.5rem; border-bottom:1px solid var(--border);">
            <p style="font-weight:600; font-size:0.9375rem; margin:0;">Productos</p>
        </div>

        @foreach($pedido->items as $item)
        <div style="
            display:flex; align-items:center; gap:1rem; justify-content:space-between;
            padding:1rem 1.5rem;
            border-bottom:{{ !$loop->last ? '1px solid var(--border)' : 'none' }};
        ">
            <div style="display:flex; align-items:center; gap:0.875rem; flex:1; min-width:0;">
                <div style="
                    width:3.5rem; height:3.5rem; border-radius:calc(var(--radius)-4px);
                    background:var(--secondary); overflow:hidden; flex-shrink:0;
                    display:flex; align-items:center; justify-content:center;
                ">
                    @if($item->producto->imagen)
                        <img src="{{ Storage::url($item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <i data-lucide="package" style="width:1.5rem;height:1.5rem;color:var(--muted-foreground);opacity:0.4;"></i>
                    @endif
                </div>
                <div style="min-width:0;">
                    <p style="font-weight:600; font-size:0.9375rem; margin:0 0 0.125rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $item->producto->nombre }}
                    </p>
                    <p style="color:var(--muted-foreground); font-size:0.8125rem; margin:0;">
                        {{ number_format($item->precio_unitario, 2, ',', '.') }} € × {{ $item->cantidad }}
                    </p>
                </div>
            </div>
            <p style="font-weight:700; font-size:0.9375rem; margin:0; flex-shrink:0; color:var(--primary);">
                {{ number_format($item->precio_unitario * $item->cantidad, 2, ',', '.') }} €
            </p>
        </div>
        @endforeach

        {{-- Total --}}
        <div style="
            padding:1rem 1.5rem; border-top:2px solid var(--border);
            display:flex; justify-content:space-between; font-weight:700; font-size:1.0625rem;
        ">
            <span>Total</span>
            <span style="color:var(--primary);">{{ number_format($pedido->total, 2, ',', '.') }} €</span>
        </div>
    </div>

    {{-- Datos adicionales --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
        <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem;">
            <p style="font-size:0.75rem; color:var(--muted-foreground); text-transform:uppercase; letter-spacing:0.04em; margin:0 0 0.5rem;">Dirección de envío</p>
            <p style="font-size:0.875rem; margin:0; line-height:1.6;">
                {{ $pedido->direccion_envio ?: 'No especificada' }}
            </p>
        </div>
        <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem;">
            <p style="font-size:0.75rem; color:var(--muted-foreground); text-transform:uppercase; letter-spacing:0.04em; margin:0 0 0.5rem;">Método de pago</p>
            <p style="font-size:0.875rem; margin:0; font-weight:500; display:flex; align-items:center; gap:0.5rem;">
                <i data-lucide="credit-card" style="width:1rem; height:1rem; color:var(--primary);"></i>
                {{ ucfirst($pedido->metodo_pago ?? 'Stripe') }}
            </p>
        </div>
    </div>

    {{-- Acciones --}}
    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
        <a href="{{ route('tienda.pedidos.index') }}" class="btn btn-outline" style="height:2.5rem; font-size:0.875rem;">
            <i data-lucide="arrow-left" style="width:0.875rem; height:0.875rem;"></i>
            Mis pedidos
        </a>
        <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.5rem; font-size:0.875rem;">
            <i data-lucide="shopping-bag" style="width:0.875rem; height:0.875rem;"></i>
            Volver a la tienda
        </a>
    </div>
</div>

<style>
@media (max-width:600px) {
    div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns:1fr !important; }
}
</style>
@endsection
