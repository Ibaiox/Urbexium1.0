{{-- resources/views/tienda/show.blade.php --}}
@extends('layout.masterpage')

@section('title', $producto->nombre)

@section('content')
<div style="max-width:1100px; margin:0 auto; width:100%; display:flex; flex-direction:column; gap:2rem;">

    {{-- Breadcrumb --}}
    <nav style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">
        <a href="{{ route('tienda.index') }}" style="color:var(--muted-foreground); text-decoration:none;"
            onmouseover="this.style.color='var(--foreground)'" onmouseout="this.style.color='var(--muted-foreground)'">Tienda</a>
        <i data-lucide="chevron-right" style="width:0.875rem; height:0.875rem;"></i>
        <span style="text-transform:capitalize;">{{ $producto->categoria }}</span>
        <i data-lucide="chevron-right" style="width:0.875rem; height:0.875rem;"></i>
        <span style="color:var(--foreground); font-weight:500;">{{ $producto->nombre }}</span>
    </nav>

    {{-- Main grid --}}
    <div class="product-main-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:3rem; align-items:start;">

        {{-- Imagen --}}
        <div style="
            aspect-ratio:1; border-radius:var(--radius); overflow:hidden;
            background:var(--secondary); border:1px solid var(--border);
            display:flex; align-items:center; justify-content:center;
        ">
            @if($producto->imagen)
                <img src="{{ Storage::url($producto->imagen) }}" alt="{{ $producto->nombre }}"
                     style="width:100%; height:100%; object-fit:cover;">
            @else
                <i data-lucide="package" style="width:5rem; height:5rem; color:var(--muted-foreground); opacity:0.3;"></i>
            @endif
        </div>

        {{-- Info --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem; padding-top:0.5rem;">

            {{-- Badge categoría --}}
            <div>
                <span style="
                    display:inline-flex; align-items:center; gap:0.375rem;
                    padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600;
                    background:color-mix(in oklch, var(--primary) 12%, transparent);
                    color:var(--primary); text-transform:uppercase; letter-spacing:0.05em;
                ">{{ $producto->categoria }}</span>

                @if(! $producto->activo)
                <span style="
                    display:inline-flex; margin-left:0.5rem;
                    padding:0.25rem 0.75rem; border-radius:9999px; font-size:0.75rem; font-weight:600;
                    background:color-mix(in oklch, var(--destructive) 12%, transparent);
                    color:var(--destructive);
                ">No disponible</span>
                @endif
            </div>

            <div>
                <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.75rem;">
                    {{ $producto->nombre }}
                </h1>
                <p style="font-size:2rem; font-weight:800; color:var(--primary); margin:0;">
                    {{ number_format($producto->precio, 2, ',', '.') }} €
                </p>
            </div>

            @if($producto->descripcion)
            <p style="font-size:0.9375rem; color:var(--muted-foreground); line-height:1.7; margin:0;">
                {{ $producto->descripcion }}
            </p>
            @endif

            {{-- Stock --}}
            <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.875rem;">
                @if($producto->stock > 10)
                    <div style="width:0.5rem; height:0.5rem; border-radius:50%; background:#22c55e;"></div>
                    <span style="color:#22c55e; font-weight:500;">En stock ({{ $producto->stock }} disponibles)</span>
                @elseif($producto->stock > 0)
                    <div style="width:0.5rem; height:0.5rem; border-radius:50%; background:#f59e0b;"></div>
                    <span style="color:#f59e0b; font-weight:500;">Últimas {{ $producto->stock }} unidades</span>
                @else
                    <div style="width:0.5rem; height:0.5rem; border-radius:50%; background:var(--destructive);"></div>
                    <span style="color:var(--destructive); font-weight:500;">Sin stock</span>
                @endif
            </div>

            {{-- Selector cantidad + Añadir al carrito --}}
            @if($producto->activo && $producto->stock > 0)
            <div style="display:flex; flex-direction:column; gap:1rem;">
                {{-- Cantidad --}}
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span style="font-size:0.875rem; font-weight:500; color:var(--muted-foreground);">Cantidad:</span>
                    <div style="display:flex; align-items:center; gap:0; border:1px solid var(--border); border-radius:var(--radius); overflow:hidden;">
                        <button onclick="changeQty(-1)" style="
                            width:2.5rem; height:2.5rem; border:none; background:var(--secondary);
                            cursor:pointer; font-size:1.25rem; display:flex; align-items:center; justify-content:center;
                        ">−</button>
                        <input type="number" id="qty" value="1" min="1" max="{{ $producto->stock }}"
                               style="
                                   width:3rem; height:2.5rem; border:none; border-left:1px solid var(--border);
                                   border-right:1px solid var(--border); text-align:center; font-size:0.9375rem;
                                   font-weight:600; background:var(--card); color:var(--foreground); outline:none;
                               ">
                        <button onclick="changeQty(1)" style="
                            width:2.5rem; height:2.5rem; border:none; background:var(--secondary);
                            cursor:pointer; font-size:1.25rem; display:flex; align-items:center; justify-content:center;
                        ">+</button>
                    </div>
                </div>

                {{-- Botón añadir --}}
                <button onclick="addThisToCart()" class="btn btn-primary" style="height:3rem; font-size:1rem; justify-content:center; gap:0.625rem;">
                    <i data-lucide="shopping-cart" style="width:1.125rem; height:1.125rem;"></i>
                    Añadir al carrito
                </button>
                <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.75rem; justify-content:center;">
                    ← Volver a la tienda
                </a>
            </div>
            @else
            <div style="padding:1rem; border-radius:var(--radius); background:var(--secondary); text-align:center; color:var(--muted-foreground); font-size:0.9rem;">
                Producto no disponible en este momento
            </div>
            @endif

            {{-- Admin acciones --}}
            @auth
            @if(Auth::user()->isAdmin())
            <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid var(--border);">
                <a href="{{ route('tienda.edit', $producto) }}" class="btn btn-outline" style="height:2.25rem; font-size:0.8125rem;">
                    <i data-lucide="edit-2" style="width:0.875rem; height:0.875rem;"></i>
                    Editar
                </a>
                <form method="POST" action="{{ route('tienda.destroy', $producto) }}" onsubmit="return confirm('¿Eliminar producto?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="height:2.25rem; font-size:0.8125rem; background:color-mix(in oklch,var(--destructive) 12%,transparent); color:var(--destructive); border:1px solid color-mix(in oklch,var(--destructive) 30%,transparent);">
                        <i data-lucide="trash-2" style="width:0.875rem; height:0.875rem;"></i>
                        Eliminar
                    </button>
                </form>
            </div>
            @endif
            @endauth
        </div>
    </div>

    {{-- Productos relacionados --}}
    @if($relacionados->count())
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
        <h2 style="font-size:1.25rem; font-weight:700; margin:0;">Productos relacionados</h2>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(13rem,1fr)); gap:1rem;">
            @foreach($relacionados as $rel)
            <a href="{{ route('tienda.show', $rel) }}" style="text-decoration:none; color:inherit;">
                <div style="
                    background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
                    overflow:hidden; transition:border-color 200ms;
                "
                onmouseover="this.style.borderColor='color-mix(in oklch,var(--primary) 50%,transparent)'"
                onmouseout="this.style.borderColor='var(--border)'"
                >
                    <div style="aspect-ratio:1; background:var(--secondary); display:flex; align-items:center; justify-content:center;">
                        @if($rel->imagen)
                            <img src="{{ Storage::url($rel->imagen) }}" alt="{{ $rel->nombre }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i data-lucide="package" style="width:2.5rem;height:2.5rem;color:var(--muted-foreground);opacity:0.3;"></i>
                        @endif
                    </div>
                    <div style="padding:0.875rem;">
                        <p style="font-weight:600; font-size:0.875rem; margin:0 0 0.25rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $rel->nombre }}</p>
                        <p style="font-weight:700; color:var(--primary); margin:0; font-size:0.9375rem;">{{ number_format($rel->precio,2,',','.') }} €</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

</div>

<script>
const productId    = {{ $producto->id }};
const productName  = @json($producto->nombre);
const productPrice = {{ $producto->precio }};
const productImage = @json($producto->imagen ? Storage::url($producto->imagen) : null);
const maxStock     = {{ $producto->stock }};

function changeQty(delta) {
    const input = document.getElementById('qty');
    input.value = Math.min(maxStock, Math.max(1, parseInt(input.value) + delta));
}

function addThisToCart() {
    const qty = parseInt(document.getElementById('qty').value);
    let cart = JSON.parse(localStorage.getItem('urbexium_cart') || '[]');
    const existing = cart.find(i => i.id === productId);
    if (existing) {
        existing.qty = Math.min(maxStock, existing.qty + qty);
    } else {
        cart.push({ id: productId, name: productName, price: productPrice, image: productImage, qty });
    }
    localStorage.setItem('urbexium_cart', JSON.stringify(cart));
    showToast('Añadido al carrito', 'success');
}

function showToast(msg, type = 'success') {
    const t = document.createElement('div');
    t.style.cssText = `position:fixed;bottom:1.5rem;right:1.5rem;z-index:300;display:flex;align-items:center;gap:0.625rem;padding:0.875rem 1.25rem;border-radius:var(--radius);background:var(--card);border:1px solid var(--border);box-shadow:0 4px 24px rgba(0,0,0,0.12);font-size:0.875rem;font-weight:500;`;
    const color = type === 'success' ? 'var(--primary)' : 'var(--destructive)';
    const icon  = type === 'success' ? 'check-circle' : 'alert-circle';
    t.innerHTML = `<i data-lucide="${icon}" style="width:1rem;height:1rem;color:${color};"></i>${msg}`;
    document.body.appendChild(t);
    if (window.lucide) lucide.createIcons();
    setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity 300ms'; setTimeout(()=>t.remove(),300); }, 2500);
}
</script>

<style>
@media (max-width: 700px) {
    div[style*="grid-template-columns:1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
    /* Main product grid stacks on mobile */
    .product-main-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
}
</style>
@endsection
