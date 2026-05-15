{{-- resources/views/dashboard/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Dashboard')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px; margin:0 auto; width:100%;">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                @auth
                    Bienvenido, {{ Auth::user()->nombre ?? 'Explorer' }}
                @else
                    Explora lo inexplorado
                @endauth
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                @auth
                    Aquí tienes un resumen de tu actividad reciente
                @else
                    Descubre localizaciones únicas de exploración urbana
                @endauth
            </p>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            @guest
            <a href="{{ route('login') }}" class="btn btn-ghost" style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                Iniciar sesión
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary">
                <i data-lucide="user-plus" style="width:0.9rem;height:0.9rem;"></i>
                Registrarse
            </a>
            @endguest
            @auth
            <a href="{{ route('spots.index') }}" class="btn btn-primary">
                Explorar Spots
                <i data-lucide="arrow-right" style="width:1rem;height:1rem;"></i>
            </a>
            @endauth
        </div>
    </div>

    {{-- Banner para guests --}}
    @guest
    <div style="padding:1.25rem 1.5rem; background:color-mix(in oklch, var(--primary) 8%, transparent);
        border:1px solid color-mix(in oklch, var(--primary) 25%, transparent);
        border-radius:var(--radius); display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
        <i data-lucide="info" style="width:1.25rem;height:1.25rem; color:var(--primary); flex-shrink:0;"></i>
        <p style="margin:0; font-size:0.875rem; flex:1; min-width:12rem;">
            <strong>¡Únete a la comunidad!</strong> Inicia sesión para ver tus favoritos, dejar comentarios, subir spots y mucho más.
        </p>
        <a href="{{ route('register') }}" class="btn btn-primary" style="font-size:0.8125rem; padding:0.4rem 0.875rem; white-space:nowrap;">
            Crear cuenta gratis
        </a>
    </div>
    @endguest

    {{-- Fila 1: Spots recientes + Actividad (solo auth) --}}
    @auth
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fill, minmax(min(100%, 400px), 1fr));">

        {{-- Vistos recientemente --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="eye" style="width:1rem;height:1rem; color:var(--primary);"></i>
                    Vistos recientemente
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($recentSpots ?? [] as $actividad)
                @php $spot = $actividad->localizacion; @endphp
                @if($spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:3rem; height:3rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->imagenPrincipal)
                            <img src="{{ $spot->imagenPrincipal }}" alt="{{ $spot->nombre }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1.25rem;height:1.25rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.2rem;">
                            {{ $spot->nombre }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.375rem; color:var(--muted-foreground);">
                            <i data-lucide="clock" style="width:0.7rem;height:0.7rem;"></i>
                            <span style="font-size:0.72rem;">{{ $actividad->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    @php
                        $dc = match($spot->dificultad) { 'baja'=>'var(--primary)', 'media'=>'var(--accent)', 'alta'=>'var(--destructive)', default=>'var(--muted-foreground)' };
                    @endphp
                    <span class="badge" style="background:color-mix(in oklch, {{ $dc }} 15%, transparent); color:{{ $dc }}; font-size:0.7rem;">
                        {{ ucfirst($spot->dificultad) }}
                    </span>
                </a>
                @endif
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="eye-off" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Aún no has visto ningún spot</p>
                    <a href="{{ route('spots.index') }}" class="btn btn-primary" style="margin-top:0.75rem; display:inline-flex;">
                        Explorar ahora
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Actividad Reciente --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="activity" style="width:1rem;height:1rem; color:var(--primary);"></i>
                    Mi actividad
                </h2>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($recentActivity ?? [] as $actividad)
                <div style="display:flex; gap:0.75rem; padding:0.7rem 0; border-bottom:1px solid var(--border); align-items:flex-start;">
                    <div style="width:2rem; height:2rem; border-radius:50%; flex-shrink:0;
                        display:flex; align-items:center; justify-content:center;
                        background:color-mix(in oklch, {{ $actividad->color() }} 12%, transparent);">
                        <i data-lucide="{{ $actividad->icono() }}"
                            style="width:0.9rem;height:0.9rem; color:{{ $actividad->color() }};"></i>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.8125rem; margin:0 0 0.2rem; line-height:1.4;">
                            {{ $actividad->descripcion }}
                        </p>
                        <span style="font-size:0.7rem; color:var(--muted-foreground);">
                            {{ $actividad->created_at->diffForHumans() }}
                        </span>
                    </div>
                    @if($actividad->localizacion_id && $actividad->localizacion)
                    <a href="{{ route('spots.show', $actividad->localizacion_id) }}"
                        style="color:var(--muted-foreground); flex-shrink:0; margin-top:0.125rem;"
                        title="Ver spot">
                        <i data-lucide="external-link" style="width:0.8rem;height:0.8rem;"></i>
                    </a>
                    @endif
                </div>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="activity" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Sin actividad reciente.<br>¡Empieza explorando spots!</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
    @endauth

    {{-- Fila 2: Populares + Favoritos + Explorados --}}
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fill, minmax(min(100%, {{ Auth::check() ? '280px' : '100%' }}), 1fr));">

        {{-- Populares en la plataforma --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="flame" style="width:1rem;height:1rem; color:var(--accent);"></i>
                    Spots destacados
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($nearbySpots ?? [] as $spot)
                {{-- Para guests, el link va a login en vez de al spot --}}
                @php $spotUrl = Auth::check() ? route('spots.show', $spot) : route('login'); @endphp
                <a href="{{ $spotUrl }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:2.75rem; height:2.75rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->imagenPrincipal)
                            <img src="{{ $spot->imagenPrincipal }}" alt="{{ $spot->nombre }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1rem;height:1rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.8125rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.2rem;">
                            {{ $spot->nombre }}
                        </p>
                        <span style="font-size:0.72rem; color:var(--muted-foreground);">{{ $spot->ciudad?->nombre ?? '—' }}</span>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.25rem; color:var(--muted-foreground); flex-shrink:0;">
                        <i data-lucide="message-circle" style="width:0.75rem;height:0.75rem;"></i>
                        <span style="font-size:0.72rem;">{{ $spot->comentarios_count }}</span>
                    </div>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="flame" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Sin spots disponibles aún</p>
                </div>
                @endforelse
            </div>
        </div>

        @auth
        {{-- Mis Favoritos --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="heart" style="width:1rem;height:1rem; color:#ef4444;"></i>
                    Mis Favoritos
                </h2>
                <a href="{{ route('spots.favorites') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($favoriteSpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:2.75rem; height:2.75rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->imagenPrincipal)
                            <img src="{{ $spot->imagenPrincipal }}" alt="{{ $spot->nombre }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1rem;height:1rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.8125rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.2rem;">
                            {{ $spot->nombre }}
                        </p>
                        <span style="font-size:0.72rem; color:var(--muted-foreground);">{{ $spot->ciudad?->nombre ?? '—' }}</span>
                    </div>
                    <i data-lucide="heart" style="width:0.9rem;height:0.9rem; color:#ef4444; fill:#ef4444; flex-shrink:0;"></i>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="heart" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Aún no tienes favoritos</p>
                    <a href="{{ route('spots.index') }}" class="btn btn-primary" style="margin-top:0.75rem; display:inline-flex;">
                        Descubrir Spots
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Spots Explorados --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="compass" style="width:1rem;height:1rem; color:#22c55e;"></i>
                    Explorados
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($exploredSpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="position:relative; width:2.75rem; height:2.75rem; flex-shrink:0;">
                        <div style="width:100%; height:100%; border-radius:calc(var(--radius) - 2px);
                            background:var(--secondary); overflow:hidden;">
                            @if($spot->imagenPrincipal)
                                <img src="{{ $spot->imagenPrincipal }}" alt="{{ $spot->nombre }}"
                                    style="width:100%;height:100%;object-fit:cover;" />
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                    <i data-lucide="image" style="width:1rem;height:1rem; color:var(--muted-foreground);"></i>
                                </div>
                            @endif
                        </div>
                        <div style="position:absolute; bottom:-2px; right:-2px; background:#22c55e;
                            border-radius:50%; width:1rem; height:1rem;
                            display:flex; align-items:center; justify-content:center; border:2px solid var(--card);">
                            <i data-lucide="check" style="width:0.5rem;height:0.5rem; color:#fff; stroke-width:3;"></i>
                        </div>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.8125rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.2rem;">
                            {{ $spot->nombre }}
                        </p>
                        <span style="font-size:0.72rem; color:var(--muted-foreground);">{{ $spot->ciudad?->nombre ?? '—' }}</span>
                    </div>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="compass" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Aún no has explorado ningún spot</p>
                    <a href="{{ route('spots.index') }}" class="btn btn-primary" style="margin-top:0.75rem; display:inline-flex;">
                        Empezar a explorar
                    </a>
                </div>
                @endforelse
            </div>
        </div>
        @endauth

    </div>

</div>
@endsection
