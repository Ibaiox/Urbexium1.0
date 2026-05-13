{{-- resources/views/tienda/checkout.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Checkout')

@section('content')
<div style="max-width:800px; display:flex; flex-direction:column; gap:1.5rem;">

    <div>
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">Finalizar compra</h1>
        <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">Pago seguro con Stripe</p>
    </div>

    {{-- Error global --}}
    <div id="global-error" style="display:none; padding:0.875rem 1rem; border-radius:var(--radius);
        background:color-mix(in oklch,var(--destructive) 10%,transparent);
        border:1px solid color-mix(in oklch,var(--destructive) 30%,transparent);
        color:var(--destructive); font-size:0.875rem;">
    </div>

    <div style="display:grid; grid-template-columns:1fr 360px; gap:2rem; align-items:start;">

        {{-- Formulario --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            {{-- Dirección --}}
            <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem; display:flex; flex-direction:column; gap:1rem;">
                <h2 style="font-size:1rem; font-weight:700; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="map-pin" style="width:1rem; height:1rem; color:var(--primary);"></i>
                    Dirección de envío
                </h2>
                <textarea id="direccion" rows="3" placeholder="Calle, número, ciudad, código postal..."
                    style="width:100%; padding:0.75rem; border:1px solid var(--border);
                        border-radius:calc(var(--radius) - 4px); background:var(--secondary);
                        color:var(--foreground); font-family:inherit; font-size:0.875rem;
                        resize:vertical; outline:none; box-sizing:border-box;"
                    onfocus="this.style.borderColor='var(--ring)'"
                    onblur="this.style.borderColor='var(--border)'"
                ></textarea>
            </div>

            {{-- Stripe Elements (oculto hasta que se cree el PaymentIntent) --}}
            <div id="payment-section" style="display:none; background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem; flex-direction:column; gap:1rem;">
                <h2 style="font-size:1rem; font-weight:700; margin:0; display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="credit-card" style="width:1rem; height:1rem; color:var(--primary);"></i>
                    Datos de pago
                </h2>
                <div id="payment-element" style="padding:0.5rem 0;"></div>
                <div id="payment-message" style="display:none; color:var(--destructive); font-size:0.875rem; padding:0.75rem; border-radius:calc(var(--radius) - 4px); background:color-mix(in oklch,var(--destructive) 10%,transparent);"></div>
                <button id="pay-btn" onclick="handlePay()" class="btn btn-primary"
                    style="height:3rem; font-size:1rem; justify-content:center; width:100%;">
                    <span id="pay-text" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="lock" style="width:1rem; height:1rem;"></i>
                        Pagar <span id="pay-total">0,00 €</span>
                    </span>
                    <span id="pay-spinner" style="display:none; align-items:center; justify-content:center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;">
                            <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                        </svg>
                    </span>
                </button>
                <p style="text-align:center; font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                    Pago cifrado con TLS · Procesado por Stripe
                </p>
            </div>

            {{-- Botón continuar (paso 1) --}}
            <button id="continue-btn" onclick="initPayment()" class="btn btn-primary"
                style="height:3rem; font-size:1rem; justify-content:center; width:100%;">
                <span id="continue-text" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="arrow-right" style="width:1rem; height:1rem;"></i>
                    Continuar al pago
                </span>
                <span id="continue-spinner" style="display:none; align-items:center; justify-content:center;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                </span>
            </button>

            <a href="{{ route('tienda.index') }}" style="font-size:0.875rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.375rem; width:fit-content;">
                <i data-lucide="arrow-left" style="width:0.875rem; height:0.875rem;"></i>
                Volver a la tienda
            </a>
        </div>

        {{-- Resumen pedido --}}
        <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem;
            display:flex; flex-direction:column; gap:1rem; position:sticky; top:5rem;">
            <h2 style="font-size:1rem; font-weight:700; margin:0;">Resumen del pedido</h2>
            <div id="order-summary" style="display:flex; flex-direction:column; gap:0.75rem; font-size:0.875rem;">
                <p style="color:var(--muted-foreground); text-align:center; margin:0.5rem 0;">Cargando carrito...</p>
            </div>
            <div style="border-top:1px solid var(--border); padding-top:1rem; display:flex; flex-direction:column; gap:0.5rem;">
                <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--muted-foreground);">
                    <span>Subtotal</span><span id="summary-subtotal">0,00 €</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:0.875rem; color:var(--muted-foreground);">
                    <span>Envío</span><span style="color:var(--primary); font-weight:500;">Gratis</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:1.0625rem; font-weight:700; margin-top:0.25rem;">
                    <span>Total</span>
                    <span id="summary-total" style="color:var(--primary);">0,00 €</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const STRIPE_KEY    = '{{ config("services.stripe.key") }}';
const PAYMENT_INTENT_URL = '{{ route("tienda.payment-intent") }}';
const SUCCESS_URL   = '{{ route("tienda.pago-exitoso") }}';
const TIENDA_URL    = '{{ route("tienda.index") }}';
const CSRF_TOKEN    = document.querySelector('meta[name=csrf-token]').content;

const stripe = Stripe(STRIPE_KEY);
let elements, paymentElement, pedidoId;

// Al cargar: renderizar resumen o redirigir si carrito vacío
document.addEventListener('DOMContentLoaded', () => {
    const cart = JSON.parse(localStorage.getItem('urbexium_cart') || '[]');
    if (cart.length === 0) {
        window.location.href = TIENDA_URL;
        return;
    }
    renderSummary(cart);
});

// Paso 1: usuario pulsa "Continuar al pago" → crear PaymentIntent
async function initPayment() {
    const cart = JSON.parse(localStorage.getItem('urbexium_cart') || '[]');
    if (cart.length === 0) {
        showGlobalError('Tu carrito está vacío.');
        return;
    }

    setContinueLoading(true);
    hideGlobalError();

    try {
        const res = await fetch(PAYMENT_INTENT_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            body: JSON.stringify({
                items: cart.map(i => ({ id: i.id, qty: i.qty })),
                direccion_envio: document.getElementById('direccion').value,
            }),
        });

        const data = await res.json();

        if (!res.ok) {
            showGlobalError(data.message || 'Error al iniciar el pago. Inténtalo de nuevo.');
            setContinueLoading(false);
            return;
        }

        pedidoId = data.pedido_id;
        mountStripeElements(data.client_secret);

    } catch (err) {
        showGlobalError('Error de conexión. Comprueba tu internet e inténtalo de nuevo.');
        setContinueLoading(false);
    }
}

function mountStripeElements(clientSecret) {
    // Detectar tema del sistema para Stripe
    const isDark = document.documentElement.classList.contains('dark') ||
                   window.matchMedia('(prefers-color-scheme: dark)').matches;

    const appearance = {
        theme: isDark ? 'night' : 'stripe',
        variables: {
            colorPrimary: getComputedStyle(document.documentElement)
                            .getPropertyValue('--primary').trim() || '#4ade80',
            borderRadius: '0.5rem',
        },
    };

    elements      = stripe.elements({ clientSecret, appearance });
    paymentElement = elements.create('payment');
    paymentElement.mount('#payment-element');

    // Mostrar sección de pago, ocultar botón continuar
    const paySection = document.getElementById('payment-section');
    paySection.style.display = 'flex';
    document.getElementById('continue-btn').style.display = 'none';
    setContinueLoading(false);
}

// Paso 2: usuario pulsa "Pagar"
async function handlePay() {
    setPayLoading(true);
    hideGlobalError();
    hidePaymentMessage();

    // return_url: Stripe redirige aquí tras el pago (incluido 3DS).
    // Incluimos pedido_id como fallback; pagoExitoso() también acepta
    // el payment_intent que Stripe añade automáticamente a la URL.
    const returnUrl = SUCCESS_URL + '?pedido_id=' + pedidoId;

    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            return_url: returnUrl,
        },
    });

    if (error) {
        // Stripe redirige si no hay error → aquí solo llegamos en caso de fallo
        if (error.type === 'card_error' || error.type === 'validation_error') {
            showPaymentMessage(error.message);
        } else {
            showGlobalError('Error inesperado. Inténtalo de nuevo.');
        }
        setPayLoading(false);
    }
    // Si no hay error, Stripe redirige automáticamente a return_url
}

