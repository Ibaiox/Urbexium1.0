{{-- resources/views/admin/spots/pendientes.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Spots Pendientes')

@section('content')
<style>
    .admin-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .admin-title  { font-size:1.5rem; font-weight:700; letter-spacing:-0.03em; margin:0; display:flex; align-items:center; gap:0.6rem; }
    .spots-grid   { display:grid; grid-template-columns:repeat(auto-fill, minmax(340px, 1fr)); gap:1.25rem; }
    .spot-card    { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; display:flex; flex-direction:column; }
    .spot-img     { width:100%; height:180px; object-fit:cover; background:var(--secondary); display:block; }
    .spot-img-placeholder { width:100%; height:180px; background:var(--secondary); display:flex; align-items:center; justify-content:center; }
    .spot-body    { padding:1rem 1.25rem; flex:1; display:flex; flex-direction:column; gap:0.5rem; }
    .spot-name    { font-size:1.125rem; font-weight:700; margin:0; }
    .spot-meta    { font-size:0.8125rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.4rem; }
    .spot-desc    { font-size:0.875rem; color:var(--muted-foreground); margin:0; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    .spot-footer  { display:flex; gap:0.5rem; padding:0.75rem 1.25rem; border-top:1px solid var(--border); background:color-mix(in oklch,var(--card) 97%,var(--foreground)); }
    .spot-footer form { flex:1; }
    .spot-footer .btn { width:100%; justify-content:center; }
    .images-strip { display:flex; gap:0.25rem; overflow-x:auto; padding:0.5rem 1.25rem; background:color-mix(in oklch,var(--card) 96%,var(--foreground)); }
    .images-strip img { width:3.5rem; height:3.5rem; border-radius:calc(var(--radius)-4px); object-fit:cover; flex-shrink:0; cursor:pointer; }
    .empty-state  { text-align:center; padding:4rem 2rem; color:var(--muted-foreground); }
    .empty-state i { width:3rem; height:3rem; margin-bottom:1rem; opacity:.5; }
</style>

<div class="admin-header">
    <h1 class="admin-title">
        <i data-lucide="clock" style="width:1.4rem;height:1.4rem;color:var(--accent);"></i>
        Spots Pendientes de Verificación
        @if($spots->total())
            <span style="background:var(--accent);color:#fff;border-radius:999px;padding:0.15rem 0.6rem;font-size:0.8rem;">{{ $spots->total() }}</span>
        @endif
    </h1>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('admin.spots.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="list" style="width:1rem;height:1rem;"></i> Todos los spots
        </a>
        <a href="{{ route('admin.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Panel
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
@endif

@if($spots->isEmpty())
    <div class="empty-state">
        <i data-lucide="check-circle" style="display:block;margin:0 auto 1rem;width:3rem;height:3rem;color:var(--primary);opacity:.7;"></i>
        <p style="font-size:1.125rem;font-weight:600;margin:0 0 0.5rem;">¡Todo al día!</p>
        <p style="margin:0;">No hay spots pendientes de verificación.</p>
    </div>
@else
    <div class="spots-grid">
        @foreach($spots as $spot)
        <div class="spot-card">

            {{-- Imagen principal --}}
            @if($spot->imagenes->first())
                <img src="{{ asset('storage/'.$spot->imagenes->first()->ruta) }}" class="spot-img" alt="{{ $spot->nombre }}">
            @else
                <div class="spot-img-placeholder">
                    <i data-lucide="image-off" style="width:2rem;height:2rem;color:var(--muted-foreground);opacity:.5;"></i>
                </div>
            @endif

            {{-- Strip de imágenes adicionales --}}
            @if($spot->imagenes->count() > 1)
                <div class="images-strip">
                    @foreach($spot->imagenes->skip(1)->take(6) as $img)
                        <img src="{{ asset('storage/'.$img->ruta) }}" alt=""
                             onclick="document.querySelector('#main-img-{{ $spot->id }}').src=this.src">
                    @endforeach
                </div>
            @endif

            <div class="spot-body">
                <h3 class="spot-name">{{ $spot->nombre }}</h3>

                <div class="spot-meta">
                    <i data-lucide="user" style="width:.9rem;height:.9rem;"></i>
                    {{ $spot->user?->nombre ?? 'Desconocido' }}
                    &nbsp;·&nbsp;
                    <i data-lucide="map-pin" style="width:.9rem;height:.9rem;"></i>
                    {{ $spot->ciudad?->nombre ?? '—' }}
                </div>

                <div class="spot-meta">
                    <i data-lucide="calendar" style="width:.9rem;height:.9rem;"></i>
                    Enviado {{ $spot->created_at->diffForHumans() }}
                </div>

                @if($spot->descripcion)
                    <p class="spot-desc">{{ $spot->descripcion }}</p>
                @endif

                @if($spot->lat && $spot->lng)
                    <div class="spot-meta">
                        <i data-lucide="crosshair" style="width:.9rem;height:.9rem;"></i>
                        {{ number_format($spot->lat, 5) }}, {{ number_format($spot->lng, 5) }}
                    </div>
                @endif
            </div>

            <div class="spot-footer">
                <a href="{{ route('spots.show', $spot) }}" class="btn btn-ghost btn-sm" target="_blank" style="flex:0 0 auto;">
                    <i data-lucide="external-link" style="width:1rem;height:1rem;"></i>
                </a>

                <form method="POST" action="{{ route('admin.spots.aprobar', $spot) }}" style="flex:1;">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-primary btn-sm" style="width:100%;">
                        <i data-lucide="check" style="width:1rem;height:1rem;"></i> Aprobar
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.spots.rechazar', $spot) }}" style="flex:1;"
                      onsubmit="return confirm('¿Rechazar y eliminar «{{ $spot->nombre }}»? El creador será notificado.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="width:100%;background:color-mix(in oklch,var(--destructive) 10%,transparent);color:var(--destructive);border:1px solid color-mix(in oklch,var(--destructive) 30%,transparent);">
                        <i data-lucide="x" style="width:1rem;height:1rem;"></i> Rechazar
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:1.5rem;">
        {{ $spots->links() }}
    </div>
@endif

@endsection
