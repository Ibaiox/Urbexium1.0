{{-- resources/views/tienda/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Tienda')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                Tienda
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                Equipo y accesorios para tus exploraciones urbanas
            </p>
        </div>

        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
            {{-- Admin: Añadir producto --}}
            @if(Auth::user()->rol->nombre === 'admin')
            <a href="{{ route('tienda.create') }}" class="btn btn-primary">
                <i data-lucide="plus" style="width:1rem; height:1rem;"></i>
                Añadir producto
            </a>
            @endif

            {{-- Carrito button --}}
            <button
                id="cart-toggle"
                class="btn btn-outline"
                style="position:relative;"
                onclick="toggleCart()"
            >
                <i data-lucide="shopping-cart" style="width:1rem; height:1rem;"></i>
                Carrito
                <span
                    id="cart-badge"
                    style="
                        display:none; position:absolute; top:-0.5rem; right:-0.5rem;
                        min-width:1.25rem; height:1.25rem; padding:0 0.3rem;
                        border-radius:9999px; font-size:0.7rem; font-weight:700;
                        background:var(--primary); color:var(--primary-foreground);
                        display:flex; align-items:center; justify-content:center;
                    "
                    id="cart-count-badge"
                >0</span>
            </button>
        </div>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div style="
        display:flex; align-items:center; gap:0.75rem;
        padding:0.875rem 1rem; border-radius:var(--radius);
        background:color-mix(in oklch, var(--primary) 12%, transparent);
        border:1px solid color-mix(in oklch, var(--primary) 30%, transparent);
        color:var(--primary); font-size:0.875rem;
    ">
        <i data-lucide="check-circle" style="width:1rem; height:1rem; flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="
        display:flex; align-items:center; gap:0.75rem;
        padding:0.875rem 1rem; border-radius:var(--radius);
        background:color-mix(in oklch, var(--destructive) 12%, transparent);
        border:1px solid color-mix(in oklch, var(--destructive) 30%, transparent);
        color:var(--destructive); font-size:0.875rem;
    ">
        <i data-lucide="alert-circle" style="width:1rem; height:1rem; flex-shrink:0;"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- Filtros y búsqueda --}}
    <div style="display:flex; flex-wrap:wrap; gap:0.75rem; align-items:center;">
        {{-- Búsqueda --}}
        <div style="position:relative; flex:1; min-width:14rem; max-width:22rem;">
            <i data-lucide="search" style="
                position:absolute; left:0.75rem; top:50%; transform:translateY(-50%);
                width:1rem; height:1rem; color:var(--muted-foreground); pointer-events:none;
            "></i>
            <input
                type="search"
                id="search-input"
                placeholder="Buscar productos..."
                value="{{ request('q') }}"
                oninput="filterProducts()"
                style="
                    width:100%; height:2.5rem; padding:0 1rem 0 2.5rem;
                    border:1px solid var(--border); border-radius:var(--radius);
                    background:var(--card); color:var(--foreground);
                    font-size:0.875rem; outline:none; box-sizing:border-box;
                "
                onfocus="this.style.borderColor='var(--ring)'"
                onblur="this.style.borderColor='var(--border)'"
            />
        </div>

        {{-- Filtro categoría --}}
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            @php
                $categorias = ['todos' => 'Todos', 'equipo' => 'Equipo', 'ropa' => 'Ropa', 'seguridad' => 'Seguridad', 'accesorios' => 'Accesorios'];
                $currentCat = request('categoria', 'todos');
            @endphp
            @foreach($categorias as $val => $label)
            <button
                class="filter-btn {{ $currentCat === $val ? 'active' : '' }}"
                data-category="{{ $val }}"
                onclick="setCategoryFilter('{{ $val }}')"
                style="
                    height:2.5rem; padding:0 1rem; border-radius:var(--radius);
                    border:1px solid {{ $currentCat === $val ? 'var(--primary)' : 'var(--border)' }};
                    background:{{ $currentCat === $val ? 'color-mix(in oklch, var(--primary) 12%, transparent)' : 'var(--card)' }};
                    color:{{ $currentCat === $val ? 'var(--primary)' : 'var(--foreground)' }};
                    font-size:0.8125rem; font-weight:{{ $currentCat === $val ? '600' : '400' }};
                    cursor:pointer; transition:all 150ms;
                "
            >{{ $label }}</button>
            @endforeach
        </div>
    </div>

    {{-- Grid de productos --}}
    <div id="products-grid" style="
        display:grid; gap:1.25rem;
        grid-template-columns:repeat(auto-fill, minmax(15rem, 1fr));
    ">
        @forelse($productos ?? [] as $producto)
        <div
            class="product-card"
            data-name="{{ strtolower($producto->nombre) }}"
            data-category="{{ $producto->categoria }}"
            style="
                display:flex; flex-direction:column;
                background:var(--card); border:1px solid var(--border);
                border-radius:var(--radius); overflow:hidden;
                transition:border-color 200ms, box-shadow 200ms;
            "
            onmouseover="this.style.borderColor='color-mix(in oklch, var(--primary) 50%, transparent)'; this.style.boxShadow='0 4px 20px color-mix(in oklch, var(--primary) 8%, transparent)'"
            onmouseout="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"
        >
            {{-- Imagen --}}
            <div style="position:relative; aspect-ratio:1; background:var(--secondary); overflow:hidden;">
                @if($producto->imagen)
                    <img
                        src="{{ asset('storage/' . $producto->imagen) }}"
                        alt="{{ $producto->nombre }}"
                        style="width:100%; height:100%; object-fit:cover; transition:transform 300ms;"
                        onmouseover="this.style.transform='scale(1.05)'"
                        onmouseout="this.style.transform='scale(1)'"
                    />
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                        <i data-lucide="package" style="width:3rem; height:3rem; color:var(--muted-foreground); opacity:0.4;"></i>
                    </div>
                @endif

                {{-- Badge categoría --}}
                <span style="
                    position:absolute; top:0.625rem; left:0.625rem;
                    padding:0.2rem 0.6rem; border-radius:9999px;
                    background:rgba(0,0,0,0.55); backdrop-filter:blur(4px);
                    color:#fff; font-size:0.7rem; font-weight:500; text-transform:capitalize;
                ">{{ $producto->categoria }}</span>

                {{-- Sin stock overlay --}}
                @if(!$producto->stock || $producto->stock < 1)
                <div style="
                    position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
                    background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);
                ">
                    <span style="
                        padding:0.375rem 1rem; border-radius:9999px;
                        background:var(--secondary); color:var(--foreground);
                        font-size:0.8125rem; font-weight:600;
                    ">Agotado</span>
                </div>
                @endif

                {{-- Admin actions --}}
                @if(Auth::user()->rol->nombre === 'admin')
                <div style="
                    position:absolute; top:0.625rem; right:0.625rem;
                    display:flex; gap:0.375rem;
                ">
                    <a
                        href="{{ route('tienda.edit', $producto) }}"
                        style="
                            display:flex; align-items:center; justify-content:center;
                            width:2rem; height:2rem; border-radius:calc(var(--radius) - 4px);
                            background:rgba(0,0,0,0.55); backdrop-filter:blur(4px);
                            color:#fff; text-decoration:none; transition:background 150ms;
                        "
                        title="Editar"
                        onmouseover="this.style.background='var(--primary)'"
                        onmouseout="this.style.background='rgba(0,0,0,0.55)'"
                    >
                        <i data-lucide="pencil" style="width:0.875rem; height:0.875rem;"></i>
                    </a>
                    <button
                        onclick="confirmDelete({{ $producto->id }}, '{{ addslashes($producto->nombre) }}')"
                        style="
                            display:flex; align-items:center; justify-content:center;
                            width:2rem; height:2rem; border-radius:calc(var(--radius) - 4px);
                            background:rgba(0,0,0,0.55); backdrop-filter:blur(4px);
                            color:#fff; border:none; cursor:pointer; transition:background 150ms;
                        "
                        title="Eliminar"
                        onmouseover="this.style.background='var(--destructive)'"
                        onmouseout="this.style.background='rgba(0,0,0,0.55)'"
                    >
                        <i data-lucide="trash-2" style="width:0.875rem; height:0.875rem;"></i>
                    </button>
                </div>
                @endif
            </div>

            {{-- Contenido --}}
            <div style="display:flex; flex-direction:column; gap:0.75rem; padding:1rem; flex:1;">
                <div style="flex:1;">
                    <h3 style="font-weight:600; font-size:0.9375rem; margin:0 0 0.375rem; line-height:1.3;">
                        {{ $producto->nombre }}
                    </h3>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0; line-height:1.5;
                        display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $producto->descripcion }}
                    </p>
                </div>

                <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem;">
                    <span style="font-size:1.25rem; font-weight:700; color:var(--foreground);">
                        {{ number_format($producto->precio, 2) }} €
                    </span>

                    @if($producto->stock && $producto->stock > 0)
                    <button
                        onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }}, '{{ $producto->imagen ? asset('storage/'.$producto->imagen) : '' }}')"
                        class="btn btn-primary"
                        style="font-size:0.8125rem; padding:0 0.875rem; height:2.25rem;"
                    >
                        <i data-lucide="plus" style="width:0.875rem; height:0.875rem;"></i>
                        Añadir
                    </button>
                    @else
                    <button disabled style="
                        height:2.25rem; padding:0 0.875rem;
                        border-radius:var(--radius); border:1px solid var(--border);
                        background:var(--secondary); color:var(--muted-foreground);
                        font-size:0.8125rem; cursor:not-allowed; opacity:0.6;
                    ">Agotado</button>
                    @endif
                </div>

                @if($producto->stock && $producto->stock <= 5 && $producto->stock > 0)
                <p style="font-size:0.75rem; color:var(--destructive); margin:0; display:flex; align-items:center; gap:0.375rem;">
                    <i data-lucide="alert-triangle" style="width:0.75rem; height:0.75rem;"></i>
                    Solo quedan {{ $producto->stock }} unidades
                </p>
                @endif
            </div>
        </div>
        @empty
        <div style="
            grid-column:1/-1; display:flex; flex-direction:column;
            align-items:center; justify-content:center;
            padding:4rem 2rem; text-align:center;
            color:var(--muted-foreground);
        ">
            <i data-lucide="package" style="width:3rem; height:3rem; margin-bottom:1rem; opacity:0.4;"></i>
            <h3 style="font-size:1.0625rem; font-weight:500; margin:0 0 0.375rem;">No hay productos disponibles</h3>
            <p style="font-size:0.875rem; margin:0;">Vuelve pronto, estamos añadiendo más equipo.</p>
        </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if(isset($productos) && $productos->hasPages())
    <div style="display:flex; justify-content:center; margin-top:1rem;">
        {{ $productos->appends(request()->query())->links() }}
    </div>
    @endif

