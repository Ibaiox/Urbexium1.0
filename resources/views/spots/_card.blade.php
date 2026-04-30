{{-- resources/views/spots/_card.blade.php --}}
@php
    $diffColors = [
        'facil'   => 'var(--primary)',
        'medio'   => 'var(--accent)',
        'dificil' => 'var(--destructive)',
    ];
    $dc = $diffColors[$spot->difficulty ?? 'facil'] ?? 'var(--muted-foreground)';
@endphp

<div class="card" style="overflow:hidden; transition:transform 200ms, box-shadow 200ms;"
    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.15)'"
    onmouseout="this.style.transform=''; this.style.boxShadow=''">

    {{-- Image --}}
    <div style="position:relative; height:11rem; background:var(--secondary); overflow:hidden;">
        @if($spot->image)
            <img src="{{ $spot->image }}" alt="{{ $spot->name }}"
                style="width:100%; height:100%; object-fit:cover;" />
        @else
            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;">
                <i data-lucide="image" style="width:3rem;height:3rem; color:var(--muted-foreground); opacity:0.4;"></i>
            </div>
        @endif

        {{-- Difficulty badge overlay --}}
        <div style="position:absolute; top:0.75rem; right:0.75rem;">
            <span class="badge"
                style="background:color-mix(in oklch, {{ $dc }} 20%, transparent);
                color:{{ $dc }};
                backdrop-filter:blur(8px);
                border:1px solid color-mix(in oklch, {{ $dc }} 30%, transparent);">
                {{ ucfirst($spot->difficulty ?? 'Fácil') }}
            </span>
        </div>

        {{-- Saved/Bookmark button --}}
        <button class="btn btn-ghost btn-icon"
            style="position:absolute; top:0.5rem; left:0.5rem;
            background:color-mix(in oklch, var(--background) 60%, transparent);
            backdrop-filter:blur(8px);"
            title="Guardar">
            <i data-lucide="bookmark" style="width:1rem;height:1rem;"></i>
        </button>
    </div>

    {{-- Content --}}
    <div style="padding:1rem;">
        <a href="{{ route('spots.show', $spot) }}"
            style="text-decoration:none; color:inherit;">
            <h3 style="font-size:1rem; font-weight:600; margin:0 0 0.375rem; line-height:1.3;
                overflow:hidden; display:-webkit-box; -webkit-line-clamp:1; -webkit-box-orient:vertical;">
                {{ $spot->name }}
            </h3>
        </a>

        {{-- Location --}}
        <div style="display:flex; align-items:center; gap:0.375rem; margin-bottom:0.625rem;">
            <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground); flex-shrink:0;"></i>
            <span style="font-size:0.8125rem; color:var(--muted-foreground);
                white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                {{ $spot->location }}
            </span>
        </div>

        {{-- Description --}}
        @if($spot->description)
        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0 0 0.875rem;
            overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; line-height:1.5;">
            {{ $spot->description }}
        </p>
        @endif

        {{-- Footer row --}}
        <div style="display:flex; align-items:center; justify-content:space-between; padding-top:0.75rem; border-top:1px solid var(--border);">
            {{-- Author --}}
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <div class="avatar"
                    style="width:1.5rem; height:1.5rem; font-size:0.6875rem;
                    background:var(--primary); color:var(--primary-foreground);">
                    {{ strtoupper(substr($spot->user->name ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size:0.75rem; color:var(--muted-foreground);">
                    {{ $spot->user->name ?? 'Anónimo' }}
                </span>
            </div>

            {{-- Stats --}}
            <div style="display:flex; align-items:center; gap:0.875rem;">
                <span style="display:flex; align-items:center; gap:0.25rem; font-size:0.75rem; color:var(--muted-foreground);">
                    <i data-lucide="star" style="width:0.875rem;height:0.875rem; color:var(--accent);"></i>
                    {{ $spot->rating ?? '—' }}
                </span>
                <span style="display:flex; align-items:center; gap:0.25rem; font-size:0.75rem; color:var(--muted-foreground);">
                    <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                    {{ $spot->views_count ?? 0 }}
                </span>
            </div>
        </div>
    </div>
</div>
