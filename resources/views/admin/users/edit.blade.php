{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Editar usuario: ' . $user->nombre)

@section('content')
<style>
    .edit-grid { display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start; }
    @media(max-width:860px){ .edit-grid { grid-template-columns:1fr; } }
    .card      { background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:1.5rem; }
    .card-title{ font-size:1rem; font-weight:700; margin:0 0 1.25rem; display:flex; align-items:center; gap:0.5rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem; }
    .form-group{ margin-bottom:1.25rem; }
    .form-group label { display:block; font-size:0.875rem; font-weight:600; margin-bottom:0.4rem; }
    .form-group input, .form-group select, .form-group textarea {
        width:100%; box-sizing:border-box;
        padding:0.5rem 0.75rem; border:1px solid var(--border);
        border-radius:var(--radius); background:var(--background);
        color:var(--foreground); font-size:0.875rem;
        transition:border-color 150ms, box-shadow 150ms;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline:none; border-color:var(--primary);
        box-shadow:0 0 0 3px color-mix(in oklch,var(--primary) 20%,transparent);
    }
    .form-group textarea { resize:vertical; min-height:100px; }
    .form-group .hint { font-size:0.75rem; color:var(--muted-foreground); margin-top:0.3rem; }
    .form-error { font-size:0.75rem; color:var(--destructive); margin-top:0.3rem; }
    .avatar-preview { width:5rem; height:5rem; border-radius:50%; object-fit:cover; background:var(--secondary); display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700; margin-bottom:1rem; }
    .toggle-row { display:flex; align-items:center; justify-content:space-between; padding:0.75rem 0; border-bottom:1px solid var(--border); }
    .toggle-row:last-child { border-bottom:none; }
    .toggle-label { font-size:0.875rem; font-weight:500; }
    .toggle-hint  { font-size:0.75rem; color:var(--muted-foreground); margin-top:0.1rem; }
    /* Toggle switch */
    .switch { position:relative; display:inline-block; width:2.75rem; height:1.5rem; flex-shrink:0; }
    .switch input { opacity:0; width:0; height:0; }
    .slider { position:absolute; cursor:pointer; inset:0; background:var(--border); border-radius:999px; transition:background 200ms; }
    .slider:before { position:absolute; content:''; height:1.1rem; width:1.1rem; left:0.2rem; bottom:0.2rem; background:#fff; border-radius:50%; transition:transform 200ms; }
    input:checked + .slider { background:var(--primary); }
    input:checked + .slider:before { transform:translateX(1.25rem); }
</style>

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem; flex-wrap:wrap; gap:1rem;">
    <h1 style="font-size:1.5rem; font-weight:700; letter-spacing:-0.03em; margin:0; display:flex; align-items:center; gap:0.6rem;">
        <i data-lucide="user-cog" style="width:1.4rem;height:1.4rem;"></i>
        Editar usuario
    </h1>
    <div style="display:flex;gap:0.5rem;">
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-ghost btn-sm">
            <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i> Ver perfil
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">Usuarios</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.25rem;">{{ session('success') }}</div>
@endif

<div class="edit-grid">

    {{-- ─── Formulario principal ─── --}}
    <div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="card" style="margin-bottom:1rem;">
                <p class="card-title"><i data-lucide="user" style="width:1rem;height:1rem;"></i> Información personal</p>

                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}" required>
                    @error('nombre') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="bio">Biografía</label>
                    <textarea id="bio" name="bio">{{ old('bio', $user->bio) }}</textarea>
                    <p class="hint">Descripción pública del usuario.</p>
                    @error('bio') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group">
                    <label for="avatar">Avatar</label>
                    @if($user->avatar)
                        <div style="margin-bottom:0.75rem;display:flex;align-items:center;gap:1rem;">
                            <img src="{{ asset('storage/'.$user->avatar) }}" style="width:4rem;height:4rem;border-radius:50%;object-fit:cover;" alt="">
                            <span style="font-size:0.8rem;color:var(--muted-foreground);">Avatar actual</span>
                        </div>
                    @endif
                    <input type="file" id="avatar" name="avatar" accept="image/*">
                    <p class="hint">JPG, PNG o WEBP. Máx. 2MB. Dejar vacío para no cambiar.</p>
                    @error('avatar') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="card" style="margin-bottom:1rem;">
                <p class="card-title"><i data-lucide="lock" style="width:1rem;height:1rem;"></i> Cambiar contraseña</p>

                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <input type="password" id="password" name="password" autocomplete="new-password">
                    <p class="hint">Dejar vacío para no cambiar la contraseña.</p>
                    @error('password') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" style="width:1rem;height:1rem;"></i> Guardar cambios
            </button>
        </form>
    </div>

    {{-- ─── Panel lateral: rol y estado ─── --}}
    <div style="display:flex;flex-direction:column;gap:1rem;">

        {{-- Rol --}}
        @if(!$user->esAdmin() || Auth::user()->id !== $user->id)
        <div class="card">
            <p class="card-title"><i data-lucide="shield" style="width:1rem;height:1rem;"></i> Rol y acceso</p>
            <form method="POST" action="{{ route('admin.users.rol', $user) }}">
                @csrf @method('PATCH')
                <div class="form-group" style="margin-bottom:0.75rem;">
                    <label for="rol">Rol del usuario</label>
                    <select id="rol" name="rol">
                        <option value="usuario"   {{ ($user->rol?->nombre ?? 'usuario') === 'usuario'   ? 'selected' : '' }}>Usuario</option>
                        <option value="moderador" {{ ($user->rol?->nombre) === 'moderador' ? 'selected' : '' }}>Moderador</option>
                        <option value="admin"     {{ ($user->rol?->nombre) === 'admin'     ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Actualizar rol</button>
            </form>
        </div>
        @endif

        {{-- Estado --}}
        <div class="card">
            <p class="card-title"><i data-lucide="alert-circle" style="width:1rem;height:1rem;"></i> Estado de la cuenta</p>

            <div class="toggle-row">
                <div>
                    <div class="toggle-label">Cuenta baneada</div>
                    <div class="toggle-hint">El usuario no puede acceder a la plataforma</div>
                </div>
                <form method="POST" action="{{ route('admin.users.ban', $user) }}">
                    @csrf @method('PATCH')
                    <label class="switch">
                        <input type="checkbox" {{ $user->baneado ? 'checked' : '' }} onchange="this.form.submit()" {{ $user->esAdmin() ? 'disabled' : '' }}>
                        <span class="slider"></span>
                    </label>
                </form>
            </div>

            @if($user->esAdmin())
                <p style="font-size:0.75rem;color:var(--muted-foreground);margin:0.75rem 0 0;">Los administradores no pueden ser baneados.</p>
            @endif
        </div>

        {{-- Info del usuario --}}
        <div class="card">
            <p class="card-title"><i data-lucide="info" style="width:1rem;height:1rem;"></i> Estadísticas</p>
            <div style="display:flex;flex-direction:column;gap:0.5rem;font-size:0.875rem;">
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted-foreground);">Registro</span> <span>{{ $user->created_at->format('d/m/Y') }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted-foreground);">Spots</span> <span>{{ $user->localizaciones()->count() }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted-foreground);">Reportes</span> <span>{{ $user->reportes()->count() }}</span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted-foreground);">Pedidos</span> <span>{{ $user->pedidos()->count() }}</span></div>
            </div>
        </div>

    </div>
</div>
@endsection