</div>

{{-- ====== CARRITO DRAWER ====== --}}
<div
    id="cart-overlay"
    onclick="toggleCart()"
    style="
        display:none; position:fixed; inset:0;
        background:rgba(0,0,0,0.4); backdrop-filter:blur(2px);
        z-index:100; transition:opacity 200ms;
    "
></div>

<div
    id="cart-drawer"
    style="
        position:fixed; top:0; right:-32rem;
        width:min(32rem, 100vw); height:100%;
        background:var(--card); border-left:1px solid var(--border);
        z-index:101; display:flex; flex-direction:column;
        transition:right 300ms cubic-bezier(0.4,0,0.2,1);
        box-shadow:-4px 0 32px rgba(0,0,0,0.12);
    "
>
    {{-- Header carrito --}}
    <div style="
        display:flex; align-items:center; justify-content:space-between;
        padding:1.25rem 1.5rem; border-bottom:1px solid var(--border);
    ">
        <div style="display:flex; align-items:center; gap:0.625rem;">
            <i data-lucide="shopping-cart" style="width:1.25rem; height:1.25rem; color:var(--primary);"></i>
            <h2 style="font-size:1.0625rem; font-weight:600; margin:0;">Tu Carrito</h2>
            <span id="cart-header-count" style="
                padding:0.125rem 0.5rem; border-radius:9999px;
                background:color-mix(in oklch, var(--primary) 12%, transparent);
                color:var(--primary); font-size:0.75rem; font-weight:600;
            ">0 artículos</span>
        </div>
        <button onclick="toggleCart()" style="
            display:flex; align-items:center; justify-content:center;
            width:2rem; height:2rem; border-radius:var(--radius);
            border:none; background:var(--secondary); cursor:pointer;
            color:var(--muted-foreground); transition:background 150ms;
        "
        onmouseover="this.style.background='var(--border)'"
        onmouseout="this.style.background='var(--secondary)'"
        >
            <i data-lucide="x" style="width:1rem; height:1rem;"></i>
        </button>
    </div>

    {{-- Lista items --}}
    <div id="cart-items" style="flex:1; overflow-y:auto; padding:1rem 1.5rem; display:flex; flex-direction:column; gap:0.875rem;">
        {{-- Items se insertan con JS --}}
        <div id="cart-empty" style="
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            height:100%; text-align:center; color:var(--muted-foreground); gap:1rem;
        ">
            <div style="
                display:flex; align-items:center; justify-content:center;
                width:4rem; height:4rem; border-radius:50%;
                background:var(--secondary);
            ">
                <i data-lucide="shopping-bag" style="width:2rem; height:2rem; opacity:0.5;"></i>
            </div>
            <div>
                <p style="font-weight:500; margin:0 0 0.25rem;">Tu carrito está vacío</p>
                <p style="font-size:0.8125rem; margin:0;">Añade productos para empezar</p>
            </div>
            <button onclick="toggleCart()" class="btn btn-primary">Seguir comprando</button>
        </div>
    </div>

    {{-- Footer carrito --}}
    <div id="cart-footer" style="display:none; padding:1.25rem 1.5rem; border-top:1px solid var(--border); display:flex; flex-direction:column; gap:1rem;">
        <div style="display:flex; flex-direction:column; gap:0.5rem;">
            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--muted-foreground);">
                <span>Subtotal</span>
                <span id="cart-subtotal">0,00 €</span>
            </div>
            <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--muted-foreground);">
                <span>Envío</span>
                <span style="color:var(--primary); font-weight:500;">Gratis</span>
            </div>
            <div style="height:1px; background:var(--border); margin:0.25rem 0;"></div>
            <div style="display:flex; justify-content:space-between; font-weight:700; font-size:1.0625rem;">
                <span>Total</span>
                <span id="cart-total">0,00 €</span>
            </div>
        </div>
        <button onclick="checkout()" class="btn btn-primary" style="width:100%; height:2.875rem; font-size:0.9375rem; justify-content:center;">
            <i data-lucide="credit-card" style="width:1rem; height:1rem;"></i>
            Finalizar compra
        </button>
        <button onclick="clearCart()" style="
            width:100%; height:2.25rem; border-radius:var(--radius);
            border:1px solid var(--border); background:transparent;
            color:var(--muted-foreground); font-size:0.8125rem; cursor:pointer;
            transition:all 150ms;
        "
        onmouseover="this.style.borderColor='var(--destructive)'; this.style.color='var(--destructive)'"
        onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--muted-foreground)'"
        >Vaciar carrito</button>
    </div>
