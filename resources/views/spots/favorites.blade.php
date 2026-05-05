{{-- resources/views/spots/favorites.blade.php --}}
@extends('layout.masterpage')
@section('title', 'Mis Favoritos')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    <div>
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
            <i data-lucide="bookmark" style="width:1.5rem;height:1.5rem; vertical-align:middle; margin-right:0.5rem; color:var(--primary);"></i>
            Mis Favoritos
        </h1>
        <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
            Tienes {{ $spots->total() }} spot{{ $spots->total() !== 1 ? 's' : '' }} guardado{{ $spots->total() !== 1 ? 's' : '' }}
        </p>
    </div>

    @if($spots->count())
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));">
        @foreach($spots as $spot)
            @include('spots._card', ['spot' => $spot, 'favIds' => $favIds ?? []])
        @endforeach
    </div>

    @if($spots->hasPages())
    <div style="display:flex; justify-content:center;">{{ $spots->links() }}</div>
    @endif

    @else
    <div style="text-align:center; padding:5rem 1rem; color:var(--muted-foreground);">
        <i data-lucide="bookmark" style="width:3.5rem;height:3.5rem; opacity:0.25; margin-bottom:1rem;"></i>
        <p style="font-size:1.125rem; font-weight:500; margin:0 0 0.5rem;">Sin favoritos todavía</p>
        <p style="font-size:0.875rem; margin:0 0 1.5rem;">Guarda spots que te interesen para encontrarlos fácilmente</p>
        <a href="{{ route('spots.index') }}" class="btn btn-primary">
            <i data-lucide="map-pin" style="width:1rem;height:1rem;"></i>
            Explorar Spots
        </a>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function toggleFav(btn, spotId) {
        fetch(`/spots/${spotId}/fav`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.favorito) {
                // Animar y quitar la card
                const card = btn.closest('.spot-card');
                if (card) {
                    card.style.transition = 'opacity 300ms, transform 300ms';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 300);
                }
            }
        });
    }
</script>
@endpush
