{{-- resources/views/spots/show.blade.php --}}
@extends('layout.masterpage')

@section('title', $spot->nombre)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    .img-gallery { display:grid; gap:0.5rem; }
    .img-gallery.one  { grid-template-columns:1fr; }
    .img-gallery.two  { grid-template-columns:1fr 1fr; }
    .img-gallery.many { grid-template-columns:2fr 1fr; grid-template-rows:1fr 1fr; }
    .img-gallery img  { width:100%; height:100%; object-fit:cover; border-radius:calc(var(--radius) - 2px); cursor:pointer; transition:opacity 150ms; }
    .img-gallery img:hover { opacity:0.88; }

    /* Lightbox */
    #lightbox {
        display:none; position:fixed; inset:0; z-index:200;
        background:rgba(0,0,0,0.92); align-items:center; justify-content:center;
    }
    #lightbox.open { display:flex; }
    #lightbox img  { max-width:90vw; max-height:90vh; border-radius:var(--radius); box-shadow:0 20px 60px rgba(0,0,0,0.5); }
    #lightbox-close {
        position:absolute; top:1rem; right:1rem;
        background:rgba(255,255,255,0.15); border:none; color:#fff;
        border-radius:9999px; width:2.5rem; height:2.5rem;
        display:flex; align-items:center; justify-content:center; cursor:pointer;
        font-size:1.25rem; backdrop-filter:blur(4px);
    }

    /* Mini mapa en sidebar */
    #mini-map { height:160px; border-radius:calc(var(--radius)-2px); border:1px solid var(--border); z-index:0; }
</style>
@endpush

@section('content')
@php
    $dificultad   = $spot->dificultad ?? 'media';
    $diffLabel    = ['baja' => 'Fácil', 'media' => 'Medio', 'alta' => 'Difícil'][$dificultad] ?? 'Medio';
    $diffColor    = ['baja' => 'var(--primary)', 'media' => 'var(--accent)', 'alta' => 'var(--destructive)'][$dificultad] ?? 'var(--muted-foreground)';
    $imagenes     = $spot->imagenes;
    $imgCount     = $imagenes->count();
    $galleryClass = $imgCount === 1 ? 'one' : ($imgCount === 2 ? 'two' : 'many');
@endphp