</div>

{{-- Modal confirmar checkout --}}
<div id="checkout-modal" style="
    display:none; position:fixed; inset:0; z-index:200;
    display:none; align-items:center; justify-content:center;
    background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);
">
    <div style="
        background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
        padding:2rem; max-width:24rem; width:calc(100% - 2rem);
        display:flex; flex-direction:column; gap:1.25rem; text-align:center;
    ">
        <div style="
            display:flex; align-items:center; justify-content:center;
            width:3.5rem; height:3.5rem; border-radius:50%; margin:0 auto;
            background:color-mix(in oklch, var(--primary) 12%, transparent);
        ">
            <i data-lucide="check-circle" style="width:1.75rem; height:1.75rem; color:var(--primary);"></i>
        </div>
        <div>
            <h3 style="font-size:1.125rem; font-weight:700; margin:0 0 0.5rem;">¿Confirmar pedido?</h3>
            <p style="font-size:0.875rem; color:var(--muted-foreground); margin:0;">
                Se procesará tu pedido por un total de <strong id="modal-total">0,00 €</strong>.
            </p>
        </div>
        <div style="display:flex; gap:0.75rem;">
            <button onclick="closeCheckoutModal()" style="
                flex:1; height:2.5rem; border-radius:var(--radius);
                border:1px solid var(--border); background:transparent;
                font-size:0.875rem; cursor:pointer;
            ">Cancelar</button>
            <button onclick="confirmCheckout()" class="btn btn-primary" style="flex:1; height:2.5rem; justify-content:center;">
                Confirmar
            </button>
        </div>
    </div>
