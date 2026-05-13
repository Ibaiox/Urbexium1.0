{{-- resources/views/admin/spots/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Spots')

@section('content')
<style>
    .admin-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .admin-title  { font-size:1.5rem; font-weight:700; letter-spacing:-0.03em; margin:0; display:flex; align-items:center; gap:0.6rem; }
    .filter-bar   { display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center; margin-bottom:1.25rem; }
    .filter-bar input, .filter-bar select { height:2.25rem; padding:0 0.75rem; border:1px solid var(--border); border-radius:var(--radius); background:var(--card); color:var(--foreground); font-size:0.875rem; }
    .filter-bar input { min-width:220px; }
    .table-wrap   { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
    table         { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead th      { padding:0.75rem 1rem; text-align:left; font-weight:600; font-size:0.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted-foreground); border-bottom:1px solid var(--border); background:color-mix(in oklch,var(--card) 95%,var(--foreground)); }
    tbody tr      { border-bottom:1px solid var(--border); transition:background 120ms; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:var(--secondary); }
    tbody td      { padding:0.75rem 1rem; vertical-align:middle; }
    .spot-thumb   { width:3rem; height:3rem; border-radius:calc(var(--radius)-2px); object-fit:cover; background:var(--secondary); flex-shrink:0; }
    .badge        { display:inline-flex; align-items:center; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
    .badge-verificado { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .badge-pendiente  { background:color-mix(in oklch,var(--accent) 15%,transparent); color:var(--accent); }
    .badge-rechazado  { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .action-btns  { display:flex; gap:0.4rem; flex-wrap:wrap; }
    .btn-xs       { padding:0.25rem 0.6rem; font-size:0.75rem; border-radius:calc(var(--radius) - 2px); border:1px solid var(--border); cursor:pointer; background:var(--card); color:var(--foreground); transition:background 120ms; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; white-space:nowrap; }
    .btn-xs:hover { background:var(--secondary); }
    .btn-xs.danger { color:var(--destructive); border-color:color-mix(in oklch,var(--destructive) 40%,transparent); }
    .btn-xs.danger:hover { background:color-mix(in oklch,var(--destructive) 10%,transparent); }
    .btn-xs.success { color:var(--primary); border-color:color-mix(in oklch,var(--primary) 40%,transparent); }
    .btn-xs.success:hover { background:color-mix(in oklch,var(--primary) 10%,transparent); }
</style>

<div class="admin-header">
    <h1 class="admin-title">
        <i data-lucide="map-pin" style="width:1.4rem;height:1.4rem;"></i>
        Gestión de Spots
    </h1>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('admin.spots.pendientes') }}" class="btn btn-primary btn-sm">
            <i data-lucide="clock" style="width:1rem;height:1rem;"></i> Pendientes de revisión
        </a>
        <a href="{{ route('admin.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Panel
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.spots.index') }}">
    <div class="filter-bar">
        <input type="text" name="q" placeholder="Buscar por nombre…" value="{{ request('q') }}">
        <select name="estado">
            <option value="">Todos los estados</option>
            <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
            <option value="verificado" {{ request('estado') === 'verificado' ? 'selected' : '' }}>Verificado</option>
            <option value="rechazado"  {{ request('estado') === 'rechazado'  ? 'selected' : '' }}>Rechazado</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('admin.spots.index') }}" class="btn btn-ghost btn-sm">Limpiar</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:3.5rem;"></th>
                <th>Nombre</th>
                <th>Creador</th>
                <th>Ciudad</th>
                <th>Estado</th>
                <th>Activo</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($spots as $spot)
            <tr>
                <td>
                    @if($spot->imagenes->first())
                        <img src="{{ asset('storage/'.$spot->imagenes->first()->ruta) }}" class="spot-thumb" alt="">
                    @else
                        <div class="spot-thumb" style="display:flex;align-items:center;justify-content:center;">
                            <i data-lucide="image-off" style="width:1.2rem;height:1.2rem;color:var(--muted-foreground);"></i>
                        </div>
                    @endif
                </td>
                <td>
                    <div style="font-weight:600;">{{ $spot->nombre }}</div>
                    @if($spot->descripcion)
                        <div style="font-size:0.75rem;color:var(--muted-foreground);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $spot->descripcion }}</div>
                    @endif
                </td>
                <td style="color:var(--muted-foreground);">{{ $spot->user?->nombre ?? '—' }}</td>
                <td style="color:var(--muted-foreground);">{{ $spot->ciudad?->nombre ?? '—' }}</td>
                <td>
                    <span class="badge badge-{{ $spot->verification_status }}">{{ ucfirst($spot->verification_status) }}</span>
                </td>
                <td>
                    @if($spot->is_active)
                        <span style="color:var(--primary);font-size:0.8rem;font-weight:600;">✓ Sí</span>
                    @else
                        <span style="color:var(--muted-foreground);font-size:0.8rem;">No</span>
                    @endif
                </td>
                <td style="color:var(--muted-foreground);font-size:0.8rem;">{{ $spot->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('spots.show', $spot) }}" class="btn-xs" target="_blank">
                            <i data-lucide="eye" style="width:.9rem;height:.9rem;"></i>
                        </a>

                        @if($spot->verification_status === 'pendiente')
                        <form method="POST" action="{{ route('admin.spots.aprobar', $spot) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-xs success">
                                <i data-lucide="check" style="width:.9rem;height:.9rem;"></i> Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.spots.rechazar', $spot) }}" style="display:inline;"
                              onsubmit="return confirm('¿Rechazar y eliminar este spot?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-xs danger">
                                <i data-lucide="x" style="width:.9rem;height:.9rem;"></i> Rechazar
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.spots.destroy', $spot) }}" style="display:inline;"
                              onsubmit="return confirm('¿Eliminar permanentemente este spot?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-xs danger">
                                <i data-lucide="trash-2" style="width:.9rem;height:.9rem;"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:2rem;color:var(--muted-foreground);">
                    No se encontraron spots.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:1rem;">
    {{ $spots->links() }}
</div>

@endsection
