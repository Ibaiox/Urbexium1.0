@extends('layout.masterpage')

@section('title', 'Política de Cookies')

@section('content')
<div style="max-width: 52rem; margin: 0 auto; padding: 2rem 0 4rem;">

    {{-- Header --}}
    <div style="margin-bottom: 2.5rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="width:2.5rem; height:2.5rem; border-radius:var(--radius);
                background:color-mix(in oklch, var(--accent) 20%, transparent);
                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i data-lucide="cookie" style="width:1.25rem;height:1.25rem; color:var(--accent);"></i>
            </div>
            <div>
                <h1 style="font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; margin:0; line-height:1.2;">
                    Política de Cookies
                </h1>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Última actualización: {{ date('d \d\e F \d\e Y') }}
                </p>
            </div>
        </div>
        <div style="padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--accent) 10%, transparent);
            border:1px solid color-mix(in oklch, var(--accent) 30%, transparent);
            border-radius:var(--radius); font-size:0.875rem; color:var(--foreground); line-height:1.6;">
            Esta política explica qué son las cookies, cuáles utiliza <strong>Urbexium</strong> y cómo puedes
            gestionarlas, en cumplimiento de la <strong>Ley 34/2002, de Servicios de la Sociedad de la Información
            (LSSI)</strong> y el <strong>RGPD</strong>.
        </div>
    </div>

    {{-- Sección 1: Qué son --}}
    <div class="card" style="margin-bottom:1rem; padding:1.5rem 1.75rem;">
        <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:1rem;">
            <i data-lucide="info" style="width:1.125rem;height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
            <h2 style="font-size:1rem; font-weight:700; margin:0;">1. ¿Qué son las cookies?</h2>
        </div>
        <p style="font-size:0.875rem; line-height:1.75; margin:0;">
            Las cookies son pequeños archivos de texto que se almacenan en tu dispositivo cuando visitas un sitio web.
            Permiten al sitio recordar tus preferencias, mantener tu sesión activa y recopilar información estadística
            sobre el uso de la plataforma. No todas las cookies implican tratamiento de datos personales.
        </p>
    </div>

    {{-- Tabla de cookies --}}
    <div class="card" style="margin-bottom:1rem; padding:1.5rem 1.75rem;">
        <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:1.25rem;">
            <i data-lucide="table" style="width:1.125rem;height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
            <h2 style="font-size:1rem; font-weight:700; margin:0;">2. Cookies que utilizamos</h2>
        </div>

        {{-- Cookies técnicas --}}
        <h3 style="font-size:0.9375rem; font-weight:600; margin:0 0 0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <span style="display:inline-block; width:0.625rem; height:0.625rem; border-radius:50%; background:var(--primary);"></span>
            Cookies técnicas (necesarias)
        </h3>
        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0 0 0.875rem; line-height:1.6;">
            Imprescindibles para el funcionamiento de la plataforma. No requieren consentimiento.
        </p>
        <div style="overflow-x:auto; margin-bottom:1.5rem;">
            <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                <thead>
                    <tr style="background:var(--secondary);">
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Nombre</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Finalidad</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Duración</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-family:monospace; font-size:0.8rem;">urbexium_session</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Mantiene la sesión del usuario autenticado</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Sesión</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);"><span class="badge badge-primary">Propia</span></td>
                    </tr>
                    <tr style="background:var(--secondary);">
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-family:monospace; font-size:0.8rem;">XSRF-TOKEN</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Protección contra ataques CSRF</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Sesión</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);"><span class="badge badge-primary">Propia</span></td>
                    </tr>
                    <tr>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-family:monospace; font-size:0.8rem;">remember_web_*</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Función "Recuérdame" al iniciar sesión</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">400 días</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);"><span class="badge badge-primary">Propia</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Cookies de preferencias --}}
        <h3 style="font-size:0.9375rem; font-weight:600; margin:0 0 0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <span style="display:inline-block; width:0.625rem; height:0.625rem; border-radius:50%; background:var(--accent);"></span>
            Cookies de preferencias
        </h3>
        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0 0 0.875rem; line-height:1.6;">
            Recuerdan tus configuraciones para mejorar tu experiencia. Se almacenan en <code style="background:var(--secondary); padding:0.1rem 0.35rem; border-radius:0.25rem; font-size:0.75rem;">localStorage</code> del navegador.
        </p>
        <div style="overflow-x:auto; margin-bottom:1.5rem;">
            <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                <thead>
                    <tr style="background:var(--secondary);">
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Clave</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Finalidad</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Duración</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-family:monospace; font-size:0.8rem;">darkMode</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Guardar preferencia de tema (oscuro/claro)</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Persistente</td>
                    </tr>
                    <tr style="background:var(--secondary);">
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-family:monospace; font-size:0.8rem;">sidebarCollapsed</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Guardar estado del menú lateral</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Persistente</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Cookies de terceros --}}
        <h3 style="font-size:0.9375rem; font-weight:600; margin:0 0 0.75rem; display:flex; align-items:center; gap:0.5rem;">
            <span style="display:inline-block; width:0.625rem; height:0.625rem; border-radius:50%; background:var(--destructive);"></span>
            Cookies de terceros
        </h3>
        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0 0 0.875rem; line-height:1.6;">
            Generadas por servicios externos que integramos en la plataforma.
        </p>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.8125rem;">
                <thead>
                    <tr style="background:var(--secondary);">
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Proveedor</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Finalidad</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left; border:1px solid var(--border); font-weight:600;">Más info</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-weight:600;">Stripe</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Procesamiento seguro de pagos</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">
                            <a href="https://stripe.com/es/privacy" target="_blank" rel="noopener" style="color:var(--primary); font-size:0.8rem;">stripe.com/privacy</a>
                        </td>
                    </tr>
                    <tr style="background:var(--secondary);">
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border); font-weight:600;">Leaflet / Tiles</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">Renderizado de mapas interactivos</td>
                        <td style="padding:0.5rem 0.75rem; border:1px solid var(--border);">
                            <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" style="color:var(--primary); font-size:0.8rem;">OpenStreetMap</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Cómo gestionar --}}
    <div class="card" style="margin-bottom:1rem; padding:1.5rem 1.75rem;">
        <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:1rem;">
            <i data-lucide="settings" style="width:1.125rem;height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
            <h2 style="font-size:1rem; font-weight:700; margin:0;">3. Cómo gestionar o eliminar las cookies</h2>
        </div>
        <p style="font-size:0.875rem; line-height:1.75; margin:0 0 1rem;">
            Puedes controlar y eliminar las cookies desde la configuración de tu navegador. Ten en cuenta que
            deshabilitar las cookies técnicas puede afectar al funcionamiento correcto de la plataforma.
        </p>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(10rem, 1fr)); gap:0.625rem;">
            @foreach([
                ['Chrome', 'https://support.google.com/chrome/answer/95647'],
                ['Firefox', 'https://support.mozilla.org/es/kb/Borrar%20cookies'],
                ['Safari', 'https://support.apple.com/es-es/guide/safari/sfri11471/mac'],
                ['Edge', 'https://support.microsoft.com/es-es/microsoft-edge/eliminar-las-cookies-en-microsoft-edge'],
            ] as [$browser, $url])
            <a href="{{ $url }}" target="_blank" rel="noopener"
                style="display:flex; align-items:center; gap:0.5rem; padding:0.625rem 0.875rem;
                    background:var(--secondary); border-radius:var(--radius); text-decoration:none;
                    font-size:0.8125rem; color:var(--foreground); font-weight:500;
                    transition:background 150ms;"
                onmouseover="this.style.background='color-mix(in oklch, var(--primary) 10%, var(--secondary))'"
                onmouseout="this.style.background='var(--secondary)'">
                <i data-lucide="external-link" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground);"></i>
                {{ $browser }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Pie --}}
    <div style="margin-top:2rem; padding:1rem 1.25rem; background:var(--secondary); border-radius:var(--radius);
        font-size:0.8125rem; color:var(--muted-foreground); text-align:center; line-height:1.6;">
        ¿Dudas sobre el uso de cookies? Escríbenos a
        <a href="mailto:privacidad@urbexium.com" style="color:var(--primary); text-decoration:none; font-weight:600;">
            privacidad@urbexium.com
        </a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.privacidad') }}" style="color:var(--primary); text-decoration:none;">Política de Privacidad</a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.aviso') }}" style="color:var(--primary); text-decoration:none;">Aviso Legal</a>
    </div>

</div>

<style>
    p { margin: 0 0 0.75rem; }
    p:last-child { margin-bottom: 0; }
</style>
@endsection
