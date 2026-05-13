@extends('layout.masterpage')

@section('title', 'Política de Privacidad')

@section('content')
<div style="max-width: 52rem; margin: 0 auto; padding: 2rem 0 4rem;">

    {{-- Header --}}
    <div style="margin-bottom: 2.5rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="width:2.5rem; height:2.5rem; border-radius:var(--radius);
                background:color-mix(in oklch, var(--primary) 15%, transparent);
                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i data-lucide="shield" style="width:1.25rem;height:1.25rem; color:var(--primary);"></i>
            </div>
            <div>
                <h1 style="font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; margin:0; line-height:1.2;">
                    Política de Privacidad
                </h1>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Última actualización: {{ date('d \d\e F \d\e Y') }}
                </p>
            </div>
        </div>
        <div style="padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--primary) 8%, transparent);
            border:1px solid color-mix(in oklch, var(--primary) 25%, transparent);
            border-radius:var(--radius); font-size:0.875rem; color:var(--foreground); line-height:1.6;">
            En <strong>Urbexium</strong> nos tomamos tu privacidad muy en serio. Este documento explica qué datos
            recopilamos, cómo los usamos y qué derechos tienes sobre ellos, conforme al
            <strong>Reglamento General de Protección de Datos (RGPD)</strong> y la normativa española aplicable.
        </div>
    </div>

    {{-- Secciones --}}
    @php
        $sections = [
            [
                'icon'  => 'user',
                'title' => '1. Responsable del tratamiento',
                'content' => '
                    <p>El responsable del tratamiento de los datos personales recogidos a través de Urbexium es:</p>
                    <ul>
                        <li><strong>Denominación:</strong> Urbexium</li>
                        <li><strong>Correo de contacto:</strong> privacidad@urbexium.com</li>
                        <li><strong>Domicilio:</strong> País Vasco, España</li>
                    </ul>
                    <p>Para cualquier cuestión relacionada con la protección de datos puedes escribirnos a la dirección indicada.</p>
                ',
            ],
            [
                'icon'  => 'database',
                'title' => '2. Datos que recopilamos',
                'content' => '
                    <p>Recogemos únicamente los datos necesarios para prestar el servicio:</p>
                    <ul>
                        <li><strong>Datos de registro:</strong> nombre, dirección de correo electrónico y contraseña (almacenada de forma cifrada).</li>
                        <li><strong>Datos de perfil:</strong> fotografía de avatar y cualquier información que decidas añadir voluntariamente.</li>
                        <li><strong>Contenido generado:</strong> spots, comentarios, valoraciones y favoritos que publiques en la plataforma.</li>
                        <li><strong>Datos de uso:</strong> registros de actividad, dirección IP, tipo de dispositivo y navegador, páginas visitadas y tiempos de sesión.</li>
                        <li><strong>Datos de pago:</strong> cuando realizas una compra en la tienda, el pago es gestionado íntegramente por <strong>Stripe</strong>. Urbexium no almacena datos de tarjetas de crédito.</li>
                    </ul>
                ',
            ],
            [
                'icon'  => 'target',
                'title' => '3. Finalidad y base legal del tratamiento',
                'content' => '
                    <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                        <thead>
                            <tr style="background:var(--secondary);">
                                <th style="padding:0.625rem 0.875rem; text-align:left; border:1px solid var(--border); font-weight:600;">Finalidad</th>
                                <th style="padding:0.625rem 0.875rem; text-align:left; border:1px solid var(--border); font-weight:600;">Base legal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Gestión de la cuenta de usuario</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Ejecución de un contrato (art. 6.1.b RGPD)</td>
                            </tr>
                            <tr style="background:var(--secondary);">
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Publicación y moderación de spots</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Ejecución de un contrato (art. 6.1.b RGPD)</td>
                            </tr>
                            <tr>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Procesamiento de pedidos en la tienda</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Ejecución de un contrato (art. 6.1.b RGPD)</td>
                            </tr>
                            <tr style="background:var(--secondary);">
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Envío de notificaciones y comunicaciones del servicio</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Interés legítimo (art. 6.1.f RGPD)</td>
                            </tr>
                            <tr>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Análisis estadístico para mejorar la plataforma</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Interés legítimo (art. 6.1.f RGPD)</td>
                            </tr>
                            <tr style="background:var(--secondary);">
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Cumplimiento de obligaciones legales</td>
                                <td style="padding:0.625rem 0.875rem; border:1px solid var(--border);">Obligación legal (art. 6.1.c RGPD)</td>
                            </tr>
                        </tbody>
                    </table>
                ',
            ],
            [
                'icon'  => 'share-2',
                'title' => '4. Cesión de datos a terceros',
                'content' => '
                    <p>No vendemos ni cedemos tus datos personales a terceros con fines comerciales. Solo los compartimos cuando es estrictamente necesario:</p>
                    <ul>
                        <li><strong>Stripe Inc.</strong> — procesador de pagos, sujeto a su propia política de privacidad.</li>
                        <li><strong>Proveedores de infraestructura</strong> — servidores de alojamiento web que actúan como encargados del tratamiento bajo contrato.</li>
                        <li><strong>Autoridades competentes</strong> — cuando exista una obligación legal o requerimiento judicial.</li>
                    </ul>
                ',
            ],
            [
                'icon'  => 'clock',
                'title' => '5. Conservación de los datos',
                'content' => '
                    <p>Conservamos tus datos personales durante el tiempo necesario para las finalidades indicadas:</p>
                    <ul>
                        <li><strong>Datos de cuenta:</strong> mientras mantengas la cuenta activa y, tras su eliminación, durante el plazo legal exigible (máximo 5 años para obligaciones fiscales).</li>
                        <li><strong>Datos de actividad:</strong> 12 meses desde su generación.</li>
                        <li><strong>Registros de transacciones:</strong> 5 años conforme a la normativa fiscal española.</li>
                    </ul>
                ',
            ],
            [
                'icon'  => 'check-circle',
                'title' => '6. Tus derechos',
                'content' => '
                    <p>Bajo el RGPD tienes derecho a:</p>
                    <ul>
                        <li><strong>Acceso:</strong> conocer qué datos tratamos sobre ti.</li>
                        <li><strong>Rectificación:</strong> corregir datos inexactos o incompletos.</li>
                        <li><strong>Supresión ("derecho al olvido"):</strong> solicitar la eliminación de tus datos.</li>
                        <li><strong>Limitación:</strong> restringir el tratamiento de tus datos en determinadas circunstancias.</li>
                        <li><strong>Portabilidad:</strong> recibir tus datos en un formato estructurado y legible por máquina.</li>
                        <li><strong>Oposición:</strong> oponerte al tratamiento basado en interés legítimo.</li>
                    </ul>
                    <p>Puedes ejercer estos derechos escribiéndonos a <strong>privacidad@urbexium.com</strong>. También tienes derecho a presentar una reclamación ante la
                    <strong>Agencia Española de Protección de Datos (AEPD)</strong> en <a href="https://www.aepd.es" target="_blank" rel="noopener" style="color:var(--primary);">www.aepd.es</a>.</p>
                ',
            ],
            [
                'icon'  => 'lock',
                'title' => '7. Seguridad',
                'content' => '
                    <p>Aplicamos medidas técnicas y organizativas adecuadas para proteger tus datos frente a accesos no autorizados, pérdida o destrucción accidental, entre ellas:</p>
                    <ul>
                        <li>Transmisión de datos mediante <strong>HTTPS/TLS</strong>.</li>
                        <li>Almacenamiento de contraseñas con hash seguro (bcrypt).</li>
                        <li>Acceso a datos de producción restringido al personal autorizado.</li>
                        <li>Revisiones periódicas de seguridad.</li>
                    </ul>
                ',
            ],
            [
                'icon'  => 'refresh-cw',
                'title' => '8. Cambios en esta política',
                'content' => '
                    <p>Podemos actualizar esta política ocasionalmente. Cuando lo hagamos, publicaremos la versión actualizada en esta página con la nueva fecha de revisión.
                    Si los cambios son significativos, te lo comunicaremos por correo electrónico o mediante un aviso destacado en la plataforma.</p>
                ',
            ],
        ];
    @endphp

    @foreach($sections as $section)
    <div class="card" style="margin-bottom:1rem; padding:1.5rem 1.75rem;">
        <div style="display:flex; align-items:center; gap:0.625rem; margin-bottom:1rem;">
            <i data-lucide="{{ $section['icon'] }}" style="width:1.125rem;height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
            <h2 style="font-size:1rem; font-weight:700; margin:0; letter-spacing:-0.01em;">{{ $section['title'] }}</h2>
        </div>
        <div style="font-size:0.875rem; line-height:1.75; color:var(--foreground);">
            {!! $section['content'] !!}
        </div>
    </div>
    @endforeach

    {{-- Pie de página legal --}}
    <div style="margin-top:2rem; padding:1rem 1.25rem; background:var(--secondary); border-radius:var(--radius);
        font-size:0.8125rem; color:var(--muted-foreground); text-align:center; line-height:1.6;">
        ¿Tienes alguna duda? Escríbenos a
        <a href="mailto:privacidad@urbexium.com" style="color:var(--primary); text-decoration:none; font-weight:600;">
            privacidad@urbexium.com
        </a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.cookies') }}" style="color:var(--primary); text-decoration:none;">Política de Cookies</a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.aviso') }}" style="color:var(--primary); text-decoration:none;">Aviso Legal</a>
    </div>

</div>

<style>
    ul { padding-left: 1.25rem; margin: 0.5rem 0; }
    ul li { margin-bottom: 0.375rem; }
    p { margin: 0 0 0.75rem; }
    p:last-child { margin-bottom: 0; }
</style>
@endsection
