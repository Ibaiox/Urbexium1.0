{{--
    resources/views/components/estrellas.blade.php
    Uso: <x-estrellas :media="$spot->mediaValoracion()" :votos="$spot->valoraciones->count()" />
    Props:
        $media  — float|null  (p.ej. 4.3)
        $votos  — int         (nº de valoraciones)
        $size   — 'sm' | 'md' (por defecto 'md')
--}}
@props(['media' => null, 'votos' => 0, 'size' => 'md'])

@php
    $starSize  = $size === 'sm' ? '0.9rem' : '1.125rem';
    $fontSize  = $size === 'sm' ? '0.75rem' : '0.875rem';
    $filled    = $media ? floor($media) : 0;
    $hasHalf   = $media && ($media - $filled) >= 0.25 && ($media - $filled) < 0.75;
    $isRound   = $media && ($media - $filled) >= 0.75;
    if ($isRound) { $filled++; $hasHalf = false; }
    $empty     = 5 - $filled - ($hasHalf ? 1 : 0);
@endphp

<div style="display:inline-flex; align-items:center; gap:0.35rem; flex-wrap:wrap;">
    <div style="display:flex; gap:0.15rem;">
        @for($i = 0; $i < $filled; $i++)
            <svg width="{{ $starSize }}" height="{{ $starSize }}" viewBox="0 0 24 24" fill="var(--accent)" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        @endfor

        @if($hasHalf)
            <svg width="{{ $starSize }}" height="{{ $starSize }}" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="half-{{ uniqid() }}" x1="0" x2="1" y1="0" y2="0">
                        <stop offset="50%" stop-color="var(--accent)"/>
                        <stop offset="50%" stop-color="var(--border)"/>
                    </linearGradient>
                </defs>
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="url(#half-{{ uniqid() }})"/>
            </svg>
        @endif

        @for($i = 0; $i < $empty; $i++)
            <svg width="{{ $starSize }}" height="{{ $starSize }}" viewBox="0 0 24 24" fill="none" stroke="var(--border)" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        @endfor
    </div>

    @if($media)
        <span style="font-size:{{ $fontSize }}; font-weight:700; color:var(--accent);">{{ number_format($media, 1) }}</span>
        <span style="font-size:{{ $fontSize }}; color:var(--muted-foreground);">({{ $votos }} {{ $votos === 1 ? 'voto' : 'votos' }})</span>
    @else
        <span style="font-size:{{ $fontSize }}; color:var(--muted-foreground);">Sin valoraciones</span>
    @endif
</div>
