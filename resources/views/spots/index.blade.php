{{-- resources/views/spots/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Spots')

@push('styles')
<style>
.pagination-wrap {
    display:flex; align-items:center; justify-content:center;
    gap:0.375rem; flex-wrap:wrap; margin-top:0.5rem;
}
.pagination-wrap span, .pagination-wrap a {
    display:inline-flex; align-items:center; justify-content:center;
    min-width:2.25rem; height:2.25rem; padding:0 0.625rem;
    border-radius:var(--radius); font-size:0.875rem; font-weight:500;
    text-decoration:none; border:1px solid transparent;
    transition:background 150ms, color 150ms; color:var(--foreground); white-space:nowrap;
}
.pagination-wrap a:hover { background:var(--secondary); }
.pagination-wrap span[aria-current="page"] {
    background:var(--primary); color:var(--primary-foreground); border-color:var(--primary);
}
.pagination-wrap span.disabled { color:var(--muted-foreground); opacity:0.5; cursor:not-allowed; }
.pagination-info { font-size:0.8125rem; color:var(--muted-foreground); text-align:center; margin-top:0.375rem; }
</style>
@endpush

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px; margin:0 auto; width:100%;">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">Spots</h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                {{ $spots->total() }} lugar{{ $spots->total() !== 1 ? 'es' : '' }} inexplorado{{ $spots->total() !== 1 ? 's' : '' }} te espera{{ $spots->total() !== 1 ? 'n' : '' }}
            </p>
        </div>
        @if(Auth::check() && (Auth::user()->esAdmin() || Auth::user()->esModerador()))
        <a href="{{ route('spots.create') }}" class="btn btn-primary">
            <i data-lucide="plus" style="width:1rem;height:1rem;"></i>
            Añadir Spot
        </a>
        @endif
    </div>

    {{-- Filtros --}}
    <form method="GET" action="{{ route('spots.index') }}"
        style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">

        <div style="position:relative; flex:1; min-width:200px; max-width:400px;">
            <i data-lucide="search" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem;height:1rem; color:var(--muted-foreground);"></i>
            <input type="search" name="search" placeholder="Buscar spots..."
                class="input" style="padding-left:2.5rem;"
                value="{{ request('search') }}" />
        </div>

        <div style="display:flex; gap:0.375rem; flex-wrap:wrap;">
            @foreach(['todos' => 'Todos', 'facil' => 'Fácil', 'medio' => 'Medio', 'dificil' => 'Difícil'] as $val => $label)
                @php $isActive = request('difficulty', 'todos') === $val; @endphp
                <a href="{{ request()->fullUrlWithQuery(['difficulty' => $val, 'search' => request('search')]) }}"
                    class="btn {{ $isActive ? 'btn-primary' : 'btn-secondary' }}"
                    style="font-size:0.8125rem; padding:0.375rem 0.75rem;">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <button type="submit" class="btn btn-ghost" style="padding:0.375rem 0.75rem; font-size:0.8125rem;">
            <i data-lucide="search" style="width:1rem;height:1rem;"></i>
            Buscar
        </button>

        <div style="display:flex; gap:0.25rem; margin-left:auto;" id="view-toggle">
            <button type="button" class="btn btn-ghost btn-icon" onclick="setView('grid')" id="btn-grid"
                style="background:var(--secondary);" title="Vista cuadrícula">
                <i data-lucide="grid-2x2" style="width:1.125rem;height:1.125rem;"></i>
            </button>
            <button type="button" class="btn btn-ghost btn-icon" onclick="setView('list')" id="btn-list"
                title="Vista lista">
                <i data-lucide="list" style="width:1.125rem;height:1.125rem;"></i>
            </button>
        </div>
    </form>

    {{-- Grid de Spots --}}
    <div id="spots-container"
        style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));">
        @forelse($spots as $spot)
            @include('spots._card', ['spot' => $spot, 'favIds' => $favIds ?? []])
        @empty
            <div style="grid-column:1/-1; text-align:center; padding:4rem 1rem; color:var(--muted-foreground);">
                <i data-lucide="map-pin" style="width:3rem;height:3rem; opacity:0.3; margin-bottom:1rem;"></i>
                <p style="font-size:1rem; font-weight:500; margin:0 0 0.25rem;">No se encontraron spots</p>
                <p style="font-size:0.875rem; margin:0 0 1rem;">Prueba con otros filtros o añade el primero</p>
                @if(Auth::check() && (Auth::user()->esAdmin() || Auth::user()->esModerador()))
                <a href="{{ route('spots.create') }}" class="btn btn-primary" style="display:inline-flex;">
                    <i data-lucide="plus" style="width:1rem;height:1rem;"></i>
                    Añadir Spot
                </a>
                @endif
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    @if($spots->hasPages())
    <div>
        <div class="pagination-wrap">
            @if($spots->onFirstPage())
                <span class="disabled"><i data-lucide="chevron-left" style="width:1rem;height:1rem;"></i> Anterior</span>
            @else
                <a href="{{ $spots->appends(request()->query())->previousPageUrl() }}">
                    <i data-lucide="chevron-left" style="width:1rem;height:1rem;"></i> Anterior
                </a>
            @endif

            @foreach($spots->appends(request()->query())->getUrlRange(1, $spots->lastPage()) as $page => $url)
                @if($page == $spots->currentPage())
                    <span aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($spots->hasMorePages())
                <a href="{{ $spots->appends(request()->query())->nextPageUrl() }}">
                    Siguiente <i data-lucide="chevron-right" style="width:1rem;height:1rem;"></i>
                </a>
            @else
                <span class="disabled">Siguiente <i data-lucide="chevron-right" style="width:1rem;height:1rem;"></i></span>
            @endif
        </div>
        <p class="pagination-info">
            Mostrando {{ $spots->firstItem() }}–{{ $spots->lastItem() }} de {{ $spots->total() }} resultados
        </p>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// ── Vista grid/list ──
