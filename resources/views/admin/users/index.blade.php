{{-- resources/views/admin/users/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Usuarios')

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
    .avatar       { width:2rem; height:2rem; border-radius:50%; object-fit:cover; background:var(--secondary); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.8rem; flex-shrink:0; }
    .user-info    { display:flex; align-items:center; gap:0.6rem; }
    .badge        { display:inline-flex; align-items:center; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; text-transform:uppercase; letter-spacing:.04em; }
    .badge-admin  { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .badge-mod    { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .badge-user   { background:var(--secondary); color:var(--muted-foreground); }
    .badge-ban    { background:color-mix(in oklch,var(--destructive) 20%,transparent); color:var(--destructive); }
    .action-btns  { display:flex; gap:0.4rem; }
    .btn-xs       { padding:0.25rem 0.6rem; font-size:0.75rem; border-radius:calc(var(--radius) - 2px); border:1px solid var(--border); cursor:pointer; background:var(--card); color:var(--foreground); transition:background 120ms; text-decoration:none; display:inline-flex; align-items:center; gap:0.3rem; }
    .btn-xs:hover { background:var(--secondary); }
    .btn-xs.danger { color:var(--destructive); border-color:color-mix(in oklch,var(--destructive) 40%,transparent); }
    .btn-xs.danger:hover { background:color-mix(in oklch,var(--destructive) 10%,transparent); }
    .btn-xs.warning { color:var(--accent); border-color:color-mix(in oklch,var(--accent) 40%,transparent); }
</style>

<div class="admin-header">
    <h1 class="admin-title">
        <i data-lucide="users" style="width:1.4rem;height:1.4rem;"></i>
        Gestión de Usuarios
    </h1>
    <a href="{{ route('admin.index') }}" class="btn btn-ghost btn-sm">
        <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Volver al panel
    </a>
</div>

{{-- Alertas --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
@endif

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.users.index') }}">
    <div class="filter-bar">
        <input type="text" name="q" placeholder="Buscar por nombre o email…" value="{{ request('q') }}">
        <select name="rol">
            <option value="">Todos los roles</option>
            <option value="admin"      {{ request('rol') === 'admin'      ? 'selected' : '' }}>Admin</option>
            <option value="moderador"  {{ request('rol') === 'moderador'  ? 'selected' : '' }}>Moderador</option>
            <option value="usuario"    {{ request('rol') === 'usuario'    ? 'selected' : '' }}>Usuario</option>
        </select>
        <select name="baneado">
            <option value="">Todos</option>
            <option value="1" {{ request('baneado') === '1' ? 'selected' : '' }}>Baneados</option>
            <option value="0" {{ request('baneado') === '0' ? 'selected' : '' }}>Activos</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Limpiar</a>
    </div>
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Registro</th>
                <th>Spots</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div class="user-info">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" class="avatar" alt="">
                        @else
                            <div class="avatar">{{ strtoupper(substr($user->nombre,0,1)) }}</div>
                        @endif
                        <div>
                            <div style="font-weight:600;">{{ $user->nombre }}</div>
                            <div style="font-size:0.75rem;color:var(--muted-foreground);">#{{ $user->id }}</div>
                        </div>
                    </div>
                </td>
                <td style="color:var(--muted-foreground);">{{ $user->email }}</td>
                <td>
                    @php $rol = $user->rol?->nombre ?? 'usuario'; @endphp
                    <span class="badge badge-{{ $rol }}">{{ ucfirst($rol) }}</span>
                </td>
                <td>
                    @if($user->baneado)
                        <span class="badge badge-ban">Baneado</span>
                    @else
                        <span style="color:var(--primary);font-size:0.8rem;font-weight:600;">Activo</span>
                    @endif
                </td>
                <td style="color:var(--muted-foreground);font-size:0.8rem;">{{ $user->created_at->format('d/m/Y') }}</td>
                <td style="text-align:center;">{{ $user->localizaciones_count ?? $user->localizaciones()->count() }}</td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-xs">
                            <i data-lucide="eye" style="width:.9rem;height:.9rem;"></i> Ver
                        </a>

                        {{-- Ban/Desban --}}
                        @if(!$user->esAdmin())
                        <form method="POST" action="{{ route('admin.users.ban', $user) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-xs {{ $user->baneado ? 'warning' : 'danger' }}">
                                <i data-lucide="{{ $user->baneado ? 'shield-check' : 'ban' }}" style="width:.9rem;height:.9rem;"></i>
                                {{ $user->baneado ? 'Desbanear' : 'Banear' }}
                            </button>
                        </form>

                        {{-- Cambiar rol --}}
                        <form method="POST" action="{{ route('admin.users.rol', $user) }}" style="display:inline;display:flex;align-items:center;gap:0.25rem;">
                            @csrf @method('PATCH')
                            <select name="rol" onchange="this.form.submit()" style="height:1.8rem;padding:0 0.5rem;font-size:0.75rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);">
                                <option value="usuario"   {{ $rol === 'usuario'   ? 'selected' : '' }}>Usuario</option>
                                <option value="moderador" {{ $rol === 'moderador' ? 'selected' : '' }}>Moderador</option>
                                <option value="admin"     {{ $rol === 'admin'     ? 'selected' : '' }}>Admin</option>
                            </select>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem;color:var(--muted-foreground);">
                    No se encontraron usuarios.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:1rem;">
    {{ $users->links() }}
</div>

@endsection
