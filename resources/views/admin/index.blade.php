{{-- resources/views/admin/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Panel de Administración')

@section('content')

<style>
    /* ── Admin-specific styles ─────────────────────────────────────────── */
    .admin-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .admin-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
    .admin-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 1200px) {
        .admin-grid-4 { grid-template-columns: repeat(2, 1fr); }
        .admin-grid-3 { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .admin-grid-4, .admin-grid-3, .admin-grid-2 { grid-template-columns: 1fr; }
    }

    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1.25rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 150ms, box-shadow 150ms;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px color-mix(in oklch, var(--foreground) 6%, transparent);
    }
    .stat-card-accent {
        position: absolute;
        top: 0; right: 0;
        width: 4rem; height: 4rem;
        border-radius: 0 var(--radius) 0 50%;
        opacity: 0.12;
    }

    .stat-icon {
        width: 2.25rem; height: 2.25rem;
        border-radius: calc(var(--radius) - 4px);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: -0.04em;
        line-height: 1;
        margin: 0;
    }
    .stat-label {
        font-size: 0.8125rem;
        color: var(--muted-foreground);
        margin: 0;
        font-weight: 500;
    }
    .stat-delta {
        font-size: 0.75rem;
        font-weight: 600;
        display: flex; align-items: center; gap: 0.25rem;
    }
    .stat-delta.up   { color: var(--primary); }
    .stat-delta.warn { color: var(--accent); }
    .stat-delta.down { color: var(--destructive); }

    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .section-title {
        font-size: 1.125rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0;
        display: flex; align-items: center; gap: 0.5rem;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    .data-table th {
        text-align: left;
        padding: 0.625rem 0.875rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--muted-foreground);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }
    .data-table td {
        padding: 0.75rem 0.875rem;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    .data-table tr:last-child td { border-bottom: none; }
    .data-table tbody tr {
        transition: background-color 120ms;
    }
    .data-table tbody tr:hover { background-color: var(--secondary); }

    .action-btn {
        display: inline-flex; align-items: center; justify-content: center;
        width: 1.875rem; height: 1.875rem;
        border-radius: calc(var(--radius) - 4px);
        border: 1px solid var(--border);
        background: transparent;
        color: var(--muted-foreground);
        cursor: pointer;
        transition: all 120ms;
        text-decoration: none;
    }
    .action-btn:hover { background: var(--secondary); color: var(--foreground); }
    .action-btn.danger:hover { background: color-mix(in oklch, var(--destructive) 15%, transparent); color: var(--destructive); border-color: var(--destructive); }

    .quick-action-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        display: flex; align-items: center; gap: 0.875rem;
        text-decoration: none; color: inherit;
        transition: all 150ms;
        cursor: pointer;
    }
    .quick-action-card:hover {
        border-color: var(--primary);
        box-shadow: 0 0 0 1px var(--primary);
        transform: translateY(-1px);
    }
    .quick-action-icon {
        width: 2.5rem; height: 2.5rem;
        border-radius: calc(var(--radius) - 2px);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    .report-item {
        display: flex; gap: 0.875rem;
        padding: 0.875rem 0;
        border-bottom: 1px solid var(--border);
        align-items: flex-start;
    }
    .report-item:last-child { border-bottom: none; }

    .notif-form textarea, .notif-form select, .notif-form input[type="text"] {
        width: 100%;
        background: var(--input);
        border: 1px solid var(--border);
        border-radius: calc(var(--radius) - 4px);
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: var(--foreground);
        font-family: inherit;
        transition: border-color 150ms, box-shadow 150ms;
        resize: vertical;
        outline: none;
    }
    .notif-form textarea:focus, .notif-form select:focus, .notif-form input[type="text"]:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 2px color-mix(in oklch, var(--primary) 20%, transparent);
    }
    .notif-form label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: var(--muted-foreground);
        margin-bottom: 0.375rem;
    }

    .admin-badge {
        display: inline-flex; align-items: center;
        padding: 0.2rem 0.625rem;
        border-radius: 9999px;
        font-size: 0.6875rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .tab-btn {
        padding: 0.5rem 1rem;
        border-radius: var(--radius);
        border: none;
        background: transparent;
        color: var(--muted-foreground);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 120ms;
        font-family: inherit;
    }
    .tab-btn.active {
        background: var(--secondary);
        color: var(--foreground);
    }
    .tab-btn:hover:not(.active) { color: var(--foreground); }

    .tab-content { display: none; }
    .tab-content.active { display: block; }
</style>

<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    {{-- ── Header ── --}}
    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.25rem;">
                <span class="admin-badge" style="background:color-mix(in oklch, var(--destructive) 15%, transparent); color:var(--destructive);">
                    <i data-lucide="shield" style="width:0.75rem;height:0.75rem;margin-right:0.25rem;"></i>
                    Admin
                </span>
            </div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.03em; margin:0 0 0.25rem;">
                Panel de Control
            </h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
                Visión general de Urbexium — {{ now()->format('d M Y') }}
            </p>
        </div>
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <a href="{{ route('admin.spots.index') }}" class="btn btn-secondary" style="font-size:0.875rem;">
                <i data-lucide="map-pin" style="width:1rem;height:1rem;"></i>
                Gestionar Spots
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary" style="font-size:0.875rem;">
                <i data-lucide="users" style="width:1rem;height:1rem;"></i>
                Ver Usuarios
            </a>
        </div>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="admin-grid-4">

        {{-- Usuarios --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:var(--primary);"></div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div class="stat-icon" style="background:color-mix(in oklch, var(--primary) 15%, transparent);">
                    <i data-lucide="users" style="width:1.1rem;height:1.1rem; color:var(--primary);"></i>
                </div>
                <span class="stat-delta up">
                    <i data-lucide="trending-up" style="width:0.875rem;height:0.875rem;"></i>
                    +{{ $stats['nuevos_usuarios_mes'] ?? 0 }} este mes
                </span>
            </div>
            <p class="stat-value" style="margin-top:0.5rem;">{{ $stats['total_usuarios'] ?? 0 }}</p>
            <p class="stat-label">Usuarios registrados</p>
            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                {{ $stats['usuarios_baneados'] ?? 0 }} baneados ·
                {{ $stats['moderadores'] ?? 0 }} moderadores
            </p>
        </div>

        {{-- Spots --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:var(--accent);"></div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div class="stat-icon" style="background:color-mix(in oklch, var(--accent) 15%, transparent);">
                    <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem; color:var(--accent);"></i>
                </div>
                <span class="stat-delta warn">
                    {{ $stats['spots_pendientes'] ?? 0 }} pendientes
                </span>
            </div>
            <p class="stat-value" style="margin-top:0.5rem;">{{ $stats['total_spots'] ?? 0 }}</p>
            <p class="stat-label">Localizaciones totales</p>
            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                {{ $stats['spots_activos'] ?? 0 }} activos ·
                {{ $stats['spots_ocultos'] ?? 0 }} ocultos
            </p>
        </div>

        {{-- Reportes --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:var(--destructive);"></div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div class="stat-icon" style="background:color-mix(in oklch, var(--destructive) 15%, transparent);">
                    <i data-lucide="flag" style="width:1.1rem;height:1.1rem; color:var(--destructive);"></i>
                </div>
                @if(($stats['reportes_abiertos'] ?? 0) > 0)
                <span class="stat-delta down">
                    <i data-lucide="alert-circle" style="width:0.875rem;height:0.875rem;"></i>
                    {{ $stats['reportes_abiertos'] }} abiertos
                </span>
                @endif
            </div>
            <p class="stat-value" style="margin-top:0.5rem;">{{ $stats['total_reportes'] ?? 0 }}</p>
            <p class="stat-label">Reportes recibidos</p>
            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                {{ $stats['reportes_resueltos'] ?? 0 }} resueltos
            </p>
        </div>

        {{-- Tienda --}}
        <div class="stat-card">
            <div class="stat-card-accent" style="background:oklch(0.65 0.18 280);"></div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div class="stat-icon" style="background:color-mix(in oklch, oklch(0.65 0.18 280) 15%, transparent);">
                    <i data-lucide="shopping-bag" style="width:1.1rem;height:1.1rem; color:oklch(0.65 0.18 280);"></i>
                </div>
                <span class="stat-delta up">
                    {{ $stats['pedidos_pendientes'] ?? 0 }} por enviar
                </span>
            </div>
            <p class="stat-value" style="margin-top:0.5rem;">€{{ number_format($stats['ingresos_mes'] ?? 0, 0, ',', '.') }}</p>
            <p class="stat-label">Ingresos este mes</p>
            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                {{ $stats['total_pedidos'] ?? 0 }} pedidos ·
                {{ $stats['productos_activos'] ?? 0 }} productos activos
            </p>
        </div>
    </div>

    {{-- ── Acciones Rápidas ── --}}
    <div>
        <div class="section-header">
            <h2 class="section-title">
                <i data-lucide="zap" style="width:1.125rem;height:1.125rem; color:var(--accent);"></i>
                Acciones Rápidas
            </h2>
        </div>
        <div class="admin-grid-3">

            <a href="{{ route('admin.spots.pendientes') }}" class="quick-action-card">
                <div class="quick-action-icon" style="background:color-mix(in oklch, var(--accent) 15%, transparent);">
                    <i data-lucide="clock" style="width:1.25rem;height:1.25rem; color:var(--accent);"></i>
                </div>
                <div>
                    <p style="font-weight:600; font-size:0.9375rem; margin:0 0 0.125rem;">
                        Spots Pendientes
                        @if(($stats['spots_pendientes'] ?? 0) > 0)
                        <span class="admin-badge" style="background:color-mix(in oklch, var(--accent) 20%, transparent); color:var(--accent); margin-left:0.375rem;">
                            {{ $stats['spots_pendientes'] }}
                        </span>
                        @endif
                    </p>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;">Verificar y aprobar localizaciones</p>
                </div>
                <i data-lucide="chevron-right" style="width:1rem;height:1rem; color:var(--muted-foreground); margin-left:auto; flex-shrink:0;"></i>
            </a>

            <a href="{{ route('admin.reportes.index') }}" class="quick-action-card">
                <div class="quick-action-icon" style="background:color-mix(in oklch, var(--destructive) 15%, transparent);">
                    <i data-lucide="flag" style="width:1.25rem;height:1.25rem; color:var(--destructive);"></i>
                </div>
                <div>
                    <p style="font-weight:600; font-size:0.9375rem; margin:0 0 0.125rem;">
                        Reportes Abiertos
                        @if(($stats['reportes_abiertos'] ?? 0) > 0)
                        <span class="admin-badge" style="background:color-mix(in oklch, var(--destructive) 20%, transparent); color:var(--destructive); margin-left:0.375rem;">
                            {{ $stats['reportes_abiertos'] }}
                        </span>
                        @endif
                    </p>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;">Revisar denuncias de la comunidad</p>
                </div>
                <i data-lucide="chevron-right" style="width:1rem;height:1rem; color:var(--muted-foreground); margin-left:auto; flex-shrink:0;"></i>
            </a>

            <a href="{{ route('tienda.admin.pedidos') }}" class="quick-action-card">
                <div class="quick-action-icon" style="background:color-mix(in oklch, oklch(0.65 0.18 280) 15%, transparent);">
                    <i data-lucide="package" style="width:1.25rem;height:1.25rem; color:oklch(0.65 0.18 280);"></i>
                </div>
                <div>
                    <p style="font-weight:600; font-size:0.9375rem; margin:0 0 0.125rem;">
                        Pedidos Tienda
                        @if(($stats['pedidos_pendientes'] ?? 0) > 0)
                        <span class="admin-badge" style="background:color-mix(in oklch, oklch(0.65 0.18 280) 20%, transparent); color:oklch(0.65 0.18 280); margin-left:0.375rem;">
                            {{ $stats['pedidos_pendientes'] }}
                        </span>
                        @endif
                    </p>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;">Gestionar pedidos y envíos</p>
                </div>
                <i data-lucide="chevron-right" style="width:1rem;height:1rem; color:var(--muted-foreground); margin-left:auto; flex-shrink:0;"></i>
            </a>
        </div>
    </div>

    {{-- ── Fila 2: Usuarios recientes + Reportes recientes ── --}}
    <div class="admin-grid-2">

        {{-- USUARIOS RECIENTES --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="users" style="width:1rem;height:1rem; color:var(--primary);"></i>
                    Usuarios Recientes
                </h2>
                <a href="{{ route('admin.users.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding:0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $u)
                        <tr>
                            <td>
                                <div style="display:flex; align-items:center; gap:0.625rem;">
                                    <div class="avatar" style="width:2rem; height:2rem; font-size:0.75rem;
                                        background:var(--primary); color:var(--primary-foreground); flex-shrink:0;">
                                        @if($u->avatar)
                                            <img src="{{ $u->avatar }}" alt="{{ $u->nombre }}"
                                                style="width:100%;height:100%;object-fit:cover;" />
                                        @else
                                            {{ strtoupper(substr($u->nombre, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p style="font-weight:500; font-size:0.875rem; margin:0;">{{ $u->nombre }}</p>
                                        <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ $u->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $rolColor = match($u->rol?->nombre) {
                                        'admin'      => 'color:var(--destructive);background:color-mix(in oklch,var(--destructive) 12%,transparent)',
                                        'moderador'  => 'color:var(--accent);background:color-mix(in oklch,var(--accent) 12%,transparent)',
                                        default      => 'color:var(--muted-foreground);background:var(--muted)',
                                    };
                                @endphp
                                <span class="admin-badge" style="{{ $rolColor }}">
                                    {{ ucfirst($u->rol?->nombre ?? 'usuario') }}
                                </span>
                            </td>
                            <td>
                                @if($u->baneado)
                                    <span class="admin-badge" style="background:color-mix(in oklch,var(--destructive) 12%,transparent); color:var(--destructive);">
                                        <i data-lucide="ban" style="width:0.625rem;height:0.625rem;margin-right:0.2rem;"></i> Baneado
                                    </span>
                                @else
                                    <span class="admin-badge" style="background:color-mix(in oklch,var(--primary) 12%,transparent); color:var(--primary);">
                                        Activo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:0.375rem;">
                                    <a href="{{ route('admin.users.show', $u) }}" class="action-btn" title="Ver perfil">
                                        <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.ban', $u) }}" style="display:inline;" onsubmit="return confirm('¿{{ $u->baneado ? 'Desbanear' : 'Banear' }} a este usuario?')">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="action-btn {{ $u->baneado ? '' : 'danger' }}" title="{{ $u->baneado ? 'Desbanear' : 'Banear' }}">
                                            <i data-lucide="{{ $u->baneado ? 'user-check' : 'ban' }}" style="width:0.875rem;height:0.875rem;"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.rol', $u) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <select name="rol" onchange="this.form.submit()"
                                            style="font-size:0.75rem; background:var(--input); border:1px solid var(--border);
                                            border-radius:calc(var(--radius) - 6px); padding:0.2rem 0.375rem; color:var(--foreground);
                                            cursor:pointer; font-family:inherit;">
                                            <option value="usuario"    {{ $u->rol?->nombre === 'usuario'    ? 'selected' : '' }}>Usuario</option>
                                            <option value="moderador"  {{ $u->rol?->nombre === 'moderador'  ? 'selected' : '' }}>Moderador</option>
                                            <option value="admin"      {{ $u->rol?->nombre === 'admin'      ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:2rem; color:var(--muted-foreground);">
                                No hay usuarios
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- REPORTES RECIENTES --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="flag" style="width:1rem;height:1rem; color:var(--destructive);"></i>
                    Reportes Recientes
                </h2>
                <a href="{{ route('admin.reportes.index') }}"
                    style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; display:flex; align-items:center; gap:0.25rem;">
                    Ver todos
                    <i data-lucide="arrow-right" style="width:0.875rem;height:0.875rem;"></i>
                </a>
            </div>
            <div class="card-content" style="padding-top:0;">
                @forelse($recentReportes ?? [] as $r)
                <div class="report-item">
                    <div class="stat-icon" style="width:2rem; height:2rem; background:color-mix(in oklch, var(--destructive) 12%, transparent); flex-shrink:0;">
                        <i data-lucide="flag" style="width:0.875rem;height:0.875rem; color:var(--destructive);"></i>
                    </div>
                    <div style="flex:1; min-width:0;">
                        <p style="font-size:0.875rem; font-weight:500; margin:0 0 0.2rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $r->localizacion?->nombre ?? 'Spot eliminado' }}
                        </p>
                        <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0 0 0.375rem;">
                            por <strong>{{ $r->user?->nombre ?? 'usuario' }}</strong>
                            · {{ $r->created_at->diffForHumans() }}
                        </p>
                        <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;
                            white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $r->descripcion }}
                        </p>
                    </div>
                    <div style="display:flex; gap:0.375rem; flex-shrink:0;">
                        <a href="{{ route('admin.reportes.show', $r) }}" class="action-btn" title="Ver reporte">
                            <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.reportes.resolver', $r) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="action-btn" title="Marcar resuelto"
                                style="color:var(--primary); border-color:var(--primary);">
                                <i data-lucide="check" style="width:0.875rem;height:0.875rem;"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div style="padding:2rem 0; text-align:center; color:var(--muted-foreground);">
                    <i data-lucide="shield-check" style="width:2rem;height:2rem; opacity:0.4; margin-bottom:0.75rem;"></i>
                    <p style="font-size:0.875rem;">Sin reportes pendientes 🎉</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── Fila 3: Spots + Tienda ── --}}
    <div class="admin-grid-2">

        {{-- SPOTS RECIENTES --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="map-pin" style="width:1rem;height:1rem; color:var(--accent);"></i>
                    Localizaciones
                </h2>
                <div style="display:flex; gap:0.375rem;">
                    <button class="tab-btn active" onclick="switchTab('spots','pendientes',this)">Pendientes</button>
                    <button class="tab-btn" onclick="switchTab('spots','recientes',this)">Recientes</button>
                </div>
            </div>
            <div class="card-content" style="padding:0;">

                {{-- Tab pendientes --}}
                <div id="spots-tab-pendientes" class="tab-content active">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Spot</th>
                                <th>Autor</th>
                                <th>Dificultad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spotsPendientes ?? [] as $spot)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <div style="width:2.25rem; height:2.25rem; border-radius:calc(var(--radius)-4px);
                                            background:var(--secondary); overflow:hidden; flex-shrink:0;">
                                            @if($spot->imagenes->first())
                                                <img src="{{ $spot->imagenes->first()->url }}" alt=""
                                                    style="width:100%;height:100%;object-fit:cover;" />
                                            @else
                                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="image" style="width:1rem;height:1rem; color:var(--muted-foreground);"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <span style="font-weight:500; font-size:0.875rem;">{{ $spot->nombre }}</span>
                                    </div>
                                </td>
                                <td style="font-size:0.8125rem; color:var(--muted-foreground);">
                                    {{ $spot->user?->nombre ?? '—' }}
                                </td>
                                <td>
                                    @php
                                        $dc = ['facil'=>'var(--primary)','medio'=>'var(--accent)','dificil'=>'var(--destructive)'][$spot->dificultad] ?? 'var(--muted-foreground)';
                                    @endphp
                                    <span class="admin-badge" style="background:color-mix(in oklch,{{ $dc }} 12%,transparent); color:{{ $dc }};">
                                        {{ ucfirst($spot->dificultad) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.375rem;">
                                        <a href="{{ route('spots.show', $spot) }}" class="action-btn" title="Ver">
                                            <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.spots.aprobar', $spot) }}" style="display:inline;">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="action-btn" title="Aprobar"
                                                style="color:var(--primary); border-color:var(--primary);">
                                                <i data-lucide="check" style="width:0.875rem;height:0.875rem;"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.spots.rechazar', $spot) }}" style="display:inline;"
                                            onsubmit="return confirm('¿Rechazar este spot?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn danger" title="Rechazar">
                                                <i data-lucide="x" style="width:0.875rem;height:0.875rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:2rem; color:var(--muted-foreground);">
                                    <i data-lucide="check-circle" style="width:1.5rem;height:1.5rem; opacity:0.4;"></i>
                                    <p style="font-size:0.875rem; margin:0.5rem 0 0;">Sin spots pendientes</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tab recientes --}}
                <div id="spots-tab-recientes" class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Spot</th>
                                <th>Autor</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($spotsRecientes ?? [] as $spot)
                            <tr>
                                <td>
                                    <p style="font-weight:500; font-size:0.875rem; margin:0;">{{ $spot->nombre }}</p>
                                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                                        {{ $spot->created_at->diffForHumans() }}
                                    </p>
                                </td>
                                <td style="font-size:0.8125rem;">{{ $spot->user?->nombre ?? '—' }}</td>
                                <td>
                                    @if($spot->is_active)
                                        <span class="admin-badge" style="background:color-mix(in oklch,var(--primary) 12%,transparent); color:var(--primary);">Activo</span>
                                    @else
                                        <span class="admin-badge" style="background:var(--muted); color:var(--muted-foreground);">Oculto</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.375rem;">
                                        <a href="{{ route('spots.show', $spot) }}" class="action-btn" title="Ver">
                                            <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.spots.destroy', $spot) }}" style="display:inline;"
                                            onsubmit="return confirm('¿Eliminar este spot definitivamente?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn danger" title="Eliminar">
                                                <i data-lucide="trash-2" style="width:0.875rem;height:0.875rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:2rem; color:var(--muted-foreground);">
                                    Sin localizaciones
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TIENDA / PRODUCTOS ── --}}
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="shopping-bag" style="width:1rem;height:1rem; color:oklch(0.65 0.18 280);"></i>
                    Tienda
                </h2>
                <div style="display:flex; gap:0.375rem;">
                    <button class="tab-btn active" onclick="switchTab('tienda','productos',this)">Productos</button>
                    <button class="tab-btn" onclick="switchTab('tienda','pedidos',this)">Pedidos</button>
                </div>
            </div>
            <div class="card-content" style="padding:0;">

                {{-- Tab productos --}}
                <div id="tienda-tab-productos" class="tab-content active">
                    <div style="padding:0.75rem 1rem; border-bottom:1px solid var(--border);">
                        <a href="{{ route('tienda.create') }}" class="btn btn-primary" style="font-size:0.8125rem; padding:0.4rem 0.875rem;">
                            <i data-lucide="plus" style="width:0.875rem;height:0.875rem;"></i>
                            Nuevo producto
                        </a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos ?? [] as $prod)
                            <tr>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <div style="width:2.25rem; height:2.25rem; border-radius:calc(var(--radius)-4px);
                                            background:var(--secondary); overflow:hidden; flex-shrink:0;">
                                            @if($prod->imagen)
                                                <img src="{{ $prod->imagen }}" alt=""
                                                    style="width:100%;height:100%;object-fit:cover;" />
                                            @else
                                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                                                    <i data-lucide="package" style="width:1rem;height:1rem; color:var(--muted-foreground);"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p style="font-weight:500; font-size:0.875rem; margin:0;">{{ $prod->nombre }}</p>
                                            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ $prod->categoria }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td style="font-weight:600; font-size:0.875rem;">€{{ number_format($prod->precio, 2, ',', '.') }}</td>
                                <td>
                                    @if($prod->stock <= 5)
                                        <span class="admin-badge" style="background:color-mix(in oklch,var(--destructive) 12%,transparent); color:var(--destructive);">
                                            {{ $prod->stock }} uds
                                        </span>
                                    @else
                                        <span style="font-size:0.875rem;">{{ $prod->stock }} uds</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex; gap:0.375rem;">
                                        <a href="{{ route('tienda.edit', $prod) }}" class="action-btn" title="Editar">
                                            <i data-lucide="pencil" style="width:0.875rem;height:0.875rem;"></i>
                                        </a>
                                        <form method="POST" action="{{ route('tienda.destroy', $prod) }}" style="display:inline;"
                                            onsubmit="return confirm('¿Eliminar este producto?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn danger" title="Eliminar">
                                                <i data-lucide="trash-2" style="width:0.875rem;height:0.875rem;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:2rem; color:var(--muted-foreground);">Sin productos</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tab pedidos --}}
                <div id="tienda-tab-pedidos" class="tab-content">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidosRecientes ?? [] as $pedido)
                            <tr>
                                <td style="font-size:0.8125rem; color:var(--muted-foreground);">#{{ $pedido->id }}</td>
                                <td style="font-size:0.875rem; font-weight:500;">{{ $pedido->user?->nombre ?? '—' }}</td>
                                <td style="font-weight:600; font-size:0.875rem;">€{{ number_format($pedido->total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="admin-badge" style="background:color-mix(in oklch,{{ $pedido->estadoColor() }} 15%,transparent); color:{{ $pedido->estadoColor() }};">
                                        {{ $pedido->estadoLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('tienda.pedidos.show', $pedido) }}" class="action-btn" title="Ver pedido">
                                        <i data-lucide="eye" style="width:0.875rem;height:0.875rem;"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:2rem; color:var(--muted-foreground);">Sin pedidos</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Enviar Notificación Global ── --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                <i data-lucide="bell" style="width:1rem;height:1rem; color:var(--primary);"></i>
                Enviar Notificación
            </h2>
            <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                Envía un aviso a todos los usuarios o a un grupo específico
            </p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ route('admin.notificaciones.send') }}" class="notif-form">
                @csrf
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; align-items:end;">
                    <div>
                        <label>Destinatarios</label>
                        <select name="destinatario">
                            <option value="todos">Todos los usuarios</option>
                            <option value="usuarios">Solo usuarios normales</option>
                            <option value="moderadores">Solo moderadores</option>
                        </select>
                    </div>
                    <div>
                        <label>Título</label>
                        <input type="text" name="titulo" placeholder="Título de la notificación" required />
                    </div>
                    <div>
                        <label>Tipo</label>
                        <select name="tipo">
                            <option value="info">Información</option>
                            <option value="aviso">Aviso</option>
                        </select>
                    </div>
                </div>
                <div style="margin-top:1rem;">
                    <label>Mensaje</label>
                    <textarea name="mensaje" rows="3" placeholder="Escribe el mensaje que recibirán los usuarios..." required></textarea>
                </div>
                <div style="margin-top:1rem; display:flex; justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="send" style="width:1rem;height:1rem;"></i>
                        Enviar notificación
                    </button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECCIÓN 1: GRÁFICAS Chart.js ── --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div>
        <div class="section-header">
            <h2 class="section-title">
                <i data-lucide="bar-chart-2" style="width:1.125rem;height:1.125rem; color:var(--primary);"></i>
                Analítica Visual
            </h2>
            <span style="font-size:0.8125rem; color:var(--muted-foreground);">Últimos 12 meses</span>
        </div>
        <div class="admin-grid-3">

            {{-- Registros por mes --}}
            <div class="card" style="grid-column: span 2;">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="user-plus" style="width:1rem;height:1rem; color:var(--primary);"></i>
                        Registros por mes
                    </h2>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                        Nuevos usuarios registrados en la plataforma
                    </p>
                </div>
                <div class="card-content">
                    <div style="position:relative; height:220px;">
                        <canvas id="chartRegistros"></canvas>
                    </div>
                </div>
            </div>

            {{-- Pedidos por estado --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="shopping-bag" style="width:1rem;height:1rem; color:oklch(0.65 0.18 280);"></i>
                        Pedidos por estado
                    </h2>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                        Distribución actual de pedidos
                    </p>
                </div>
                <div class="card-content">
                    <div style="position:relative; height:220px; display:flex; align-items:center; justify-content:center;">
                        <canvas id="chartPedidosEstado"></canvas>
                    </div>
                </div>
            </div>

            {{-- Spots aprobados --}}
            <div class="card" style="grid-column: span 3;">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                        <i data-lucide="map-pin" style="width:1rem;height:1rem; color:var(--accent);"></i>
                        Spots aprobados (últimos 6 meses)
                    </h2>
                    <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                        Evolución mensual de spots verificados
                    </p>
                </div>
                <div class="card-content">
                    <div style="position:relative; height:180px;">
                        <canvas id="chartSpotsAprobados"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECCIÓN 2: EXPORTACIÓN CSV ── --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                <i data-lucide="download" style="width:1rem;height:1rem; color:var(--primary);"></i>
                Exportación de Datos
            </h2>
            <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                Descarga registros filtrados en formato CSV (UTF-8 con BOM, compatible con Excel)
            </p>
        </div>
        <div class="card-content">
            <div class="admin-grid-3" style="gap:1rem;">

                {{-- Export usuarios --}}
                <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; flex-direction:column; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.625rem;">
                        <div class="stat-icon" style="background:color-mix(in oklch, var(--primary) 12%, transparent);">
                            <i data-lucide="users" style="width:1.1rem;height:1.1rem; color:var(--primary);"></i>
                        </div>
                        <div>
                            <p style="font-weight:600; margin:0; font-size:0.9375rem;">Usuarios</p>
                            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ $stats['total_usuarios'] ?? 0 }} registros</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.export', 'usuarios') }}" style="display:flex; flex-direction:column; gap:0.625rem;">
                        <div style="display:flex; gap:0.5rem;">
                            <select name="baneado" style="flex:1; font-size:0.8125rem; background:var(--input); border:1px solid var(--border); border-radius:calc(var(--radius)-4px); padding:0.4rem 0.625rem; color:var(--foreground); font-family:inherit;">
                                <option value="">Todos</option>
                                <option value="1">Baneados</option>
                                <option value="0">Activos</option>
                            </select>
                            <select name="rol" style="flex:1; font-size:0.8125rem; background:var(--input); border:1px solid var(--border); border-radius:calc(var(--radius)-4px); padding:0.4rem 0.625rem; color:var(--foreground); font-family:inherit;">
                                <option value="">Todos los roles</option>
                                <option value="usuario">Usuario</option>
                                <option value="moderador">Moderador</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; font-size:0.875rem;">
                            <i data-lucide="download" style="width:0.875rem;height:0.875rem;"></i>
                            Descargar CSV
                        </button>
                    </form>
                </div>

                {{-- Export spots --}}
                <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; flex-direction:column; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.625rem;">
                        <div class="stat-icon" style="background:color-mix(in oklch, var(--accent) 12%, transparent);">
                            <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem; color:var(--accent);"></i>
                        </div>
                        <div>
                            <p style="font-weight:600; margin:0; font-size:0.9375rem;">Spots</p>
                            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ $stats['total_spots'] ?? 0 }} registros</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.export', 'spots') }}" style="display:flex; flex-direction:column; gap:0.625rem;">
                        <select name="estado" style="font-size:0.8125rem; background:var(--input); border:1px solid var(--border); border-radius:calc(var(--radius)-4px); padding:0.4rem 0.625rem; color:var(--foreground); font-family:inherit;">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendientes</option>
                            <option value="verificado">Verificados</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; font-size:0.875rem;">
                            <i data-lucide="download" style="width:0.875rem;height:0.875rem;"></i>
                            Descargar CSV
                        </button>
                    </form>
                </div>

                {{-- Export pedidos --}}
                <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; flex-direction:column; gap:1rem;">
                    <div style="display:flex; align-items:center; gap:0.625rem;">
                        <div class="stat-icon" style="background:color-mix(in oklch, oklch(0.65 0.18 280) 12%, transparent);">
                            <i data-lucide="package" style="width:1.1rem;height:1.1rem; color:oklch(0.65 0.18 280);"></i>
                        </div>
                        <div>
                            <p style="font-weight:600; margin:0; font-size:0.9375rem;">Pedidos</p>
                            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">{{ $stats['total_pedidos'] ?? 0 }} registros</p>
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.export', 'pedidos') }}" style="display:flex; flex-direction:column; gap:0.625rem;">
                        <select name="estado" style="font-size:0.8125rem; background:var(--input); border:1px solid var(--border); border-radius:calc(var(--radius)-4px); padding:0.4rem 0.625rem; color:var(--foreground); font-family:inherit;">
                            <option value="">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="procesando">Procesando</option>
                            <option value="enviado">Enviado</option>
                            <option value="entregado">Entregado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; font-size:0.875rem;">
                            <i data-lucide="download" style="width:0.875rem;height:0.875rem;"></i>
                            Descargar CSV
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECCIÓN 3: LOG DE ACTIVIDAD DE ADMINISTRADORES ── --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="scroll-text" style="width:1rem;height:1rem; color:var(--primary);"></i>
                    Log de Actividad — Admins
                </h2>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Auditoría de las últimas acciones realizadas por administradores
                </p>
            </div>
            <span class="admin-badge" style="background:color-mix(in oklch, var(--primary) 12%, transparent); color:var(--primary);">
                Últimas {{ $activityLogs->count() }}
            </span>
        </div>
        <div class="card-content" style="padding:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:140px;">Administrador</th>
                        <th style="width:150px;">Acción</th>
                        <th>Descripción</th>
                        <th style="width:90px;">Entidad</th>
                        <th style="width:90px;">IP</th>
                        <th style="width:130px;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activityLogs ?? [] as $log)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <div class="avatar" style="width:1.75rem; height:1.75rem; font-size:0.6875rem;
                                    background:var(--primary); color:var(--primary-foreground); flex-shrink:0;">
                                    {{ strtoupper(substr($log->admin?->nombre ?? 'A', 0, 1)) }}
                                </div>
                                <span style="font-size:0.8125rem; font-weight:500;">{{ $log->admin?->nombre ?? '—' }}</span>
                            </div>
                        </td>
                        <td>
                            @php $color = $log->accionColor(); @endphp
                            <span class="admin-badge" style="background:color-mix(in oklch,{{ $color }} 12%,transparent); color:{{ $color }};">
                                {{ $log->accionLabel() }}
                            </span>
                        </td>
                        <td style="font-size:0.8125rem; color:var(--muted-foreground); max-width:280px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $log->descripcion }}
                        </td>
                        <td>
                            @if($log->entidad)
                            <span style="font-size:0.75rem; color:var(--muted-foreground); font-family:monospace;">
                                {{ $log->entidad }} #{{ $log->entidad_id }}
                            </span>
                            @else
                            <span style="color:var(--muted-foreground);">—</span>
                            @endif
                        </td>
                        <td style="font-size:0.75rem; color:var(--muted-foreground); font-family:monospace;">
                            {{ $log->ip ?? '—' }}
                        </td>
                        <td style="font-size:0.75rem; color:var(--muted-foreground);">
                            <span title="{{ $log->created_at->format('d/m/Y H:i:s') }}">
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:2.5rem; color:var(--muted-foreground);">
                            <i data-lucide="clock" style="width:1.5rem;height:1.5rem; opacity:0.4; margin-bottom:0.5rem;"></i>
                            <p style="font-size:0.875rem; margin:0;">Sin actividad registrada todavía</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════════ --}}
    {{-- ── SECCIÓN 4: AJUSTES DE PLATAFORMA ── --}}
    {{-- ══════════════════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title" style="display:flex; align-items:center; gap:0.5rem;">
                <i data-lucide="settings" style="width:1rem;height:1rem; color:var(--primary);"></i>
                Ajustes de la Plataforma
            </h2>
            <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                Configuración global de Urbexium — sin tocar código
            </p>
        </div>
        <div class="card-content">
            <form method="POST" action="{{ route('admin.ajustes.guardar') }}">
                @csrf
                <div style="display:flex; flex-direction:column; gap:1.5rem;">

                    {{-- Fila de toggles --}}
                    <div class="admin-grid-2" style="gap:1rem;">

                        {{-- Modo mantenimiento --}}
                        <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="stat-icon" style="background:color-mix(in oklch, var(--destructive) 12%, transparent);">
                                    <i data-lucide="wrench" style="width:1.1rem;height:1.1rem; color:var(--destructive);"></i>
                                </div>
                                <div>
                                    <p style="font-weight:600; margin:0; font-size:0.9375rem;">Modo mantenimiento</p>
                                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                                        Bloquea el acceso a usuarios no-admin
                                    </p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="modo_mantenimiento" value="true"
                                    {{ ($platformSettings['modo_mantenimiento']?->valor ?? 'false') === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>

                        {{-- Registro abierto --}}
                        <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="stat-icon" style="background:color-mix(in oklch, var(--primary) 12%, transparent);">
                                    <i data-lucide="user-plus" style="width:1.1rem;height:1.1rem; color:var(--primary);"></i>
                                </div>
                                <div>
                                    <p style="font-weight:600; margin:0; font-size:0.9375rem;">Registro abierto</p>
                                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                                        Permite que nuevos usuarios se registren
                                    </p>
                                </div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="registro_abierto" value="true"
                                    {{ ($platformSettings['registro_abierto']?->valor ?? 'true') === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>

                    {{-- Fila numérica + texto --}}
                    <div class="admin-grid-2" style="gap:1rem;">

                        {{-- Límite de spots --}}
                        <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; flex-direction:column; gap:0.75rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="stat-icon" style="background:color-mix(in oklch, var(--accent) 12%, transparent);">
                                    <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem; color:var(--accent);"></i>
                                </div>
                                <div>
                                    <p style="font-weight:600; margin:0; font-size:0.9375rem;">Límite de spots por usuario</p>
                                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                                        Máximo de spots que puede crear un usuario
                                    </p>
                                </div>
                            </div>
                            <input type="number" name="limite_spots_usuario" min="1" max="500"
                                value="{{ $platformSettings['limite_spots_usuario']?->valor ?? 20 }}"
                                style="background:var(--input); border:1px solid var(--border);
                                    border-radius:calc(var(--radius)-4px); padding:0.625rem 0.875rem;
                                    font-size:0.875rem; color:var(--foreground); font-family:inherit;
                                    width:100%; box-sizing:border-box; outline:none;"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'" />
                        </div>

                        {{-- Mensaje aviso global --}}
                        <div style="border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem; display:flex; flex-direction:column; gap:0.75rem;">
                            <div style="display:flex; align-items:center; gap:0.75rem;">
                                <div class="stat-icon" style="background:color-mix(in oklch, oklch(0.65 0.18 280) 12%, transparent);">
                                    <i data-lucide="megaphone" style="width:1.1rem;height:1.1rem; color:oklch(0.65 0.18 280);"></i>
                                </div>
                                <div>
                                    <p style="font-weight:600; margin:0; font-size:0.9375rem;">Mensaje de aviso global</p>
                                    <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0;">
                                        Se muestra como banner en toda la web (vacío = oculto)
                                    </p>
                                </div>
                            </div>
                            <input type="text" name="mensaje_aviso_global" maxlength="500"
                                placeholder="Ej: Mañana habrá mantenimiento a las 02:00h"
                                value="{{ $platformSettings['mensaje_aviso_global']?->valor ?? '' }}"
                                style="background:var(--input); border:1px solid var(--border);
                                    border-radius:calc(var(--radius)-4px); padding:0.625rem 0.875rem;
                                    font-size:0.875rem; color:var(--foreground); font-family:inherit;
                                    width:100%; box-sizing:border-box; outline:none;"
                                onfocus="this.style.borderColor='var(--primary)'"
                                onblur="this.style.borderColor='var(--border)'" />
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" style="width:1rem;height:1rem;"></i>
                            Guardar ajustes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
/* ── Toggle switch ─────────────────────────────────────────────────────── */
.toggle-switch {
    position: relative;
    display: inline-block;
    width: 2.75rem;
    height: 1.5rem;
    flex-shrink: 0;
    cursor: pointer;
}
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute;
    inset: 0;
    background: var(--muted);
    border-radius: 9999px;
    transition: background 200ms;
    border: 1px solid var(--border);
}
.toggle-slider::before {
    content: '';
    position: absolute;
    left: 3px; top: 50%;
    transform: translateY(-50%);
    width: 1rem; height: 1rem;
    background: white;
    border-radius: 50%;
    transition: left 200ms;
    box-shadow: 0 1px 3px rgba(0,0,0,0.25);
}
.toggle-switch input:checked + .toggle-slider {
    background: var(--primary);
    border-color: var(--primary);
}
.toggle-switch input:checked + .toggle-slider::before {
    left: calc(100% - 1.1875rem);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function switchTab(group, tabName, btn) {
    document.querySelectorAll(`[id^="${group}-tab-"]`).forEach(el => el.classList.remove('active'));
    btn.parentElement.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(`${group}-tab-${tabName}`).classList.add('active');
    btn.classList.add('active');
}

// ── Helpers de color ────────────────────────────────────────────────────────
const root = getComputedStyle(document.documentElement);
const getCssVar = v => root.getPropertyValue(v).trim() || '#888';

document.addEventListener('DOMContentLoaded', () => {

    // ── 1. Registros por mes (line chart) ────────────────────────────────
    new Chart(document.getElementById('chartRegistros'), {
        type: 'bar',
        data: {
            labels: @json($mesesLabels ?? []),
            datasets: [{
                label: 'Nuevos usuarios',
                data: @json($mesesData ?? []),
                backgroundColor: 'oklch(0.55 0.22 250 / 0.25)',
                borderColor: 'oklch(0.55 0.22 250)',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    titleColor: '#fff',
                    bodyColor: '#ccc',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} usuarios`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#888', font: { size: 11 } } },
                y: { grid: { color: 'rgba(128,128,128,0.1)' }, ticks: { color: '#888', stepSize: 1, font: { size: 11 } }, beginAtZero: true }
            }
        }
    });

    // ── 2. Pedidos por estado (doughnut) ─────────────────────────────────
    const estadosData = @json($estadosMap ?? []);
    new Chart(document.getElementById('chartPedidosEstado'), {
        type: 'doughnut',
        data: {
            labels: ['Pendiente', 'Procesando', 'Enviado', 'Entregado', 'Cancelado'],
            datasets: [{
                data: [
                    estadosData.pendiente  || 0,
                    estadosData.procesando || 0,
                    estadosData.enviado    || 0,
                    estadosData.entregado  || 0,
                    estadosData.cancelado  || 0,
                ],
                backgroundColor: ['#f59e0b','#3b82f6','#8b5cf6','#22c55e','#ef4444'],
                borderWidth: 2,
                borderColor: 'var(--card)',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#888', font: { size: 11 }, padding: 10, boxWidth: 12 }
                },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    bodyColor: '#ccc',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} pedido(s)`
                    }
                }
            }
        }
    });

    // ── 3. Spots aprobados (line chart) ──────────────────────────────────
    new Chart(document.getElementById('chartSpotsAprobados'), {
        type: 'line',
        data: {
            labels: @json($spotsMeses ?? []),
            datasets: [{
                label: 'Spots verificados',
                data: @json($spotsAprobData ?? []),
                borderColor: 'oklch(0.65 0.2 150)',
                backgroundColor: 'oklch(0.65 0.2 150 / 0.12)',
                borderWidth: 2.5,
                pointRadius: 5,
                pointBackgroundColor: 'oklch(0.65 0.2 150)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                fill: true,
                tension: 0.35,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a1a2e',
                    titleColor: '#fff',
                    bodyColor: '#ccc',
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} spots verificados`
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#888', font: { size: 11 } } },
                y: { grid: { color: 'rgba(128,128,128,0.1)' }, ticks: { color: '#888', stepSize: 1, font: { size: 11 } }, beginAtZero: true }
            }
        }
    });

});
</script>

@endsection