function setView(v) {
    const c = document.getElementById('spots-container');
    const bg = document.getElementById('btn-grid');
    const bl = document.getElementById('btn-list');
    localStorage.setItem('spotsView', v);
    if (v === 'list') {
        c.style.gridTemplateColumns = '1fr';
        bl.style.background = 'var(--secondary)'; bg.style.background = '';
    } else {
        c.style.gridTemplateColumns = 'repeat(auto-fill, minmax(280px, 1fr))';
        bg.style.background = 'var(--secondary)'; bl.style.background = '';
    }
}
setView(localStorage.getItem('spotsView') || 'grid');

// ── Toggle favorito — event delegation sobre el contenedor ──
// Lee la ruta desde data-fav-route generada en PHP, sin hardcodear URLs
document.getElementById('spots-container').addEventListener('click', function (e) {
    const btn = e.target.closest('[data-fav-btn]');
    if (!btn) return;

    e.preventDefault();
    const spotId   = btn.dataset.favBtn;
    const route    = btn.dataset.favRoute;
    const isActive = btn.dataset.favActive === '1';
    const icon     = btn.querySelector('[data-lucide]');

    // Feedback inmediato (optimistic UI)
    const newState = !isActive;
    btn.dataset.favActive    = newState ? '1' : '0';
    icon.style.fill          = newState ? 'currentColor' : 'none';
    btn.style.color          = newState ? 'var(--primary)' : '';
    btn.title                = newState ? 'Quitar de favoritos' : 'Guardar';

    fetch(route, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept':       'application/json',
            'Content-Type': 'application/json',
        },
    })
    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
    .then(data => {
        // Confirmar con la respuesta real del servidor
        btn.dataset.favActive = data.favorito ? '1' : '0';
        icon.style.fill       = data.favorito ? 'currentColor' : 'none';
        btn.style.color       = data.favorito ? 'var(--primary)' : '';
        btn.title             = data.favorito ? 'Quitar de favoritos' : 'Guardar';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    })
    .catch(() => {
        // Revertir si falla
        btn.dataset.favActive = isActive ? '1' : '0';
        icon.style.fill       = isActive ? 'currentColor' : 'none';
        btn.style.color       = isActive ? 'var(--primary)' : '';
        btn.title             = isActive ? 'Quitar de favoritos' : 'Guardar';
        btn.style.outline     = '2px solid var(--destructive)';
        setTimeout(() => btn.style.outline = '', 2000);
    });
});
</script>
@endpush
