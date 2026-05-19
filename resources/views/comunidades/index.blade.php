{{-- resources/views/comunidades/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Comunidades')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.75rem; max-width:1200px; margin:0 auto; width:100%;">

    {{-- Cabecera --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                Comunidades
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                Únete a grupos de exploradores de tu zona
            </p>
        </div>
    </div>

    {{-- Flash messages --}}
    @foreach(['success' => 'check-circle', 'info' => 'info', 'error' => 'alert-circle'] as $type => $icon)
    @if(session($type))
    <div style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.25rem;
                background:color-mix(in oklch, var(--primary) 10%, transparent);
                border:1px solid color-mix(in oklch, var(--primary) 30%, transparent);
                border-radius:var(--radius); font-size:0.875rem; color:var(--foreground);">
        <i data-lucide="{{ $icon }}" style="width:1.125rem; height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
        {{ session($type) }}
    </div>
    @endif
    @endforeach

    {{-- Grid de comunidades --}}
    @if($communities->isEmpty())
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center;
                gap:1rem; padding:4rem 1rem; text-align:center;
                background:var(--card); border:1px solid var(--border); border-radius:var(--radius);">
        <i data-lucide="users-round" style="width:3rem; height:3rem; opacity:0.3;"></i>
        <div>
            <p style="font-weight:600; font-size:1rem; margin:0 0 0.25rem;">Aún no hay comunidades</p>
            <p style="color:var(--muted-foreground); font-size:0.875rem; margin:0;">
                Pronto aparecerán comunidades de exploradores.
            </p>
        </div>
    </div>
    @else
    <div class="communities-grid"
         style="display:grid; gap:1.25rem;
                grid-template-columns:repeat(auto-fill, minmax(min(100%, 280px), 1fr));">

        @foreach($communities as $community)
        <a href="{{ route('comunidades.show', $community) }}"
           style="text-decoration:none; color:inherit; display:flex;">
            <div style="background:var(--card); border:1px solid var(--border);
                        border-radius:var(--radius); overflow:hidden;
                        display:flex; flex-direction:column; width:100%;
                        transition:border-color 0.15s, box-shadow 0.15s;"
                 onmouseenter="this.style.borderColor='var(--ring)'; this.style.boxShadow='0 4px 20px rgba(0,0,0,0.12)'"
                 onmouseleave="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">

                @if($community->image)
                <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}"
                     style="width:100%; height:8rem; object-fit:cover; display:block; flex-shrink:0;" />
                @else
                <div style="height:6rem; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                            background:color-mix(in oklch, var(--primary) 8%, transparent);">
                    <i data-lucide="users-round" style="width:2.25rem; height:2.25rem;
                       color:color-mix(in oklch, var(--primary) 45%, transparent);"></i>
                </div>
                @endif

                <div style="padding:1.125rem; display:flex; flex-direction:column; gap:0.625rem; flex:1;">
                    <div>
                        <h2 style="font-size:1rem; font-weight:600; margin:0 0 0.25rem;
                                   line-height:1.3; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $community->name }}
                        </h2>
                        <span style="display:inline-flex; align-items:center; gap:0.3rem;
                                     font-size:0.8125rem; color:var(--muted-foreground);">
                            <i data-lucide="map-pin" style="width:0.75rem; height:0.75rem; flex-shrink:0;"></i>
                            {{ $community->city }}
                        </span>
                    </div>

                    @if($community->description)
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;
                               line-height:1.55; display:-webkit-box; -webkit-line-clamp:2;
                               -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $community->description }}
                    </p>
                    @endif

                    <div style="margin-top:auto; padding-top:0.625rem;
                                border-top:1px solid var(--border);
                                display:flex; align-items:center; justify-content:space-between;
                                font-size:0.8125rem; color:var(--muted-foreground);">
                        <span style="display:flex; align-items:center; gap:0.3rem;">
                            <i data-lucide="users" style="width:0.8125rem; height:0.8125rem;"></i>
                            {{ $community->members_count }}
                            {{ $community->members_count === 1 ? 'miembro' : 'miembros' }}
                        </span>
                        <span style="font-size:0.75rem; color:var(--primary); font-weight:500;">
                            Ver comunidad →
                        </span>
                    </div>
                </div>

            </div>
        </a>
        @endforeach

    </div>

    @endif

</div>

<style>
@media (max-width: 480px) {
    .communities-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
