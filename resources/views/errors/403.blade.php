{{-- resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html lang="es" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': dark }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 · Acceso denegado</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --background: oklch(0.985 0 0);
            --foreground: oklch(0.141 0.005 285.75);
            --card: oklch(1 0 0);
            --border: oklch(0.922 0 0);
            --muted-foreground: oklch(0.552 0.016 285.938);
            --primary: oklch(0.546 0.245 262.881);
            --primary-foreground: oklch(0.97 0.014 254.604);
            --destructive: oklch(0.577 0.245 27.325);
            --radius: 0.625rem;
        }
        .dark {
            --background: oklch(0.141 0.005 285.75);
            --foreground: oklch(0.985 0 0);
            --card: oklch(0.18 0.006 285.885);
            --border: oklch(0.274 0.006 286.033);
            --muted-foreground: oklch(0.705 0.015 286.067);
            --primary: oklch(0.707 0.165 254.624);
            --primary-foreground: oklch(0.18 0.045 264.695);
            --destructive: oklch(0.704 0.191 22.216);
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

        .error-icon {
            width: 6rem; height: 6rem;
            border-radius: 50%;
            background: color-mix(in oklch, var(--destructive) 12%, transparent);
            border: 2px solid color-mix(in oklch, var(--destructive) 30%, transparent);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 2rem;
            animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 color-mix(in oklch, var(--destructive) 20%, transparent); }
            50% { box-shadow: 0 0 0 12px color-mix(in oklch, var(--destructive) 0%, transparent); }
        }

        .error-code {
            font-size: clamp(5rem, 15vw, 9rem);
            font-weight: 900;
            letter-spacing: -0.06em;
            line-height: 1;
            background: linear-gradient(135deg, var(--destructive), color-mix(in oklch, var(--destructive) 60%, var(--primary)));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.03em;
        }

        .error-msg {
            color: var(--muted-foreground);
            font-size: 1rem;
            max-width: 420px;
            line-height: 1.6;
            margin: 0 auto 2rem;
        }

        .btn-group { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }

        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            height: 2.5rem; padding: 0 1.25rem;
            border-radius: var(--radius); font-size: 0.875rem;
            font-weight: 600; text-decoration: none; cursor: pointer;
            border: 1px solid var(--border); transition: all 150ms;
        }
        .btn-primary {
            background: var(--primary); color: var(--primary-foreground);
            border-color: var(--primary);
            box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 35%, transparent);
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-ghost { background: var(--card); color: var(--foreground); }
        .btn-ghost:hover { background: color-mix(in oklch, var(--foreground) 8%, transparent); }

        .divider {
            width: 3rem; height: 2px;
            background: color-mix(in oklch, var(--destructive) 40%, transparent);
            margin: 1.5rem auto;
            border-radius: 1px;
        }

        .brand {
            position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%);
            font-size: 1rem; font-weight: 800; letter-spacing: -0.04em;
            color: var(--foreground); text-decoration: none;
            display: flex; align-items: center; gap: 0.4rem;
            opacity: 0.8;
        }
        .brand:hover { opacity: 1; }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" class="brand">
        <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem;color:var(--primary);"></i>
        Urbex
    </a>

    <div class="error-icon">
        <i data-lucide="shield-off" style="width:2.5rem;height:2.5rem;color:var(--destructive);"></i>
    </div>

    <p class="error-code">403</p>
    <h1 class="error-title">Acceso denegado</h1>
    <div class="divider"></div>
    <p class="error-msg">
        {{ $exception->getMessage() ?: 'No tienes permisos para acceder a esta sección. Si crees que es un error, contacta con un administrador.' }}
    </p>

    <div class="btn-group">
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <i data-lucide="home" style="width:1rem;height:1rem;"></i>
                Ir al inicio
            </a>
            <a href="javascript:history.back()" class="btn btn-ghost">
                <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i>
                Volver atrás
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary">
                <i data-lucide="log-in" style="width:1rem;height:1rem;"></i>
                Iniciar sesión
            </a>
            <a href="{{ url('/') }}" class="btn btn-ghost">
                <i data-lucide="home" style="width:1rem;height:1rem;"></i>
                Portada
            </a>
        @endauth
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