</div>

{{-- Modal confirmar eliminar producto (admin) --}}
<div id="delete-modal" style="
    display:none; position:fixed; inset:0; z-index:200;
    align-items:center; justify-content:center;
    background:rgba(0,0,0,0.5); backdrop-filter:blur(4px);
">
    <div style="
        background:var(--card); border:1px solid var(--border); border-radius:var(--radius);
        padding:2rem; max-width:22rem; width:calc(100% - 2rem);
        display:flex; flex-direction:column; gap:1.25rem; text-align:center;
    ">
        <div style="
            display:flex; align-items:center; justify-content:center;
            width:3.5rem; height:3.5rem; border-radius:50%; margin:0 auto;
            background:color-mix(in oklch, var(--destructive) 12%, transparent);
        ">
            <i data-lucide="trash-2" style="width:1.75rem; height:1.75rem; color:var(--destructive);"></i>
        </div>
        <div>
            <h3 style="font-size:1.125rem; font-weight:700; margin:0 0 0.5rem;">¿Eliminar producto?</h3>
            <p style="font-size:0.875rem; color:var(--muted-foreground); margin:0;">
                Vas a eliminar <strong id="delete-product-name"></strong>. Esta acción no se puede deshacer.
            </p>
        </div>
        <div style="display:flex; gap:0.75rem;">
            <button onclick="closeDeleteModal()" style="
                flex:1; height:2.5rem; border-radius:var(--radius);
                border:1px solid var(--border); background:transparent;
                font-size:0.875rem; cursor:pointer;
            ">Cancelar</button>
            <form id="delete-form" method="POST" style="flex:1; margin:0;">
                @csrf @method('DELETE')
                <button type="submit" style="
                    width:100%; height:2.5rem; border-radius:var(--radius);
                    border:none; background:var(--destructive); color:var(--destructive-foreground);
                    font-size:0.875rem; font-weight:600; cursor:pointer;
                ">Eliminar</button>
            </form>
        </div>
    </div>
