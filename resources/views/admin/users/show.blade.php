{{-- resources/views/admin/users/show.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Usuario: ' . $user->nombre)

@section('content')
<style>
    .profile-grid { display:grid; grid-template-columns:320px 1fr; gap:1.5rem; align-items:start; }
    @media(max-width:900px){ .profile-grid { grid-template-columns:1fr; } }
    .card         { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.25rem 1.5rem; }
    .card-title   { font-size:1rem; font-weight:700; margin:0 0 1rem; display:flex; align-items:center; gap:0.5rem; }
    .avatar-lg    { width:5rem; height:5rem; border-radius:50%; object-fit:cover; background:var(--secondary); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; flex-shrink:0; }
    .meta-row     { display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid var(--border); font-size:0.875rem; }
    .meta-row:last-child { border-bottom:none; }
    .meta-label   { color:var(--muted-foreground); font-weight:500; }
    .badge        { display:inline-flex; align-items:center; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.7rem; font-weight:600; text-transform:uppercase; }
    .badge-admin  { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .badge-mod    { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .badge-user   { background:var(--secondary); color:var(--muted-foreground); }
    .badge-ban    { background:color-mix(in oklch,var(--destructive) 20%,transparent); color:var(--destructive); }
    .spot-mini    { display:flex; align-items:center; gap:0.75rem; padding:0.6rem 0; border-bottom:1px solid var(--border); font-size:0.875rem; }
    .spot-mini:last-child { border-bottom:none; }
    .spot-mini img { width:3rem; height:3rem; border-radius:calc(var(--radius)-2px); object-fit:cover; background:var(--secondary); flex-shrink:0; }
</style>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
    <h1 style="font-size:1.5rem;font-weight:700;letter-spacing:-0.03em;margin:0;display:flex;align-items:center;gap:0.6rem;">
        <i data-lucide="user" style="width:1.4rem;height:1.4rem;"></i>
        Perfil de {{ $user->nombre }}
    </h1>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm">
            <i data-lucide="pencil" style="width:1rem;height:1rem;"></i> Editar usuario
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Volver
        </a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
@endif

<div class="profile-grid">

    {{-- Columna izquierda: info + acciones --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        <div class="card" style="text-align:center;">
            @if($user->avatar)
                <img src="{{ asset('storage/'.$user->avatar) }}" class="avatar-lg" alt="" style="margin:0 auto 1rem;">
            @else
                <div class="avatar-lg" style="margin:0 auto 1rem;">{{ strtoupper(substr($user->nombre,0,1)) }}</div>
            @endif
            <div style="font-size:1.25rem;font-weight:700;">{{ $user->nombre }}</div>
            <div style="color:var(--muted-foreground);font-size:0.875rem;margin-bottom:0.75rem;">{{ $user->email }}</div>
            @php $rol = $user->rol?->nombre ?? 'usuario'; @endphp
            <span class="badge badge-{{ $rol }}">{{ ucfirst($rol) }}</span>
            @if($user->baneado)
                <span class="badge badge-ban" style="margin-left:0.4rem;">Baneado</span>
            @endif
        </div>

        <div class="card">
            <p class="card-title"><i data-lucide="info" style="width:1rem;height:1rem;"></i> Detalles</p>
            <div class="meta-row">
                <span class="meta-label">ID</span>
                <span>#{{ $user->id }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Registro</span>
                <span>{{ $user->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Spots creados</span>
                <span>{{ $user->localizaciones->count() }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Pedidos</span>
                <span>{{ $user->pedidos->count() }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Reportes enviados</span>
                <span>{{ $user->reportes->count() }}</span>
            </div>
        </div>

        @if(!$user->esAdmin())
        <div class="card">
            <p class="card-title"><i data-lucide="settings" style="width:1rem;height:1rem;"></i> Acciones</p>

            <form method="POST" action="{{ route('admin.users.ban', $user) }}" style="margin-bottom:0.75rem;">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-sm {{ $user->baneado ? 'btn-primary' : 'btn-destructive' }}" style="width:100%;">
                    <i data-lucide="{{ $user->baneado ? 'shield-check' : 'ban' }}" style="width:1rem;height:1rem;"></i>
                    {{ $user->baneado ? 'Desbanear usuario' : 'Banear usuario' }}
                </button>
            </form>

            <form method="POST" action="{{ route('admin.users.rol', $user) }}">
                @csrf @method('PATCH')
                <div style="display:flex;gap:0.5rem;align-items:center;">
                    <select name="rol" style="flex:1;height:2.25rem;padding:0 0.75rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;">
                        <option value="usuario"   {{ $rol === 'usuario'   ? 'selected' : '' }}>Usuario</option>
                        <option value="moderador" {{ $rol === 'moderador' ? 'selected' : '' }}>Moderador</option>
                        <option value="admin"     {{ $rol === 'admin'     ? 'selected' : '' }}>Admin</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Cambiar</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    {{-- Columna derecha: spots y actividad --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        <div class="card">
            <p class="card-title"><i data-lucide="map-pin" style="width:1rem;height:1rem;"></i> Spots creados ({{ $user->localizaciones->count() }})</p>
            @forelse($user->localizaciones->take(10) as $spot)
                <div class="spot-mini">
                    @php $img = $spot->imagenes->first(); @endphp
                    @if($img)
                        <img src="{{ asset('storage/'.$img->ruta) }}" alt="">
                    @else
                        <div style="width:3rem;height:3rem;border-radius:calc(var(--radius)-2px);background:var(--secondary);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <i data-lucide="map-pin" style="width:1.2rem;height:1.2rem;color:var(--muted-foreground);"></i>
                        </div>
                    @endif
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $spot->nombre }}</div>
                        <div style="font-size:0.75rem;color:var(--muted-foreground);">
                            {{ ucfirst($spot->verification_status) }} · {{ $spot->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                    <a href="{{ route('spots.show', $spot) }}" class="btn btn-ghost btn-sm" style="font-size:0.75rem;">Ver</a>
                </div>
            @empty
                <p style="color:var(--muted-foreground);font-size:0.875rem;margin:0;">Sin spots creados.</p>
            @endforelse
        </div>

        @if($user->pedidos->count())
        <div class="card">
            <p class="card-title"><i data-lucide="shopping-bag" style="width:1rem;height:1rem;"></i> Pedidos recientes</p>
            @foreach($user->pedidos->take(5) as $pedido)
                <div class="meta-row">
                    <span>#{{ $pedido->id }} · {{ $pedido->created_at->format('d/m/Y') }}</span>
                    <span style="font-weight:600;">{{ number_format($pedido->total, 2) }} €</span>
                </div>
            @endforeach
        </div>
        @endif

    </div>
</div>

@endsection
