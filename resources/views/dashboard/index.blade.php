{{-- resources/views/dashboard/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Dashboard')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    {{-- Header --}}
    <div style="display:flex; flex-direction:column; gap:1rem;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                    Bienvenido, {{ Auth::user()->nombre ?? $user->name ?? 'Explorer' }} 👋
                </h1>
                <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                    Aquí tienes un resumen de tu actividad reciente
                </p>
            </div>
            <a href="{{ route('spots.index') }}" class="btn btn-primary">
                Explorar Spots
                <i data-lucide="arrow-right" style="width:1rem;height:1rem;"></i>
            </a>
        </div>
    </div>

    {{-- Stats Grid (1 stat card) --}}
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));">
        <div class="card stat-card" style="color:var(--primary)">
            <div class="card-header" style="display:flex; flex-direction:row; align-items:center; justify-content:space-between;">
                <p class="card-title" style="font-size:0.8125rem; font-weight:500; color:var(--muted-foreground);">
                    Spots Añadidos
                </p>
                <i data-lucide="map-pin" style="width:1.125rem;height:1.125rem; color:var(--primary);"></i>
            </div>
            <div class="card-content">
                <p style="font-size:1.875rem; font-weight:700; color:var(--card-foreground); margin:0 0 0.25rem;">
                    {{ number_format($user->spots_count ?? 0) }}
                </p>
                <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">+3 este mes</p>
            </div>
        </div>
    </div>

    {{-- Fila 1: Spots Recientes + Actividad Reciente --}}
    <div style="display:grid; gap:1rem; grid-template-columns:1fr 1fr;">

        {{-- Spots Recientes --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title">Spots Recientes</h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($recentSpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:3rem; height:3rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->image)
                            <img src="{{ $spot->image }}" alt="{{ $spot->name }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1.25rem;height:1.25rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.25rem;">
                            {{ $spot->name }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="map-pin" style="width:0.75rem;height:0.75rem; color:var(--muted-foreground);"></i>
                            <span style="font-size:0.75rem; color:var(--muted-foreground);">{{ $spot->location }}</span>
                        </div>
                    </div>
                    @php
                        $diffColors = ['facil'=>'var(--primary)', 'medio'=>'var(--accent)', 'dificil'=>'var(--destructive)'];
                        $dc = $diffColors[$spot->difficulty] ?? 'var(--muted-foreground)';
                    @endphp
                    <span class="badge" style="background:color-mix(in oklch, {{ $dc }} 15%, transparent); color:{{ $dc }};">
                        {{ ucfirst($spot->difficulty) }}
                    </span>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="map-pin" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Aún no has añadido spots</p>
                    <a href="{{ route('spots.create') }}" class="btn btn-primary" style="margin-top:0.75rem; display:inline-flex;">
                        Añadir Spot
                    </a>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Actividad Reciente --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Actividad Reciente</h2>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($recentActivity ?? [] as $activity)
                <div style="display:flex; gap:0.875rem; padding:0.75rem 0; border-bottom:1px solid var(--border);">
                    <div class="avatar"
                        style="width:2.25rem; height:2.25rem; font-size:0.875rem;
                        background:var(--primary); color:var(--primary-foreground); flex-shrink:0;">
                        {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.875rem; margin:0 0 0.25rem;">
                            <span style="font-weight:600;">{{ $activity->user->name ?? 'Usuario' }}</span>
                            {{ $activity->description }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.375rem; color:var(--muted-foreground);">
                            <i data-lucide="clock" style="width:0.75rem;height:0.75rem;"></i>
                            <span style="font-size:0.75rem;">{{ $activity->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <p style="font-size:0.875rem;">Sin actividad reciente</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Fila 2: Populares en tu Zona + Favoritos + Explorados --}}
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(3, 1fr);">

        {{-- Populares en tu Zona --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="flame" style="width:1rem;height:1rem; color:var(--accent);"></i>
                    Populares en tu Zona
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($nearbySpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:3rem; height:3rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->image)
                            <img src="{{ $spot->image }}" alt="{{ $spot->name }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1.25rem;height:1.25rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.25rem;">
                            {{ $spot->name }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="map-pin" style="width:0.75rem;height:0.75rem; color:var(--muted-foreground);"></i>
                            <span style="font-size:0.75rem; color:var(--muted-foreground);">{{ $spot->location }}</span>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:0.25rem; color:var(--muted-foreground); flex-shrink:0;">
                        <i data-lucide="users" style="width:0.75rem;height:0.75rem;"></i>
                        <span style="font-size:0.75rem;">{{ $spot->visits_count ?? 0 }}</span>
                    </div>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="flame" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">No hay spots populares cerca aún</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Mis Favoritos --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="heart" style="width:1rem;height:1rem; color:var(--destructive);"></i>
                    Mis Favoritos
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($favoriteSpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:3rem; height:3rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0;">
                        @if($spot->image)
                            <img src="{{ $spot->image }}" alt="{{ $spot->name }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1.25rem;height:1.25rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.25rem;">
                            {{ $spot->name }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="map-pin" style="width:0.75rem;height:0.75rem; color:var(--muted-foreground);"></i>
                            <span style="font-size:0.75rem; color:var(--muted-foreground);">{{ $spot->location }}</span>
                        </div>
                    </div>
                    <i data-lucide="heart"
                        style="width:1rem;height:1rem; color:var(--destructive); fill:var(--destructive); flex-shrink:0;"></i>
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
                    <i data-lucide="compass" style="width:1rem;height:1rem; color:oklch(0.55 0.15 145);"></i>
                    Spots Explorados
                </h2>
                <a href="{{ route('spots.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($exploredSpots ?? [] as $spot)
                <a href="{{ route('spots.show', $spot) }}"
                    style="display:flex; align-items:center; gap:0.875rem; padding:0.75rem 0;
                    border-bottom:1px solid var(--border); text-decoration:none; color:inherit;
                    transition:opacity 150ms;"
                    onmouseover="this.style.opacity='0.75'" onmouseout="this.style.opacity='1'">
                    <div style="width:3rem; height:3rem; border-radius:calc(var(--radius) - 2px);
                        background:var(--secondary); overflow:hidden; flex-shrink:0; position:relative;">
                        @if($spot->image)
                            <img src="{{ $spot->image }}" alt="{{ $spot->name }}"
                                style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="image" style="width:1.25rem;height:1.25rem; color:var(--muted-foreground);"></i>
                            </div>
                        @endif
                        <div style="position:absolute; bottom:0; right:0; background:oklch(0.55 0.15 145);
                            border-radius:50%; width:1.125rem; height:1.125rem;
                            display:flex; align-items:center; justify-content:center;">
                            <i data-lucide="check" style="width:0.6rem;height:0.6rem; color:#fff; stroke-width:3;"></i>
                        </div>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-weight:500; font-size:0.875rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0 0 0.25rem;">
                            {{ $spot->name }}
                        </p>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="calendar" style="width:0.75rem;height:0.75rem; color:var(--muted-foreground);"></i>
                            <span style="font-size:0.75rem; color:var(--muted-foreground);">
                                {{ $spot->pivot->explored_at ? \Carbon\Carbon::parse($spot->pivot->explored_at)->diffForHumans() : 'Explorado' }}
                            </span>
                        </div>
                    </div>
                    @php
                        $diffColors = ['facil'=>'var(--primary)', 'medio'=>'var(--accent)', 'dificil'=>'var(--destructive)'];
                        $dc = $diffColors[$spot->difficulty] ?? 'var(--muted-foreground)';
                    @endphp
                    <span class="badge" style="background:color-mix(in oklch, {{ $dc }} 15%, transparent); color:{{ $dc }};">
                        {{ ucfirst($spot->difficulty) }}
                    </span>
                </a>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="compass" style="width:2rem;height:2rem; margin-bottom:0.75rem; opacity:0.4;"></i>
                    <p style="font-size:0.875rem;">Aún no has explorado ningún spot</p>
                    <a href="{{ route('spots.index') }}" class="btn btn-primary" style="margin-top:0.75rem; display:inline-flex;">
                        Empezar a Explorar
                    </a>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
