<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Crear Cuenta — Urbexium</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        :root {
            --background: oklch(0.12 0.01 260);
            --foreground: oklch(0.95 0 0);
            --card: oklch(0.16 0.01 260);
            --primary: oklch(0.7 0.18 145);
            --primary-foreground: oklch(0.12 0 0);
            --muted: oklch(0.22 0.01 260);
            --muted-foreground: oklch(0.65 0 0);
            --border: oklch(0.26 0.01 260);
            --input: oklch(0.22 0.01 260);
            --destructive: oklch(0.55 0.22 27);
            --radius: 0.75rem;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background-image:
                radial-gradient(ellipse at 80% 50%, color-mix(in oklch, var(--primary) 8%, transparent) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, color-mix(in oklch, var(--primary) 5%, transparent) 0%, transparent 50%);
        }
        .auth-container { width: 100%; max-width: 440px; }
        .auth-header { text-align: center; margin-bottom: 2rem; }
        .logo-box {
            width: 3rem; height: 3rem;
            border-radius: var(--radius);
            background-color: var(--primary);
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.375rem; color: var(--primary-foreground);
            margin-bottom: 1rem;
        }
        .auth-header h1 { font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em; margin-bottom: 0.375rem; }
        .auth-header p { font-size: 0.9rem; color: var(--muted-foreground); }
        .auth-card {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: calc(var(--radius) + 4px);
            padding: 2rem;
        }
        .form-group { margin-bottom: 1.125rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; }
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute; left: 0.875rem; top: 50%; transform: translateY(-50%);
            width: 1rem; height: 1rem; color: var(--muted-foreground); pointer-events: none;
        }
        .form-input {
            width: 100%; height: 2.75rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background-color: var(--input);
            color: var(--foreground);
            padding: 0 0.875rem 0 2.75rem;
            font-size: 0.9rem; font-family: inherit;
            outline: none; transition: border-color 200ms, box-shadow 200ms;
        }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in oklch, var(--primary) 20%, transparent);
        }
        .form-input::placeholder { color: var(--muted-foreground); }
        .form-input.is-invalid { border-color: var(--destructive); }
        .form-error {
            margin-top: 0.375rem; font-size: 0.8125rem; color: var(--destructive);
            display: flex; align-items: center; gap: 0.25rem;
        }

        /* Two columns for password fields */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.875rem;
        }

        /* Password strength indicator */
        .strength-bar {
            display: flex; gap: 0.25rem; margin-top: 0.5rem;
        }
        .strength-segment {
            flex: 1; height: 3px; border-radius: 9999px;
            background-color: var(--border);
            transition: background-color 300ms;
        }

        .btn-submit {
            width: 100%; height: 2.75rem;
            border-radius: var(--radius);
            background-color: var(--primary);
            color: var(--primary-foreground);
            font-size: 0.9375rem; font-weight: 600; font-family: inherit;
            border: none; cursor: pointer;
            transition: opacity 200ms, box-shadow 200ms;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            margin-top: 1.5rem;
        }
        .btn-submit:hover {
            opacity: 0.92;
            box-shadow: 0 4px 20px color-mix(in oklch, var(--primary) 40%, transparent);
        }
        .divider {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.5rem 0; color: var(--muted-foreground); font-size: 0.8125rem;
        }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background-color: var(--border); }
        .login-link { text-align: center; font-size: 0.875rem; color: var(--muted-foreground); }
        .login-link a { color: var(--primary); text-decoration: none; font-weight: 500; }
        .login-link a:hover { text-decoration: underline; }

        /* Terms note */
        .terms-note {
            margin-top: 1rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
            text-align: center;
            line-height: 1.5;
        }
        .terms-note a { color: var(--primary); text-decoration: none; }

        @media (max-width: 480px) {
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="auth-container">

        {{-- Header --}}
        <div class="auth-header">
            <a href="{{ url('/') }}">
                <div class="logo-box">U</div>
            </a>
            <h1>Únete a Urbexium</h1>
            <p>Crea tu cuenta y empieza a explorar</p>
        </div>

        {{-- Card --}}
        <div class="auth-card">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Nombre --}}
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre de usuario</label>
                    <div class="input-wrapper">
                        <i data-lucide="user" class="input-icon"></i>
                        <input
                            id="nombre"
                            type="text"
                            name="nombre"
                            class="form-input {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
                            value="{{ old('nombre') }}"
                            placeholder="Explorer123"
                            required
                            autofocus
                            autocomplete="nombre"
                        />
                    </div>
                    @error('name')
                        <p class="form-error">
                            <i data-lucide="circle-alert" style="width:0.875rem;height:0.875rem;"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <div class="input-wrapper">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="tu@email.com"
                            required
                            autocomplete="email"
                        />
                    </div>
                    @error('email')
                        <p class="form-error">
                            <i data-lucide="circle-alert" style="width:0.875rem;height:0.875rem;"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Passwords (2 columnas) --}}
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock" class="input-icon"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Min. 8 caracteres"
                                required
                                autocomplete="new-password"
                                oninput="checkStrength(this.value)"
                            />
                        </div>
                        {{-- Barra de fuerza --}}
                        <div class="strength-bar" id="strength-bar">
                            <div class="strength-segment" id="s1"></div>
                            <div class="strength-segment" id="s2"></div>
                            <div class="strength-segment" id="s3"></div>
                            <div class="strength-segment" id="s4"></div>
                        </div>
                        @error('password')
                            <p class="form-error">
                                <i data-lucide="circle-alert" style="width:0.875rem;height:0.875rem;"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label for="password-confirm" class="form-label">Confirmar</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock-keyhole" class="input-icon"></i>
                            <input
                                id="password-confirm"
                                type="password"
                                name="password_confirmation"
                                class="form-input"
                                placeholder="Repite la contraseña"
                                required
                                autocomplete="new-password"
                            />
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    <i data-lucide="user-plus" style="width:1.125rem;height:1.125rem;"></i>
                    Crear Cuenta
                </button>

                <p class="terms-note">
                    Al registrarte aceptas nuestros
                    <a href="#">Términos de uso</a> y la
                    <a href="#">Política de privacidad</a>
                </p>
            </form>

            <div class="divider">o</div>

            <p class="login-link">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}">Inicia sesión</a>
            </p>
        </div>

    </div>

    <script>
        lucide.createIcons();

        function checkStrength(val) {
            const colors = ['var(--destructive)', 'oklch(0.75 0.15 85)', 'var(--primary)', 'var(--primary)'];
            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            for (let i = 1; i <= 4; i++) {
                const seg = document.getElementById('s' + i);
                seg.style.backgroundColor = i <= score
                    ? colors[score - 1]
                    : 'var(--border)';
            }
        }
    </script>
</body>
</html>