</div>

<script>
// =================== CARRITO ===================
let cart = JSON.parse(localStorage.getItem('urbexium_cart') || '[]');

function saveCart() {
    localStorage.setItem('urbexium_cart', JSON.stringify(cart));
}

function addToCart(id, name, price, image) {
    const existing = cart.find(i => i.id === id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({ id, name, price, image, qty: 1 });
    }
    saveCart();
    renderCart();
    showToast('Producto añadido al carrito', 'success');
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    saveCart();
    renderCart();
}

function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    saveCart();
    renderCart();
}

function clearCart() {
    cart = [];
    saveCart();
    renderCart();
}

function renderCart() {
    const count = cart.reduce((s, i) => s + i.qty, 0);
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);

    // Badge
    const badge = document.getElementById('cart-count-badge');
    badge.textContent = count;
    badge.style.display = count > 0 ? 'flex' : 'none';

    // Header count
    document.getElementById('cart-header-count').textContent = count + (count === 1 ? ' artículo' : ' artículos');

    // Items
    const container = document.getElementById('cart-items');
    const empty = document.getElementById('cart-empty');
    const footer = document.getElementById('cart-footer');

    if (cart.length === 0) {
        container.innerHTML = '';
        container.appendChild(empty);
        empty.style.display = 'flex';
        footer.style.display = 'none';
        return;
    }

    empty.style.display = 'none';
    footer.style.display = 'flex';

    // Rebuild items
    const existingItems = container.querySelectorAll('.cart-item');
    existingItems.forEach(el => el.remove());

    cart.forEach(item => {
        const div = document.createElement('div');
        div.className = 'cart-item';
        div.style.cssText = `
            display:flex; gap:0.875rem; padding:0.875rem;
            border:1px solid var(--border); border-radius:calc(var(--radius) - 2px);
            background:var(--secondary);
        `;
        div.innerHTML = `
            <div style="width:4.5rem; height:4.5rem; border-radius:calc(var(--radius) - 4px); overflow:hidden; background:var(--border); flex-shrink:0;">
                ${item.image
                    ? `<img src="${item.image}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;">`
                    : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><i data-lucide="package" style="width:1.5rem;height:1.5rem;color:var(--muted-foreground);opacity:0.5;"></i></div>`
                }
            </div>
            <div style="flex:1; display:flex; flex-direction:column; justify-content:space-between; gap:0.375rem; min-width:0;">
                <div>
                    <p style="font-weight:600; font-size:0.875rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${item.name}</p>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;">${(item.price).toFixed(2)} €</p>
                </div>
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:0.5rem;">
                        <button onclick="updateQty(${item.id}, -1)" style="
                            width:1.75rem; height:1.75rem; border-radius:calc(var(--radius) - 4px);
                            border:1px solid var(--border); background:var(--card);
                            cursor:pointer; display:flex; align-items:center; justify-content:center;
                        "><i data-lucide="minus" style="width:0.75rem; height:0.75rem;"></i></button>
                        <span style="font-size:0.875rem; font-weight:600; min-width:1.5rem; text-align:center;">${item.qty}</span>
                        <button onclick="updateQty(${item.id}, 1)" style="
                            width:1.75rem; height:1.75rem; border-radius:calc(var(--radius) - 4px);
                            border:1px solid var(--border); background:var(--card);
                            cursor:pointer; display:flex; align-items:center; justify-content:center;
                        "><i data-lucide="plus" style="width:0.75rem; height:0.75rem;"></i></button>
                    </div>
                    <button onclick="removeFromCart(${item.id})" style="
                        width:1.75rem; height:1.75rem; border-radius:calc(var(--radius) - 4px);
                        border:none; background:transparent; cursor:pointer;
                        color:var(--muted-foreground); display:flex; align-items:center; justify-content:center;
                    "
                    onmouseover="this.style.color='var(--destructive)'"
                    onmouseout="this.style.color='var(--muted-foreground)'"
                    ><i data-lucide="trash-2" style="width:0.875rem; height:0.875rem;"></i></button>
                </div>
            </div>
        `;
        container.appendChild(div);
    });

    // Re-init lucide icons in new elements
    if (window.lucide) lucide.createIcons();

    // Totals
    document.getElementById('cart-subtotal').textContent = total.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('cart-total').textContent = total.toFixed(2).replace('.', ',') + ' €';
}

function toggleCart() {
    const drawer = document.getElementById('cart-drawer');
    const overlay = document.getElementById('cart-overlay');
    const isOpen = drawer.style.right === '0px';
    drawer.style.right = isOpen ? '-32rem' : '0px';
    overlay.style.display = isOpen ? 'none' : 'block';
}

function checkout() {
    const total = cart.reduce((s, i) => s + i.price * i.qty, 0);
    document.getElementById('modal-total').textContent = total.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('checkout-modal').style.display = 'flex';
}

function closeCheckoutModal() {
    document.getElementById('checkout-modal').style.display = 'none';
}

function confirmCheckout() {
    // Aquí iría el fetch/POST al endpoint de pedido
    closeCheckoutModal();
    toggleCart();
    clearCart();
    showToast('¡Pedido realizado correctamente! Te contactaremos pronto.', 'success');
}

// =================== FILTROS ===================
let currentCategory = '{{ request('categoria', 'todos') }}';

function setCategoryFilter(cat) {
    currentCategory = cat;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        const active = btn.dataset.category === cat;
        btn.style.borderColor = active ? 'var(--primary)' : 'var(--border)';
        btn.style.background = active ? 'color-mix(in oklch, var(--primary) 12%, transparent)' : 'var(--card)';
        btn.style.color = active ? 'var(--primary)' : 'var(--foreground)';
        btn.style.fontWeight = active ? '600' : '400';
    });
    filterProducts();
}

function filterProducts() {
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const nameMatch = card.dataset.name.includes(q);
        const catMatch = currentCategory === 'todos' || card.dataset.category === currentCategory;
        card.style.display = (nameMatch && catMatch) ? '' : 'none';
    });
}

// =================== ADMIN DELETE ===================
let deleteProductId = null;

function confirmDelete(id, name) {
    deleteProductId = id;
    document.getElementById('delete-product-name').textContent = name;
    document.getElementById('delete-form').action = `/tienda/${id}`;
    document.getElementById('delete-modal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

// =================== TOAST ===================
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position:fixed; bottom:1.5rem; right:1.5rem; z-index:300;
        display:flex; align-items:center; gap:0.625rem;
        padding:0.875rem 1.25rem; border-radius:var(--radius);
        background:var(--card); border:1px solid var(--border);
        box-shadow:0 4px 24px rgba(0,0,0,0.12);
        font-size:0.875rem; font-weight:500;
        animation:slideInToast 200ms ease;
        max-width:22rem;
    `;
    const icon = type === 'success' ? 'check-circle' : 'alert-circle';
    const color = type === 'success' ? 'var(--primary)' : 'var(--destructive)';
    toast.innerHTML = `<i data-lucide="${icon}" style="width:1rem;height:1rem;color:${color};flex-shrink:0;"></i>${message}`;
    document.body.appendChild(toast);
    if (window.lucide) lucide.createIcons();
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 300ms'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// Init
document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    const style = document.createElement('style');
    style.textContent = `@keyframes slideInToast { from { transform:translateY(1rem); opacity:0; } to { transform:translateY(0); opacity:1; } }`;
    document.head.appendChild(style);
});
</script>
@endsection