<div style="max-width:1100px; margin:0 auto; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Breadcrumb --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">
        <a href="{{ route('spots.index') }}" style="color:var(--muted-foreground); text-decoration:none;">Spots</a>
        <i data-lucide="chevron-right" style="width:0.875rem;height:0.875rem;"></i>
        <span style="color:var(--foreground); font-weight:500;">{{ $spot->nombre }}</span>
    </div>

    <div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

        {{-- ════ Columna principal ════ --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            {{-- Galería de imágenes --}}
            @if($imgCount > 0)
            <div class="img-gallery {{ $galleryClass }}" style="height:{{ $imgCount === 1 ? '22rem' : '20rem' }};">
                @foreach($imagenes->take($imgCount === 1 ? 1 : ($imgCount === 2 ? 2 : 3)) as $i => $img)
                    @php
                        // Normalizar URL: puede ser /storage/... o una URL completa
                        $imgUrl = Str::startsWith($img->url, ['http://', 'https://'])
                            ? $img->url
                            : asset(ltrim($img->url, '/'));
                    @endphp
                    <img src="{{ $imgUrl }}"
                        alt="{{ $spot->nombre }} foto {{ $loop->iteration }}"
                        onclick="openLightbox('{{ $imgUrl }}')"
                        loading="lazy"
                        @if($i === 0 && $imgCount >= 3) style="grid-row:1/3;" @endif />
                @endforeach
                @if($imgCount > 3)
                @php
                    $extra = $imagenes->get(3);
                    $extraUrl = Str::startsWith($extra->url, ['http://', 'https://'])
                        ? $extra->url
                        : asset(ltrim($extra->url, '/'));
                @endphp
                <div style="position:relative; cursor:pointer; border-radius:calc(var(--radius) - 2px); overflow:hidden;"
                    onclick="openLightbox('{{ $extraUrl }}')">
                    <img src="{{ $extraUrl }}" alt="más fotos"
                        style="width:100%; height:100%; object-fit:cover; filter:brightness(0.35);" />
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
                        color:#fff; font-weight:700; font-size:1.25rem; flex-direction:column; gap:0.25rem;">
                        <i data-lucide="images" style="width:1.5rem;height:1.5rem;"></i>
                        +{{ $imgCount - 3 }} fotos
                    </div>
                </div>
                @endif
            </div>
            @else
            <div style="height:14rem; background:var(--secondary); border-radius:var(--radius); display:flex; align-items:center; justify-content:center; flex-direction:column; gap:0.5rem; color:var(--muted-foreground);">
                <i data-lucide="image" style="width:3rem;height:3rem; opacity:0.3;"></i>
                <span style="font-size:0.875rem; opacity:0.6;">Sin imágenes disponibles</span>
            </div>
            @endif

            {{-- Nombre + acciones --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
                <div>
                    <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem; flex-wrap:wrap;">
                        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0;">
                            {{ $spot->nombre }}
                        </h1>
                        <span class="badge" style="background:color-mix(in oklch, {{ $diffColor }} 18%, transparent); color:{{ $diffColor }}; border:1px solid color-mix(in oklch, {{ $diffColor }} 30%, transparent); font-size:0.8rem;">
                            <i data-lucide="zap" style="width:0.75rem;height:0.75rem; margin-right:0.25rem;"></i>
                            {{ $diffLabel }}
                        </span>
                        @if($spot->verification_status === 'verificada')
                        <span class="badge badge-primary">
                            <i data-lucide="check-circle" style="width:0.75rem;height:0.75rem; margin-right:0.25rem;"></i>
                            Verificado
                        </span>
                        @else
                        <span class="badge badge-muted">{{ ucfirst($spot->verification_status) }}</span>
                        @endif
                    </div>

                    <div style="display:flex; align-items:center; gap:0.5rem; color:var(--muted-foreground); font-size:0.875rem;">
                        <i data-lucide="map-pin" style="width:1rem;height:1rem; flex-shrink:0;"></i>
                        <span>
                            {{ $spot->ciudad?->nombre ?? 'Sin ciudad' }}
                            @if($spot->ciudad?->pais) — {{ $spot->ciudad->pais->nombre }} @endif
                        </span>
                    </div>
                </div>

                {{-- Botones --}}
                <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @auth
                    <button onclick="toggleFavDetail(this, {{ $spot->id }})"
                        class="btn {{ $isFavorito ? 'btn-primary' : 'btn-secondary' }}"
                        id="fav-btn"
                        title="{{ $isFavorito ? 'Quitar de favoritos' : 'Guardar en favoritos' }}">
                        <i data-lucide="bookmark"
                            style="width:1rem;height:1rem; fill:{{ $isFavorito ? 'currentColor' : 'none' }};"
                            id="fav-icon"></i>
                        <span id="fav-text">{{ $isFavorito ? 'Guardado' : 'Guardar' }}</span>
                    </button>
                    @endauth

                    @if($esModerador)
                    <a href="{{ route('spots.edit', $spot) }}" class="btn btn-secondary">
                        <i data-lucide="pencil" style="width:1rem;height:1rem;"></i>
                        Editar
                    </a>
                    <form method="POST" action="{{ route('spots.destroy', $spot) }}"
                        onsubmit="return confirm('¿Seguro que quieres eliminar este spot? Esta acción no se puede deshacer.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-destructive">
                            <i data-lucide="trash-2" style="width:1rem;height:1rem;"></i>
                            Eliminar
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Descripción --}}
            @if($spot->descripcion)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="file-text" style="width:1rem;height:1rem;"></i>
                        Descripción
                    </h2>
                </div>
                <div class="card-content">
                    <p style="font-size:0.9375rem; line-height:1.7; color:var(--card-foreground); margin:0; white-space:pre-wrap;">{{ $spot->descripcion }}</p>
                </div>
            </div>
            @endif

            {{-- Galería completa (si hay más de 3) --}}
            @if($imgCount > 3)
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="images" style="width:1rem;height:1rem;"></i>
                        Todas las fotos ({{ $imgCount }})
                    </h2>
                </div>
                <div class="card-content" style="padding-top:0;">
                    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:0.5rem;">
                        @foreach($imagenes as $img)
                        @php
                            $iUrl = Str::startsWith($img->url, ['http://', 'https://'])
                                ? $img->url
                                : asset(ltrim($img->url, '/'));
                        @endphp
                        <div style="aspect-ratio:1; border-radius:calc(var(--radius)-2px); overflow:hidden; cursor:pointer;"
                            onclick="openLightbox('{{ $iUrl }}')">
                            <img src="{{ $iUrl }}" alt="foto" loading="lazy"
                                style="width:100%;height:100%;object-fit:cover;transition:transform 200ms;"
                                onmouseover="this.style.transform='scale(1.05)'"
                                onmouseout="this.style.transform='scale(1)'" />
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Materiales --}}
            @if($spot->materiales->count())
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="backpack" style="width:1rem;height:1rem;"></i>
                        Material necesario
                    </h2>
                </div>
                <div class="card-content" style="padding-top:0;">
                    <div style="display:grid; gap:0.5rem; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));">
                        @foreach($spot->materiales as $mat)
                        <div style="display:flex; align-items:center; gap:0.625rem; padding:0.625rem 0.875rem;
                            background:var(--secondary); border-radius:calc(var(--radius) - 2px);">
                            <i data-lucide="check" style="width:0.875rem;height:0.875rem; color:var(--primary); flex-shrink:0;"></i>
                            <div>
                                <p style="font-size:0.875rem; font-weight:500; margin:0;">{{ $mat->nombre }}</p>
                                @if($mat->descripcion)
                                <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ Str::limit($mat->descripcion, 50) }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Comentarios --}}
            <div class="card">
                <div class="card-header" style="display:flex; align-items:center; justify-content:space-between;">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="message-circle" style="width:1rem;height:1rem;"></i>
                        Comentarios
                        <span class="badge badge-muted" style="font-size:0.75rem;">{{ $spot->comentarios->count() }}</span>
                    </h2>
                </div>
                <div class="card-content" style="padding-top:0; display:flex; flex-direction:column; gap:1rem;">

                    @auth
                    <form method="POST" action="{{ route('spots.comment', $spot) }}"
                        style="display:flex; flex-direction:column; gap:0.625rem;">
                        @csrf
                        <textarea name="contenido" class="input" rows="3"
                            placeholder="Añade un comentario sobre este spot..."
                            required style="height:auto; padding:0.625rem 0.75rem; resize:vertical;">{{ old('contenido') }}</textarea>
                        @error('contenido')
                            <p style="font-size:0.8125rem; color:var(--destructive); margin:0;">{{ $message }}</p>
                        @enderror
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn btn-primary" style="font-size:0.875rem;">
                                <i data-lucide="send" style="width:0.875rem;height:0.875rem;"></i>
                                Comentar
                            </button>
                        </div>
                    </form>
                    @if($spot->comentarios->count())
                    <div style="border-top:1px solid var(--border); padding-top:1rem;"></div>
                    @endif
                    @endauth

                    @forelse($spot->comentarios->sortByDesc('created_at') as $comentario)
                    <div style="display:flex; gap:0.75rem; padding-bottom:1rem; border-bottom:1px solid var(--border);">
                        <div class="avatar" style="width:2.25rem; height:2.25rem; font-size:0.875rem; flex-shrink:0; background:var(--primary); color:var(--primary-foreground);">
                            {{ strtoupper(substr($comentario->user->nombre ?? 'U', 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.375rem; flex-wrap:wrap;">
                                <span style="font-weight:600; font-size:0.875rem;">{{ $comentario->user->nombre ?? 'Anónimo' }}</span>
                                <span style="font-size:0.75rem; color:var(--muted-foreground);">
                                    {{ $comentario->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p style="font-size:0.9rem; line-height:1.6; margin:0;">{{ $comentario->contenido }}</p>
                        </div>
                    </div>
                    @empty
                    <div style="text-align:center; padding:2rem; color:var(--muted-foreground);">
                        <i data-lucide="message-circle" style="width:2rem;height:2rem; opacity:0.3; margin-bottom:0.5rem;"></i>
                        <p style="font-size:0.875rem; margin:0;">Sé el primero en comentar</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>{{-- fin columna principal --}}

        {{-- ════ Columna lateral ════ --}}
        <div style="display:flex; flex-direction:column; gap:1rem; position:sticky; top:5.5rem;">

            {{-- Info --}}
            <div class="card">
                <div class="card-header"><h3 class="card-title">Información</h3></div>
                <div class="card-content" style="padding-top:0; display:flex; flex-direction:column; gap:0.875rem;">

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.625rem 0; border-bottom:1px solid var(--border);">
                        <span style="font-size:0.875rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="zap" style="width:0.875rem;height:0.875rem;"></i>Dificultad
                        </span>
                        <span class="badge" style="background:color-mix(in oklch, {{ $diffColor }} 15%, transparent); color:{{ $diffColor }};">{{ $diffLabel }}</span>
                    </div>

                    @if($spot->estado)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.625rem 0; border-bottom:1px solid var(--border);">
                        <span style="font-size:0.875rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="info" style="width:0.875rem;height:0.875rem;"></i>Estado
                        </span>
                        <span style="font-size:0.875rem; font-weight:500;">{{ $spot->estado }}</span>
                    </div>
                    @endif

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.625rem 0; border-bottom:1px solid var(--border);">
                        <span style="font-size:0.875rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="shield-check" style="width:0.875rem;height:0.875rem;"></i>Verificación
                        </span>
                        <span class="badge {{ $spot->verification_status === 'verificada' ? 'badge-primary' : 'badge-muted' }}">
                            {{ ucfirst($spot->verification_status) }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.625rem 0; border-bottom:1px solid var(--border);">
                        <span style="font-size:0.875rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="user" style="width:0.875rem;height:0.875rem;"></i>Añadido por
                        </span>
                        <div style="display:flex; align-items:center; gap:0.375rem;">
                            <div class="avatar" style="width:1.25rem;height:1.25rem;font-size:0.625rem;background:var(--primary);color:var(--primary-foreground);">
                                {{ strtoupper(substr($spot->user->nombre ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-size:0.875rem; font-weight:500;">{{ $spot->user->nombre ?? 'Anónimo' }}</span>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; padding:0.625rem 0;">
                        <span style="font-size:0.875rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.5rem;">
                            <i data-lucide="calendar" style="width:0.875rem;height:0.875rem;"></i>Añadido
                        </span>
                        <span style="font-size:0.875rem;">{{ $spot->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Mini mapa + coordenadas --}}
            @if($spot->latitud && $spot->longitud)
            <div class="card" style="overflow:hidden;">
                <div class="card-header">
                    <h3 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="navigation" style="width:1rem;height:1rem;"></i>
                        Ubicación
                    </h3>
                </div>
                <div class="card-content" style="padding-top:0;">
                    <div id="mini-map" style="margin-bottom:0.75rem;"></div>
                    <div style="background:var(--secondary); border-radius:calc(var(--radius)-2px); padding:0.75rem; font-family:monospace; font-size:0.8125rem; margin-bottom:0.75rem;">
                        <div style="color:var(--muted-foreground); margin-bottom:0.25rem;">LAT: <span style="color:var(--foreground); font-weight:500;">{{ $spot->latitud }}</span></div>
                        <div style="color:var(--muted-foreground);">LON: <span style="color:var(--foreground); font-weight:500;">{{ $spot->longitud }}</span></div>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $spot->latitud }},{{ $spot->longitud }}"
                        target="_blank" rel="noopener"
                        class="btn btn-secondary" style="width:100%; justify-content:center; font-size:0.875rem;">
                        <i data-lucide="external-link" style="width:0.875rem;height:0.875rem;"></i>
                        Abrir en Google Maps
                    </a>
                </div>
            </div>
            @endif

        </div>{{-- fin columna lateral --}}
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()">
    <button id="lightbox-close" onclick="closeLightbox()">✕</button>
    <img id="lightbox-img" src="" alt="Foto ampliada" onclick="event.stopPropagation()" />
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Lightbox ──
    function openLightbox(url) {
        document.getElementById('lightbox-img').src = url;
        document.getElementById('lightbox').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeLightbox() {
        document.getElementById('lightbox').classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

    // ── Favorito ──
    function toggleFavDetail(btn, spotId) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) { console.error('CSRF token not found'); return; }

        fetch(`/spots/${spotId}/fav`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(r => {
            if (!r.ok) throw new Error(`HTTP ${r.status}`);
            return r.json();
        })
        .then(data => {
            const icon   = document.getElementById('fav-icon');
            const text   = document.getElementById('fav-text');
            const favBtn = document.getElementById('fav-btn');
            if (data.favorito) {
                icon.style.fill = 'currentColor';
                text.textContent = 'Guardado';
                favBtn.classList.remove('btn-secondary');
                favBtn.classList.add('btn-primary');
            } else {
                icon.style.fill = 'none';
                text.textContent = 'Guardar';
                favBtn.classList.remove('btn-primary');
                favBtn.classList.add('btn-secondary');
            }
            lucide.createIcons();
        })
        .catch(err => {
            console.error('Error favorito:', err);
            // Mostrar error inline en vez de alert
            const favBtn = document.getElementById('fav-btn');
            favBtn.style.outline = '2px solid var(--destructive)';
            setTimeout(() => favBtn.style.outline = '', 2000);
        });
    }

    // ── Mini mapa ──
    @if($spot->latitud && $spot->longitud)
    (function() {
        const isDark = document.documentElement.classList.contains('dark');
        const tileUrl = isDark
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

        const map = L.map('mini-map', {
            zoomControl: false,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            dragging: false,
            attributionControl: false,
        }).setView([{{ $spot->latitud }}, {{ $spot->longitud }}], 14);

        L.tileLayer(tileUrl, { maxZoom: 19 }).addTo(map);

        const pinIcon = L.divIcon({
            html: `<div style="width:20px;height:20px;background:var(--primary,#22c55e);border:2px solid white;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 2px 8px rgba(0,0,0,0.3);"></div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 20],
        });

        L.marker([{{ $spot->latitud }}, {{ $spot->longitud }}], { icon: pinIcon }).addTo(map);
    })();
    @endif
</script>
@endpush
