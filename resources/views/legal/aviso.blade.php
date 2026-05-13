@extends('layout.masterpage')

@section('title', 'Aviso Legal')

@section('content')
<div style="max-width: 52rem; margin: 0 auto; padding: 2rem 0 4rem;">

    {{-- Header --}}
    <div style="margin-bottom: 2.5rem;">
        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
            <div style="width:2.5rem; height:2.5rem; border-radius:var(--radius);
                background:color-mix(in oklch, var(--destructive) 12%, transparent);
                display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i data-lucide="scale" style="width:1.25rem;height:1.25rem; color:var(--destructive);"></i>
            </div>
            <div>
                <h1 style="font-size:1.75rem; font-weight:800; letter-spacing:-0.03em; margin:0; line-height:1.2;">
                    Aviso Legal
                </h1>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Última actualización: {{ date('d \d\e F \d\e Y') }}
                </p>
            </div>
        </div>
        <div style="padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--destructive) 8%, transparent);
            border:1px solid color-mix(in oklch, var(--destructive) 20%, transparent);
            border-radius:var(--radius); font-size:0.875rem; color:var(--foreground); line-height:1.6;">
            El presente Aviso Legal regula el acceso y uso de la plataforma <strong>Urbexium</strong>,
            en cumplimiento de la <strong>Ley 34/2002 de Servicios de la Sociedad de la Información (LSSI-CE)</strong>
            y demás normativa aplicable en España.
        </div>
    </div>

    @php
        $sections = [
            [
                'icon'  => 'building',
                'title' => '1. Datos identificativos del titular',
                'content' => '
                    <p>En virtud del artículo 10 de la LSSI-CE, se facilitan los siguientes datos identificativos:</p>
                    <ul>
                        <li><strong>Denominación:</strong> Urbexium</li>
                        <li><strong>Actividad:</strong> Plataforma comunitaria de exploración urbana (urbex)</li>
                        <li><strong>Domicilio:</strong> País Vasco, España</li>
                        <li><strong>Correo electrónico:</strong> legal@urbexium.com</li>
                        <li><strong>Web:</strong> urbexium.com</li>
                    </ul>
                ',
            ],
            [
                'icon'  => 'globe',
                'title' => '2. Objeto y ámbito de aplicación',
                'content' => '
                    <p>Este aviso legal se aplica a todos los usuarios que accedan o utilicen la plataforma Urbexium,
                    independientemente del dispositivo o canal de acceso utilizado.</p>
                    <p>Urbexium es una plataforma comunitaria orientada a la exploración urbana (urbex) que permite a sus
                    usuarios publicar, descubrir y valorar localizaciones de interés. El acceso a la plataforma implica
                    la aceptación plena de este aviso legal, la política de privacidad y la política de cookies.</p>
                ',
            ],
            [
                'icon'  => 'user-check',
                'title' => '3. Condiciones de uso',
                'content' => '
                    <p>El usuario se compromete a hacer un uso lícito, correcto y adecuado de la plataforma y, en particular, a:</p>
                    <ul>
                        <li>No publicar contenido ilícito, ofensivo, discriminatorio, difamatorio o que vulnere derechos de terceros.</li>
                        <li>No facilitar información falsa ni suplantar la identidad de otra persona.</li>
                        <li>No realizar acciones que puedan dañar, sobrecargar o deteriorar los sistemas o servicios de Urbexium.</li>
                        <li>No reproducir, distribuir ni explotar comercialmente los contenidos de la plataforma sin autorización expresa.</li>
                        <li>Respetar la normativa aplicable en materia de propiedad intelectual, protección de datos y seguridad informática.</li>
                    </ul>
                    <p>El incumplimiento de estas condiciones podrá dar lugar a la suspensión o cancelación de la cuenta del usuario.</p>
                ',
            ],
            [
                'icon'  => 'alert-triangle',
                'title' => '4. Actividades de exploración urbana — exención de responsabilidad',
                'content' => '
                    <p>Urbexium es una plataforma de comunidad e información. <strong>No organiza, promueve ni facilita
                    el acceso no autorizado a propiedades privadas o con acceso restringido.</strong></p>
                    <p>Los spots publicados por los usuarios son aportaciones de la comunidad y no suponen una
                    invitación, recomendación ni autorización para acceder a dichos lugares. El usuario es
                    <strong>exclusivamente responsable</strong> de sus actos durante cualquier actividad de exploración,
                    debiendo respetar en todo momento:</p>
                    <ul>
                        <li>La legislación penal y civil vigente (allanamiento de morada, daños, etc.).</li>
                        <li>Los derechos de propiedad de terceros.</li>
                        <li>Las normativas de seguridad y salud aplicables.</li>
                    </ul>
                    <p>Urbexium queda exenta de cualquier responsabilidad derivada de accidentes, daños, sanciones o
                    consecuencias legales que puedan sufrir los usuarios durante sus actividades.</p>
                ',
            ],
            [
                'icon'  => 'copyright',
                'title' => '5. Propiedad intelectual e industrial',
                'content' => '
                    <p>Todos los elementos que integran el diseño, estructura, código fuente, logotipos, marcas y contenido editorial
                    de Urbexium son titularidad de Urbexium o de sus licenciantes y están protegidos por la normativa española
                    e internacional de propiedad intelectual e industrial.</p>
                    <p>Queda prohibida su reproducción, distribución, comunicación pública o transformación sin autorización
                    expresa y por escrito del titular.</p>
                    <p>El contenido generado por los usuarios (spots, fotos, comentarios) es responsabilidad de quienes lo
                    publican. Al publicar contenido en Urbexium, el usuario otorga a Urbexium una licencia no exclusiva,
                    gratuita y mundial para mostrar dicho contenido en la plataforma.</p>
                ',
            ],
            [
                'icon'  => 'link',
                'title' => '6. Enlaces a terceros',
                'content' => '
                    <p>La plataforma puede incluir enlaces a sitios web de terceros. Urbexium no controla dichos sitios y no
                    asume ninguna responsabilidad sobre su contenido, disponibilidad o políticas de privacidad. La inclusión
                    de un enlace no implica ningún tipo de recomendación o patrocinio.</p>
                ',
            ],
            [
                'icon'  => 'wifi-off',
                'title' => '7. Disponibilidad del servicio',
                'content' => '
                    <p>Urbexium se esfuerza por mantener el servicio disponible de forma continua, pero no garantiza
                    la disponibilidad ininterrumpida de la plataforma. Podemos interrumpir o restringir el acceso
                    temporalmente por razones de mantenimiento, seguridad o fuerza mayor, sin previo aviso y sin
                    que ello genere derecho a indemnización alguna.</p>
                ',
            ],
            [
                'icon'  => 'map-pin',
                'title' => '8. Legislación aplicable y jurisdicción',
                'content' => '
                    <p>Este aviso legal se rige por la legislación española. Para la resolución de cualquier controversia
                    derivada del acceso o uso de la plataforma, las partes se someten, con renuncia expresa a cualquier
                    otro fuero que pudiera corresponderles, a los juzgados y tribunales de <strong>Bilbao (Bizkaia)</strong>.</p>
                    <p>No obstante, si eres un consumidor en el ámbito de la Unión Europea, puedes acceder a la plataforma
                    de resolución en línea de litigios de la Comisión Europea en
                    <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener" style="color:var(--primary);">ec.europa.eu/consumers/odr</a>.</p>
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

    {{-- Pie --}}
    <div style="margin-top:2rem; padding:1rem 1.25rem; background:var(--secondary); border-radius:var(--radius);
        font-size:0.8125rem; color:var(--muted-foreground); text-align:center; line-height:1.6;">
        Consultas legales:
        <a href="mailto:legal@urbexium.com" style="color:var(--primary); text-decoration:none; font-weight:600;">
            legal@urbexium.com
        </a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.privacidad') }}" style="color:var(--primary); text-decoration:none;">Política de Privacidad</a>
        &nbsp;·&nbsp;
        <a href="{{ route('legal.cookies') }}" style="color:var(--primary); text-decoration:none;">Política de Cookies</a>
    </div>

</div>

<style>
    ul { padding-left: 1.25rem; margin: 0.5rem 0; }
    ul li { margin-bottom: 0.375rem; }
    p { margin: 0 0 0.75rem; }
    p:last-child { margin-bottom: 0; }
</style>
@endsection
