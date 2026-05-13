{{-- resources/views/admin/reportes/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Reportes')

@section('content')
<style>
    .admin-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem; }
    .admin-title  { font-size:1.5rem; font-weight:700; letter-spacing:-0.03em; margin:0; display:flex; align-items:center; gap:0.6rem; }
    .filter-bar   { display:flex; gap:0.5rem; flex-wrap:wrap; align-items:center; margin-bottom:1.25rem; }
    .filter-bar select { height:2.25rem; padding:0 0.75rem; border:1px solid var(--border); border-radius:var(--radius); background:var(--card); color:var(--foreground); font-size:0.875rem; }
    .table-wrap   { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; }
    table         { width:100%; border-collapse:collapse; font-size:0.875rem; }
    thead th      { padding:0.75rem 1rem; text-align:left; font-weight:600; font-size:0.75rem; text-transform:uppercase; letter-spacing:.06em; color:var(--muted-foreground); border-bottom:1px solid var(--border); background:color-mix(in oklch,var(--card) 95%,var(--foreground)); }
    tbody tr      { border-bottom:1px solid var(--border); transition:background 120ms; }
    tbody tr:last-child { border-bottom:none; }
    tbody tr:hover { background:var(--secondary); }
    tbody td      { padding:0.75rem 1rem; vertical-align:middle; }
    .badge        { display:inline-flex; align-items:center; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
    .badge-abierto   { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .badge-resuelto  { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .badge-revision  { background:color-mix(in oklch,var(--accent) 15%,transparent); color:var(--accent); }
    .btn-xs       { padding:0.25rem 0.6rem; font-size:0.75rem; border-radius:calc(var(--radius)-2px); border:1px solid var(--border); cursor:pointer; background:var(--card); color:var(--foreground); transition:background 120ms; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; }
    .btn-xs:hover { background:var(--secondary); }
    .btn-xs.success { color:var(--primary); border-color:color-mix(in oklch,var(--primary) 40%,transparent); }
    .btn-xs.success:hover { background:color-mix(in oklch,var(--primary) 10%,transparent); }
    .reason-cell  { max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:var(--muted-foreground); }
</style>

<div class="admin-header">
    <h1 class="admin-title">
        <i data-lucide="flag" style="width:1.4rem;height:1.4rem;"></i>
        Reportes
    </h1>
    <a href="{{ route('admin.index') }}" class="btn btn-ghost btn-sm">
        <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Panel
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif

{{-- Filtro estado --}}
<form method="GET" action="{{ route('admin.reportes.index') }}">
    <div class="filter-bar">
        <select name="estado" onchange="this.form.submit()">
            <option value=""        {{ request('estado') === ''         ? 'selected' : '' }}>Abiertos (por defecto)</option>
            <option value="abierto" {{ request('estado') === 'abierto'  ? 'selected' : '' }}>Abiertos</option>
            <option value="resuelto"{{ request('estado') === 'resuelto' ? 'selected' : '' }}>Resueltos</option>
        </select>
        <a href="{{ route('admin.reportes.index') }}" class="btn btn-ghost btn-sm">Ver todos</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Denunciante</th>
                <th>Spot reportado</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportes as $reporte)
            <tr>
                <td style="color:var(--muted-foreground);font-size:0.8rem;">#{{ $reporte->id }}</td>
                <td>
                    <div style="font-weight:600;">{{ $reporte->user?->nombre ?? '—' }}</div>
                    <div style="font-size:0.75rem;color:var(--muted-foreground);">{{ $reporte->user?->email }}</div>
                </td>
                <td>
                    @if($reporte->localizacion)
                        <a href="{{ route('spots.show', $reporte->localizacion) }}" style="color:var(--foreground);text-decoration:none;font-weight:600;" target="_blank">
                            {{ $reporte->localizacion->nombre }}
                        </a>
                        <div style="font-size:0.75rem;color:var(--muted-foreground);">por {{ $reporte->localizacion->user?->nombre ?? '—' }}</div>
                    @else
                        <span style="color:var(--muted-foreground);">Spot eliminado</span>
                    @endif
                </td>
                <td class="reason-cell" title="{{ $reporte->motivo }}">{{ $reporte->motivo }}</td>
                <td>
                    <span class="badge badge-{{ $reporte->estado }}">{{ ucfirst($reporte->estado) }}</span>
                </td>
                <td style="color:var(--muted-foreground);font-size:0.8rem;">{{ $reporte->created_at->format('d/m/Y') }}</td>
                <td>
                    <div style="display:flex;gap:0.4rem;">
                        <a href="{{ route('admin.reportes.show', $reporte) }}" class="btn-xs">
                            <i data-lucide="eye" style="width:.9rem;height:.9rem;"></i> Ver
                        </a>

                        @if($reporte->estado !== 'resuelto')
                        <form method="POST" action="{{ route('admin.reportes.resolver', $reporte) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-xs success">
                                <i data-lucide="check" style="width:.9rem;height:.9rem;"></i> Resolver
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:var(--muted-foreground);">
                    No hay reportes con este filtro.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:1rem;">
    {{ $reportes->links() }}
</div>

@endsection
