{{-- resources/views/spots/_card.blade.php --}}
@php
    use Illuminate\Support\Str;
    $dificultad = $spot->dificultad ?? 'media';
    $diffLabel  = ['baja' => 'Fácil', 'media' => 'Medio', 'alta' => 'Difícil'][$dificultad] ?? 'Medio';
    $diffColor  = ['baja' => 'var(--primary)', 'media' => 'var(--accent)', 'alta' => 'var(--destructive)'][$dificultad] ?? 'var(--muted-foreground)';

    // Normalizar URL igual que show.blade.php
    $portadaRaw = $spot->imagenes->first()?->url ?? null;
    $portada    = $portadaRaw
        ? (Str::startsWith($portadaRaw, ['http://', 'https://'])
            ? $portadaRaw
            : asset(ltrim($portadaRaw, '/')))
        : null;

    $esFav = in_array($spot->id, $favIds ?? []);
    $esMod = Auth::check() && (Auth::user()->esAdmin() || Auth::user()->esModerador());

    // Ruta de toggle favorito generada en PHP (no hardcodeada en JS)
    $favRoute = route('spots.fav', $spot);
@endphp

<div class="card spot-card"
    style="overflow:hidden; transition:transform 200ms, box-shadow 200ms; position:relative;"
    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 12px 32px rgba(0,0,0,0.18)'"
    onmouseout="this.style.transform=''; this.style.boxShadow=''">

    {{-- Imagen portada --}}
    <div style="position:relative; height:11rem; background:var(--secondary); overflow:hidden;">
        @if($portada)
            <img src="{{ $portada }}" alt="{{ $spot->nombre }}"
                style="width:100%; height:100%; object-fit:cover;"
                onerror="this.parentElement.innerHTML='<div style=\'width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'40\' height=\'40\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect width=\'18\' height=\'18\' x=\'3\' y=\'3\' rx=\'2\'/><circle cx=\'9\' cy=\'9\' r=\'2\'/><path d=\'m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21\'/></svg></div>'" />
        @else
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; flex-direction:column; gap:0.5rem; color:var(--muted-foreground); opacity:0.4;">
                <i data-lucide="image" style="width:2.5rem;height:2.5rem;"></i>
                <span style="font-size:0.75rem;">Sin imagen</span>
            </div>
        @endif

        {{-- Badge dificultad --}}
        <div style="position:absolute; top:0.75rem; right:0.75rem;">
            <span class="badge" style="
                background:color-mix(in oklch, {{ $diffColor }} 20%, transparent);
                color:{{ $diffColor }};
                backdrop-filter:blur(8px);
                border:1px solid color-mix(in oklch, {{ $diffColor }} 30%, transparent);">
                {{ $diffLabel }}
            </span>
        </div>

        {{-- Botón favorito --}}
        @auth
        <button
            data-fav-btn="{{ $spot->id }}"
            data-fav-route="{{ $favRoute }}"
            data-fav-active="{{ $esFav ? '1' : '0' }}"
            class="btn btn-ghost btn-icon spot-fav-btn"
            style="position:absolute; top:0.5rem; left:0.5rem;
                background:color-mix(in oklch, var(--background) 65%, transparent);
                backdrop-filter:blur(8px);
                color:{{ $esFav ? 'var(--primary)' : '' }};"
            title="{{ $esFav ? 'Quitar de favoritos' : 'Guardar' }}">
            <i data-lucide="bookmark"
                style="width:1rem;height:1rem; fill:{{ $esFav ? 'currentColor' : 'none' }};"></i>
        </button>
        @endauth

        {{-- Estado verificación --}}
        @if($spot->verification_status !== 'verificada')
        <div style="position:absolute; bottom:0.5rem; left:0.5rem;">
            <span class="badge badge-muted" style="font-size:0.7rem; backdrop-filter:blur(8px);">
                {{ ucfirst($spot->verification_status) }}
            </span>
        </div>
        @endif
    </div>

    {{-- Contenido --}}
    <div style="padding:1rem;">
        <a href="{{ route('spots.show', $spot) }}" style="text-decoration:none; color:inherit;">
            <h3 style="font-size:1rem; font-weight:600; margin:0 0 0.375rem; line-height:1.3;
                overflow:hidden; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical;">
                {{ $spot->nombre }}
            </h3>
        </a>

        <div style="display:flex; align-items:center; gap:0.375rem; margin-bottom:0.5rem;">
            <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground); flex-shrink:0;"></i>
            <span style="font-size:0.8125rem; color:var(--muted-foreground); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                {{ $spot->ciudad?->nombre ?? 'Ubicación desconocida' }}
                @if($spot->ciudad?->pais)
                    — {{ $spot->ciudad->pais->nombre }}
                @endif
            </span>
        </div>

        @if($spot->descripcion)
        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0 0 0.875rem;
            overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; line-height:1.5;">
            {{ $spot->descripcion }}
        </p>
        @endif

        @if($spot->materiales->count())
        <div style="display:flex; gap:0.375rem; flex-wrap:wrap; margin-bottom:0.75rem;">
            @foreach($spot->materiales->take(2) as $mat)
                <span class="badge badge-muted" style="font-size:0.7rem;">{{ $mat->nombre }}</span>
            @endforeach
            @if($spot->materiales->count() > 2)
                <span class="badge badge-muted" style="font-size:0.7rem;">+{{ $spot->materiales->count() - 2 }}</span>
            @endif
        </div>
        @endif

        <div style="display:flex; align-items:center; justify-content:space-between; padding-top:0.75rem; border-top:1px solid var(--border);">
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <div class="avatar" style="width:1.5rem; height:1.5rem; font-size:0.6875rem; background:var(--primary); color:var(--primary-foreground);">
                    {{ strtoupper(substr($spot->user->nombre ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size:0.75rem; color:var(--muted-foreground);">
                    {{ $spot->user->nombre ?? 'Anónimo' }}
                </span>
            </div>

            @if($esMod)
            <div style="display:flex; gap:0.25rem;">
                <a href="{{ route('spots.edit', $spot) }}"
                    class="btn btn-ghost btn-icon"
                    style="width:1.875rem; height:1.875rem;"
                    title="Editar">
                    <i data-lucide="pencil" style="width:0.875rem;height:0.875rem;"></i>
                </a>
                <form method="POST" action="{{ route('spots.destroy', $spot) }}"
                    onsubmit="return confirm('¿Eliminar este spot?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon"
                        style="width:1.875rem; height:1.875rem; color:var(--destructive);"
                        title="Eliminar">
                        <i data-lucide="trash-2" style="width:0.875rem;height:0.875rem;"></i>
                    </button>
                </form>
            </div>
            @else
            <span style="display:flex; align-items:center; gap:0.25rem; font-size:0.75rem; color:var(--muted-foreground);">
                <i data-lucide="message-circle" style="width:0.875rem;height:0.875rem;"></i>
                {{ $spot->comentarios->count() }}
            </span>
            @endif
        </div>
    </div>
</div>
