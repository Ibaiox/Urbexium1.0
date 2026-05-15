{{-- resources/views/comunidades/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Comunidades')

@section('content')
<div style="display:flex; flex-direction:column; gap:2rem; max-width:1400px; margin:0 auto; width:100%;">

    {{-- Header --}}
    <div style="display:flex; flex-direction:column; gap:0.5rem;">
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0;">
            Comunidades
        </h1>
        <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
            Únete a grupos de exploradores de tu zona
        </p>
    </div>

    {{-- Proximamente Hero --}}
    <div style="
        display:flex; flex-direction:column; align-items:center; justify-content:center;
        gap:2rem; padding:5rem 2rem;
        background:var(--card); border:1px solid var(--border);
        border-radius:var(--radius); text-align:center;
        position:relative; overflow:hidden;
    ">
        {{-- Decorative background circles --}}
        <div style="
            position:absolute; top:-4rem; left:-4rem;
            width:18rem; height:18rem; border-radius:50%;
            background:color-mix(in oklch, var(--primary) 8%, transparent);
            pointer-events:none;
        "></div>
        <div style="
            position:absolute; bottom:-5rem; right:-3rem;
            width:22rem; height:22rem; border-radius:50%;
            background:color-mix(in oklch, var(--accent) 6%, transparent);
            pointer-events:none;
        "></div>

        {{-- Icon --}}
        <div style="
            position:relative; z-index:1;
            display:flex; align-items:center; justify-content:center;
            width:6rem; height:6rem; border-radius:50%;
            background:color-mix(in oklch, var(--primary) 12%, transparent);
            border:2px solid color-mix(in oklch, var(--primary) 30%, transparent);
        ">
            <i data-lucide="users" style="width:2.5rem; height:2.5rem; color:var(--primary);"></i>
        </div>

        {{-- Text content --}}
        <div style="position:relative; z-index:1; display:flex; flex-direction:column; gap:1rem; max-width:36rem;">
            <div style="display:flex; align-items:center; justify-content:center; gap:0.75rem; flex-wrap:wrap;">
                <h2 style="font-size:2rem; font-weight:700; letter-spacing:-0.02em; margin:0;">
                    Próximamente
                </h2>
                <span style="
                    display:inline-flex; align-items:center; gap:0.375rem;
                    padding:0.25rem 0.875rem; border-radius:9999px;
                    background:color-mix(in oklch, var(--primary) 12%, transparent);
                    color:var(--primary); font-size:0.75rem; font-weight:600;
                    letter-spacing:0.05em; text-transform:uppercase;
                ">
                    <i data-lucide="sparkles" style="width:0.875rem; height:0.875rem;"></i>
                    En desarrollo
                </span>
            </div>

            <p style="color:var(--muted-foreground); font-size:1.0625rem; line-height:1.7; margin:0;">
                Las comunidades de Urbexium están en construcción. Pronto podrás unirte a grupos
                de exploradores de tu ciudad, compartir spots exclusivos, chatear en tiempo real
                y organizar salidas con otros urbexers.
            </p>
        </div>

        {{-- Feature preview cards --}}
        <div style="
            position:relative; z-index:1;
            display:grid; gap:1rem;
            grid-template-columns:repeat(auto-fit, minmax(14rem, 1fr));
            width:100%; max-width:52rem;
        ">
            @php
                $features = [
                    ['icon' => 'message-square', 'label' => 'Chat en tiempo real', 'desc' => 'Habla con exploradores de tu zona'],
                    ['icon' => 'map-pin',         'label' => 'Spots exclusivos',    'desc' => 'Accede a localizaciones privadas de la comunidad'],
                    ['icon' => 'calendar',        'label' => 'Organiza salidas',    'desc' => 'Coordina exploraciones grupales'],
                    ['icon' => 'shield',          'label' => 'Moderación segura',   'desc' => 'Comunidades verificadas y gestionadas'],
                ];
            @endphp

            @foreach($features as $f)
            <div style="
                display:flex; flex-direction:column; gap:0.75rem;
                padding:1.25rem; text-align:left;
                background:var(--secondary); border-radius:calc(var(--radius) - 2px);
                border:1px solid var(--border);
            ">
                <div style="
                    display:inline-flex; align-items:center; justify-content:center;
                    width:2.5rem; height:2.5rem; border-radius:calc(var(--radius) - 4px);
                    background:color-mix(in oklch, var(--primary) 12%, transparent);
                ">
                    <i data-lucide="{{ $f['icon'] }}" style="width:1.25rem; height:1.25rem; color:var(--primary);"></i>
                </div>
                <div>
                    <p style="font-weight:600; font-size:0.875rem; margin:0 0 0.25rem;">{{ $f['label'] }}</p>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0; line-height:1.5;">{{ $f['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div style="position:relative; z-index:1;">
            <p style="font-size:0.875rem; color:var(--muted-foreground); margin:0 0 0.75rem;">
                ¿Quieres ser de los primeros en enterarte?
            </p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i data-lucide="arrow-left" style="width:1rem; height:1rem;"></i>
                Volver al inicio
            </a>
        </div>
    </div>

</div>
@endsection
