{{-- resources/views/spots/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Spots')

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    {{-- Header --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">
                Spots
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                {{ $spots->total() ?? 0 }} lugares inexplorados te esperan
            </p>
        </div>
        <a href="{{ route('spots.create') }}" class="btn btn-primary">
            <i data-lucide="plus" style="width:1rem;height:1rem;"></i>
            Añadir Spot
        </a>
    </div>

    {{-- Filters bar --}}
    <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
        <div style="position:relative; flex:1; min-width:200px; max-width:400px;">
            <i data-lucide="search"
                style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem;height:1rem; color:var(--muted-foreground);"></i>
            <input type="search" placeholder="Buscar spots..."
                class="input" style="padding-left:2.5rem;"
                value="{{ request('search') }}"
                onkeyup="if(event.key==='Enter') window.location='?search='+this.value" />
        </div>

        {{-- Difficulty filter --}}
        <div style="display:flex; gap:0.375rem;">
            @foreach(['todos'=>'Todos', 'facil'=>'Fácil', 'medio'=>'Medio', 'dificil'=>'Difícil'] as $val => $label)
            @php $isActive = request('difficulty', 'todos') === $val; @endphp
            <a href="{{ request()->fullUrlWithQuery(['difficulty' => $val]) }}"
                class="btn {{ $isActive ? 'btn-primary' : 'btn-secondary' }}"
                style="font-size:0.8125rem; padding:0.375rem 0.75rem;">
                {{ $label }}
            </a>
            @endforeach
        </div>

        {{-- View toggle --}}
        <div style="display:flex; gap:0.25rem; margin-left:auto;"
            x-data="{ view: 'grid' }">
            <button class="btn btn-ghost btn-icon" @click="view='grid'"
                :style="view==='grid' ? 'background:var(--secondary);' : ''">
                <i data-lucide="grid-2x2" style="width:1.125rem;height:1.125rem;"></i>
            </button>
            <button class="btn btn-ghost btn-icon" @click="view='list'"
                :style="view==='list' ? 'background:var(--secondary);' : ''">
                <i data-lucide="list" style="width:1.125rem;height:1.125rem;"></i>
            </button>
        </div>
    </div>

    {{-- Spots Grid --}}
    <div style="display:grid; gap:1rem; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));">
        @forelse($spots as $spot)
        @include('spots._card', ['spot' => $spot])
        @empty
        <div style="grid-column:1/-1; text-align:center; padding:4rem 1rem; color:var(--muted-foreground);">
            <i data-lucide="map-pin" style="width:3rem;height:3rem; opacity:0.3; margin-bottom:1rem;"></i>
            <p style="font-size:1rem; font-weight:500;">No se encontraron spots</p>
            <p style="font-size:0.875rem; margin-top:0.25rem;">Prueba con otros filtros o añade el primero</p>
            <a href="{{ route('spots.create') }}" class="btn btn-primary" style="margin-top:1rem; display:inline-flex;">
                Añadir Spot
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($spots->hasPages())
    <div style="display:flex; justify-content:center;">
        {{ $spots->links() }}
    </div>
    @endif

</div>
@endsection
