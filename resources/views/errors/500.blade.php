{{-- resources/views/errors/500.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 · Error del servidor</title>
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
            --accent: oklch(0.769 0.188 70.08);
            --radius: 0.625rem;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--background);
            color: var(--foreground);
            min-height: 100vh;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 2rem; text-align: center;
        }
        .error-code {
            font-size: clamp(5rem, 15vw, 9rem);
            font-weight: 900; letter-spacing: -0.06em; line-height: 1;
            background: linear-gradient(135deg, var(--accent), color-mix(in oklch, var(--accent) 60%, var(--primary)));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; margin-bottom: 0.5rem;
        }
        .error-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.75rem; letter-spacing: -0.03em; }
        .error-msg { color: var(--muted-foreground); font-size: 1rem; max-width: 420px; line-height: 1.6; margin: 0 auto 2rem; }
        .divider { width: 3rem; height: 2px; background: color-mix(in oklch, var(--accent) 40%, transparent); margin: 1.5rem auto; border-radius: 1px; }
        .error-icon { width: 6rem; height: 6rem; border-radius: 50%; background: color-mix(in oklch, var(--accent) 12%, transparent); border: 2px solid color-mix(in oklch, var(--accent) 30%, transparent); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.5rem; height: 2.5rem; padding: 0 1.25rem; border-radius: var(--radius); font-size: 0.875rem; font-weight: 600; text-decoration: none; cursor: pointer; border: 1px solid var(--border); transition: all 150ms; }
        .btn-primary { background: var(--primary); color: oklch(0.18 0.045 264.695); border-color: var(--primary); box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 35%, transparent); }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-ghost { background: var(--card); color: var(--foreground); }
        .btn-ghost:hover { background: color-mix(in oklch, var(--foreground) 8%, transparent); }
        .btn-group { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        .brand { position: fixed; top: 1.5rem; left: 50%; transform: translateX(-50%); font-size: 1rem; font-weight: 800; letter-spacing: -0.04em; color: var(--foreground); text-decoration: none; display: flex; align-items: center; gap: 0.4rem; opacity: 0.8; }
        .brand:hover { opacity: 1; }
    </style>
</head>
<body>
    <a href="{{ url('/') }}" class="brand">
        <i data-lucide="map-pin" style="width:1.1rem;height:1.1rem;color:var(--primary);"></i>
        Urbex
    </a>

    <div class="error-icon">
        <i data-lucide="server-crash" style="width:2.5rem;height:2.5rem;color:var(--accent);"></i>
    </div>

    <p class="error-code">500</p>
    <h1 class="error-title">Error del servidor</h1>
    <div class="divider"></div>
    <p class="error-msg">
        Algo ha fallado por nuestra parte. Ya estamos al tanto del problema. Inténtalo de nuevo en unos minutos.
    </p>

    <div class="btn-group">
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i data-lucide="home" style="width:1rem;height:1rem;"></i>
            Ir al inicio
        </a>
        <a href="javascript:location.reload()" class="btn btn-ghost">
            <i data-lucide="refresh-cw" style="width:1rem;height:1rem;"></i>
            Reintentar
        </a>
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>
