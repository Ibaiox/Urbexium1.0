{{-- resources/views/admin/reportes/show.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Reporte #' . $reporte->id)

@section('content')
<style>
    .detail-grid { display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start; }
    @media(max-width:900px){ .detail-grid { grid-template-columns:1fr; } }
    .card        { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem 1.5rem; }
    .card-title  { font-size:1rem; font-weight:700; margin:0 0 1rem; display:flex; align-items:center; gap:0.5rem; }
    .meta-row    { display:flex; justify-content:space-between; align-items:flex-start; padding:0.6rem 0; border-bottom:1px solid var(--border); font-size:0.875rem; gap:1rem; }
    .meta-row:last-child { border-bottom:none; }
    .meta-label  { color:var(--muted-foreground); font-weight:500; flex-shrink:0; }
    .meta-value  { text-align:right; }
    .badge       { display:inline-flex; align-items:center; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
    .badge-abierto  { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .badge-resuelto { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .spot-images { display:flex; gap:0.5rem; overflow-x:auto; margin-top:0.75rem; }
    .spot-images img { width:6rem; height:6rem; border-radius:calc(var(--radius)-2px); object-fit:cover; flex-shrink:0; }
    .motivo-box  { background:color-mix(in oklch,var(--destructive) 8%,transparent); border:1px solid color-mix(in oklch,var(--destructive) 25%,transparent); border-radius:var(--radius); padding:1rem 1.25rem; font-size:0.9rem; line-height:1.6; color:var(--foreground); }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <h1 style="font-size:1.5rem;font-weight:700;letter-spacing:-0.03em;margin:0;display:flex;align-items:center;gap:0.6rem;">
        <i data-lucide="flag" style="width:1.4rem;height:1.4rem;"></i>
        Reporte #{{ $reporte->id }}
        <span class="badge badge-{{ $reporte->estado }}">{{ ucfirst($reporte->estado) }}</span>
    </h1>
    <a href="{{ route('admin.reportes.index') }}" class="btn btn-ghost btn-sm">
        <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Volver
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif

<div class="detail-grid">

    {{-- Columna principal: spot reportado --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        <div class="card">
            <p class="card-title"><i data-lucide="map-pin" style="width:1rem;height:1rem;"></i> Spot reportado</p>

            @if($reporte->localizacion)
                <div style="font-size:1.25rem;font-weight:700;margin-bottom:0.25rem;">{{ $reporte->localizacion->nombre }}</div>
                <div style="font-size:0.875rem;color:var(--muted-foreground);margin-bottom:1rem;">
                    por {{ $reporte->localizacion->user?->nombre ?? '—' }}
                    @if($reporte->localizacion->ciudad)
                        · {{ $reporte->localizacion->ciudad->nombre }}
                    @endif
                </div>

                @if($reporte->localizacion->descripcion)
                    <p style="font-size:0.875rem;color:var(--muted-foreground);margin:0 0 1rem;">{{ $reporte->localizacion->descripcion }}</p>
                @endif

                @if($reporte->localizacion->imagenes->count())
                    <div class="spot-images">
                        @foreach($reporte->localizacion->imagenes->take(8) as $img)
                            <img src="{{ asset('storage/'.$img->ruta) }}" alt="">
                        @endforeach
                    </div>
                @endif

                <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                    <a href="{{ route('spots.show', $reporte->localizacion) }}" class="btn btn-ghost btn-sm" target="_blank">
                        <i data-lucide="external-link" style="width:1rem;height:1rem;"></i> Ver spot público
                    </a>
                    <form method="POST" action="{{ route('admin.spots.destroy', $reporte->localizacion) }}"
                          onsubmit="return confirm('¿Eliminar el spot permanentemente?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:color-mix(in oklch,var(--destructive) 10%,transparent);color:var(--destructive);border:1px solid color-mix(in oklch,var(--destructive) 30%,transparent);">
                            <i data-lucide="trash-2" style="width:1rem;height:1rem;"></i> Eliminar spot
                        </button>
                    </form>
                </div>
            @else
                <p style="color:var(--muted-foreground);">El spot ya ha sido eliminado.</p>
            @endif
        </div>

        <div class="card">
            <p class="card-title"><i data-lucide="alert-triangle" style="width:1rem;height:1rem;color:var(--destructive);"></i> Motivo del reporte</p>
            <div class="motivo-box">{{ $reporte->motivo ?? 'Sin descripción.' }}</div>
        </div>

    </div>

    {{-- Columna lateral: info + acciones --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        <div class="card">
            <p class="card-title"><i data-lucide="info" style="width:1rem;height:1rem;"></i> Detalles del reporte</p>
            <div class="meta-row">
                <span class="meta-label">ID</span>
                <span class="meta-value">#{{ $reporte->id }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Fecha</span>
                <span class="meta-value">{{ $reporte->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Estado</span>
                <span class="meta-value"><span class="badge badge-{{ $reporte->estado }}">{{ ucfirst($reporte->estado) }}</span></span>
            </div>
        </div>

        <div class="card">
            <p class="card-title"><i data-lucide="user" style="width:1rem;height:1rem;"></i> Denunciante</p>
            @if($reporte->user)
                <div style="font-weight:600;margin-bottom:0.25rem;">{{ $reporte->user->nombre }}</div>
                <div style="font-size:0.875rem;color:var(--muted-foreground);margin-bottom:0.75rem;">{{ $reporte->user->email }}</div>
                <a href="{{ route('admin.users.show', $reporte->user) }}" class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">
                    Ver perfil de usuario
                </a>
            @else
                <p style="color:var(--muted-foreground);font-size:0.875rem;margin:0;">Usuario eliminado.</p>
            @endif
        </div>

        @if($reporte->estado !== 'resuelto')
        <div class="card">
            <p class="card-title"><i data-lucide="check-circle" style="width:1rem;height:1rem;color:var(--primary);"></i> Acción</p>
            <form method="POST" action="{{ route('admin.reportes.resolver', $reporte) }}">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                    <i data-lucide="check" style="width:1rem;height:1rem;"></i> Marcar como resuelto
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

@endsection
