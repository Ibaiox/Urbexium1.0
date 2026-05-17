{{-- resources/views/errors/503.blade.php --}}
{{--
    En vistas de error, @auth / Auth::check() NO funciona porque el
    ServiceProvider de autenticación no se ha resuelto en ese punto.
    Solución: leer la sesión directamente con la clave interna de Laravel
    y buscar el usuario en la BD manualmente.

    Clave de sesión: 'login_web_' . sha1(\Illuminate\Auth\SessionGuard::class)
    → 'login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'
--}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento · Urbexium</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --background: oklch(0.141 0.005 285.75);
            --foreground: oklch(0.985 0 0);
            --card: oklch(0.18 0.006 285.885);
            --border: oklch(0.274 0.006 286.033);
            --muted-foreground: oklch(0.705 0.015 286.067);
            --primary: oklch(0.707 0.165 254.624);
            --primary-foreground: oklch(0.18 0.045 264.695);
            --accent: oklch(0.769 0.188 70.08);
            --destructive: oklch(0.704 0.191 22.216);
            --radius: 0.625rem;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--background);
            color: var(--foreground);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
        }

        .brand {
            position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%);
            font-size: 1rem; font-weight: 800; letter-spacing: -0.04em;
            color: var(--foreground); text-decoration: none;
            display: flex; align-items: center; gap: 0.4rem;
        }

        .icon-wrap {
            width: 6rem; height: 6rem;
            border-radius: 50%;
            background: color-mix(in oklch, var(--accent) 12%, transparent);
            border: 2px solid color-mix(in oklch, var(--accent) 30%, transparent);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 color-mix(in oklch, var(--accent) 25%, transparent); }
            50% { box-shadow: 0 0 0 14px color-mix(in oklch, var(--accent) 0%, transparent); }
        }

        .code {
            font-size: clamp(4rem, 12vw, 7rem);
            font-weight: 900;
            letter-spacing: -0.06em;
            line-height: 1;
            background: linear-gradient(135deg, var(--accent), color-mix(in oklch, var(--primary) 60%, var(--accent)));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .title {
            font-size: 1.5rem; font-weight: 700;
            margin-bottom: 0.75rem; letter-spacing: -0.03em;
        }

        .divider {
            width: 3rem; height: 2px;
            background: color-mix(in oklch, var(--accent) 40%, transparent);
            margin: 1.25rem auto; border-radius: 1px;
        }

        .msg {
            color: var(--muted-foreground);
            font-size: 0.9375rem; max-width: 400px;
            line-height: 1.7; margin: 0 auto 2rem;
        }

        .badge {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: color-mix(in oklch, var(--accent) 10%, transparent);
            border: 1px solid color-mix(in oklch, var(--accent) 25%, transparent);
            border-radius: 999px; font-size: 0.8125rem;
            color: var(--accent); font-weight: 600; margin-bottom: 2rem;
        }

        .btn-group { display: flex; flex-direction: column; align-items: center; gap: 0.75rem; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            height: 2.5rem; padding: 0 1.25rem;
            border-radius: var(--radius); font-size: 0.875rem;
            font-weight: 600; text-decoration: none; cursor: pointer;
            border: 1px solid var(--border);
            background: color-mix(in oklch, var(--foreground) 6%, transparent);
            color: var(--foreground); transition: all 150ms; font-family: inherit;
        }
        .btn:hover { background: color-mix(in oklch, var(--foreground) 12%, transparent); }
        .btn-danger {
            background: color-mix(in oklch, var(--destructive) 10%, transparent);
            border-color: color-mix(in oklch, var(--destructive) 30%, transparent);
            color: var(--destructive);
        }
        .btn-danger:hover { background: color-mix(in oklch, var(--destructive) 18%, transparent); }

        .session-info {
            font-size: 0.8rem; color: var(--muted-foreground); margin-bottom: 0.25rem;
        }
    </style>
</head>
<body>

@php
    /**
     * En vistas de error, el stack de middlewares de autenticación no se ejecuta,
     * por lo que Auth::check() y la directiva @auth devuelven siempre false.
     *
     * Solución: leer la sesión directamente con la clave interna que usa
     * Illuminate\Auth\SessionGuard para persistir el ID del usuario autenticado,
     * y luego buscarlo en la BD manualmente con Eloquent.
     *
     * La clave es: 'login_web_' . sha1(\Illuminate\Auth\SessionGuard::class)
     * Esto produce: 'login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'
     */
    $sessionUser = null;

    try {
        $sessionKey = 'login_web_' . sha1(\Illuminate\Auth\SessionGuard::class);
        $userId     = session($sessionKey);   // null si no hay sesión activa

        if ($userId) {
            $sessionUser = \App\Models\User::with('rol')->find($userId);
        }
    } catch (\Exception $e) {
        // Si la sesión no está disponible en este punto del ciclo (muy raro
        // pero posible en algunos setups de caché/sesión personalizados),
        // simplemente mostramos el enlace al login.
    }
@endphp

    <a href="{{ url('/') }}" class="brand">
        <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem;color:var(--primary);"></i>
        Urbexium
    </a>

    <div class="icon-wrap">
        <i data-lucide="wrench" style="width:2.5rem;height:2.5rem;color:var(--accent);"></i>
    </div>

    <p class="code">503</p>
    <h1 class="title">En mantenimiento</h1>
    <div class="divider"></div>

    <div class="badge">
        <i data-lucide="clock" style="width:0.875rem;height:0.875rem;"></i>
        Volvemos pronto
    </div>

    <p class="msg">
        Estamos realizando tareas de mantenimiento para mejorar la plataforma.<br>
        Disculpa las molestias.
    </p>

    <div class="btn-group">

        @if($sessionUser)
            {{--
                Hay un usuario en sesión. Mostramos su nombre y rol,
                y un formulario POST a la ruta 'logout'.

                Nota: la ruta 'logout' debe estar excluida del middleware
                CheckMantenimiento (ya lo está en tu implementación actual),
                y el LoginController NO debe exigir middleware('auth') en logout
                porque en modo mantenimiento Auth::check() puede ser false
                aunque la sesión exista. Ver LoginController.php.
            --}}
            <p class="session-info">
                Sesión activa como <strong>{{ $sessionUser->nombre }}</strong>
                ({{ $sessionUser->rol?->nombre ?? 'usuario' }})
            </p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">
                    <i data-lucide="log-out" style="width:1rem;height:1rem;"></i>
                    Cerrar sesión e iniciar como admin
                </button>
            </form>

        @else
            {{-- No hay sesión activa: ofrecemos ir al login --}}
            <a href="{{ route('login') }}" class="btn">
                <i data-lucide="log-in" style="width:1rem;height:1rem;"></i>
                Acceso administrador
            </a>
        @endif

    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
