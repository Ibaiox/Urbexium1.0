{{-- resources/views/tienda/mis-pedidos.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Mis pedidos')

@section('content')
<div style="max-width:900px; display:flex; flex-direction:column; gap:1.5rem;">

    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">Mis pedidos</h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">Historial de tus compras</p>
        </div>
        <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.5rem; font-size:0.875rem;">
            <i data-lucide="shopping-bag" style="width:1rem; height:1rem;"></i>
            Ir a la tienda
        </a>
    </div>

    @if(session('success'))
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;border-radius:var(--radius);background:color-mix(in oklch,var(--primary) 12%,transparent);border:1px solid color-mix(in oklch,var(--primary) 30%,transparent);color:var(--primary);font-size:0.875rem;">
        <i data-lucide="check-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    @forelse($pedidos as $pedido)
    <div style="
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius); overflow:hidden;
    ">
        {{-- Cabecera pedido --}}
        <div style="
            display:flex; flex-wrap:wrap; gap:1rem; align-items:center; justify-content:space-between;
            padding:1rem 1.5rem; background:var(--secondary); border-bottom:1px solid var(--border);
        ">
            <div style="display:flex; gap:2rem; flex-wrap:wrap;">
                <div>
                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0 0 0.125rem; text-transform:uppercase; letter-spacing:0.04em;">Pedido</p>
                    <p style="font-weight:700; font-size:0.9375rem; margin:0;">#{{ $pedido->id }}</p>
                </div>
                <div>
                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0 0 0.125rem; text-transform:uppercase; letter-spacing:0.04em;">Fecha</p>
                    <p style="font-weight:500; font-size:0.875rem; margin:0;">{{ $pedido->created_at->format('d/m/Y') }}</p>
                </div>
                <div>
                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0 0 0.125rem; text-transform:uppercase; letter-spacing:0.04em;">Total</p>
                    <p style="font-weight:700; color:var(--primary); font-size:0.9375rem; margin:0;">{{ number_format($pedido->total, 2, ',', '.') }} €</p>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:0.75rem;">
                {{-- Badge estado --}}
                <span style="
                    display:inline-flex; align-items:center; gap:0.375rem;
                    padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600;
                    background:{{ $pedido->estadoColor() }}22;
                    color:{{ $pedido->estadoColor() }};
                    border:1px solid {{ $pedido->estadoColor() }}44;
                ">
                    <span style="width:0.4375rem; height:0.4375rem; border-radius:50%; background:{{ $pedido->estadoColor() }};"></span>
                    {{ $pedido->estadoLabel() }}
                </span>

                <a href="{{ route('tienda.pedidos.show', $pedido) }}" class="btn btn-outline" style="height:2rem; font-size:0.8125rem; padding:0 0.75rem;">
                    Ver detalle
                </a>
            </div>
        </div>

        {{-- Items --}}
        <div style="padding:1rem 1.5rem; display:flex; flex-direction:column; gap:0.75rem;">
            @foreach($pedido->items->take(3) as $item)
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <div style="
                    width:3rem; height:3rem; border-radius:calc(var(--radius)-4px);
                    background:var(--secondary); overflow:hidden; flex-shrink:0;
                    display:flex; align-items:center; justify-content:center;
                ">
                    @if($item->producto->imagen)
                        <img src="{{ Storage::url($item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <i data-lucide="package" style="width:1.25rem;height:1.25rem;color:var(--muted-foreground);opacity:0.4;"></i>
                    @endif
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-weight:500; font-size:0.875rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $item->producto->nombre }}</p>
                    <p style="color:var(--muted-foreground); font-size:0.8125rem; margin:0;">×{{ $item->cantidad }} · {{ number_format($item->precio_unitario, 2, ',', '.') }} € c/u</p>
                </div>
                <p style="font-weight:600; font-size:0.875rem; margin:0; flex-shrink:0;">
                    {{ number_format($item->precio_unitario * $item->cantidad, 2, ',', '.') }} €
                </p>
            </div>
            @endforeach

            @if($pedido->items->count() > 3)
            <p style="color:var(--muted-foreground); font-size:0.8125rem; margin:0;">
                + {{ $pedido->items->count() - 3 }} producto(s) más
            </p>
            @endif
        </div>
    </div>
    @empty
    <div style="
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:1rem; padding:4rem 2rem; text-align:center;
        background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
    ">
        <div style="width:4rem; height:4rem; border-radius:50%; background:var(--secondary); display:flex; align-items:center; justify-content:center;">
            <i data-lucide="package-open" style="width:2rem; height:2rem; color:var(--muted-foreground); opacity:0.5;"></i>
        </div>
        <div>
            <p style="font-weight:600; margin:0 0 0.25rem;">No tienes pedidos aún</p>
            <p style="color:var(--muted-foreground); font-size:0.875rem; margin:0;">Explora la tienda y encuentra tu equipo urbex ideal</p>
        </div>
        <a href="{{ route('tienda.index') }}" class="btn btn-primary">Ir a la tienda</a>
    </div>
    @endforelse

    {{-- Paginación --}}
    @if($pedidos->hasPages())
    <div>{{ $pedidos->links() }}</div>
    @endif
</div>
@endsection