function renderSummary(cart) {
    const container = document.getElementById('order-summary');
    container.innerHTML = '';
    let total = 0;
    cart.forEach(item => {
        total += item.price * item.qty;
        const div = document.createElement('div');
        div.style.cssText = 'display:flex;justify-content:space-between;align-items:center;gap:0.5rem;';
        div.innerHTML = `
            <div style="display:flex;align-items:center;gap:0.625rem;min-width:0;">
                <div style="width:2.5rem;height:2.5rem;border-radius:0.375rem;background:var(--secondary);flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    ${item.image
                        ? `<img src="${item.image}" style="width:100%;height:100%;object-fit:cover;" alt="${item.name}">`
                        : '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="opacity:0.4;"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>'
                    }
                </div>
                <span style="font-size:0.8125rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${item.name}</span>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-size:0.8125rem;font-weight:600;">${(item.price * item.qty).toFixed(2).replace('.',',')} €</div>
                <div style="font-size:0.75rem;color:var(--muted-foreground);">×${item.qty}</div>
            </div>`;
        container.appendChild(div);
    });
    const fmt = n => n.toFixed(2).replace('.', ',') + ' €';
    document.getElementById('summary-subtotal').textContent = fmt(total);
    document.getElementById('summary-total').textContent    = fmt(total);
    document.getElementById('pay-total').textContent        = fmt(total);
}

function setContinueLoading(on) {
    document.getElementById('continue-btn').disabled = on;
    document.getElementById('continue-text').style.display   = on ? 'none' : 'flex';
    document.getElementById('continue-spinner').style.display = on ? 'flex' : 'none';
}

function setPayLoading(on) {
    document.getElementById('pay-btn').disabled = on;
    document.getElementById('pay-text').style.display   = on ? 'none' : 'flex';
    document.getElementById('pay-spinner').style.display = on ? 'flex' : 'none';
}

function showGlobalError(msg) {
    const el = document.getElementById('global-error');
    el.textContent   = msg;
    el.style.display = 'block';
    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function hideGlobalError() {
    document.getElementById('global-error').style.display = 'none';
}

function showPaymentMessage(msg) {
    const el = document.getElementById('payment-message');
    el.textContent   = msg;
    el.style.display = 'block';
}

function hidePaymentMessage() {
    document.getElementById('payment-message').style.display = 'none';
}
</script>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 700px) {
    div[style*="grid-template-columns:1fr 360px"] { grid-template-columns: 1fr !important; }
}
</style>
@endsection
