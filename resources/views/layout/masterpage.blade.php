<!DOCTYPE html>
<html lang="es" class="dark"
    x-data="{
        darkMode: localStorage.getItem('darkMode') !== 'false',
        sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        sidebarOpen: false,
        init() {
            this.$watch('darkMode', v => localStorage.setItem('darkMode', v));
            this.$watch('sidebarCollapsed', v => localStorage.setItem('sidebarCollapsed', v));
        }
    }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Urbexium') — Explora lo Inexplorado</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        :root {
            --background: oklch(0.985 0 0);
            --foreground: oklch(0.145 0 0);
            --card: oklch(1 0 0);
            --card-foreground: oklch(0.145 0 0);
            --primary: oklch(0.65 0.2 145);
            --primary-foreground: oklch(0.985 0 0);
            --secondary: oklch(0.97 0 0);
            --secondary-foreground: oklch(0.205 0 0);
            --muted: oklch(0.97 0 0);
            --muted-foreground: oklch(0.556 0 0);
            --accent: oklch(0.75 0.15 85);
            --accent-foreground: oklch(0.205 0 0);
            --destructive: oklch(0.577 0.245 27.325);
            --border: oklch(0.922 0 0);
            --input: oklch(0.922 0 0);
            --ring: oklch(0.65 0.2 145);
            --radius: 0.75rem;
            --sidebar-w: 16rem;
            --sidebar-collapsed-w: 4.5rem;
            --sidebar: oklch(0.985 0 0);
            --sidebar-border: oklch(0.922 0 0);
        }

        .dark {
            --background: oklch(0.12 0.01 260);
            --foreground: oklch(0.95 0 0);
            --card: oklch(0.16 0.01 260);
            --card-foreground: oklch(0.95 0 0);
            --primary: oklch(0.7 0.18 145);
            --primary-foreground: oklch(0.12 0 0);
            --secondary: oklch(0.22 0.01 260);
            --secondary-foreground: oklch(0.95 0 0);
            --muted: oklch(0.22 0.01 260);
            --muted-foreground: oklch(0.65 0 0);
            --accent: oklch(0.78 0.14 85);
            --accent-foreground: oklch(0.12 0 0);
            --destructive: oklch(0.55 0.22 27);
            --border: oklch(0.26 0.01 260);
            --input: oklch(0.22 0.01 260);
            --ring: oklch(0.7 0.18 145);
            --sidebar: oklch(0.1 0.01 260);
            --sidebar-border: oklch(0.22 0.01 260);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            border-color: var(--border);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            margin: 0;
            overflow-x: hidden;
            overflow-wrap: break-word;
            word-break: break-word;
        }

        [x-cloak] {
            display: none !important;
        }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            height: 4rem;
            border-bottom: 1px solid var(--border);
            background-color: var(--background);
            /* CRÍTICO: sin overflow:hidden para que los dropdowns sean visibles */
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1rem;
            overflow: visible;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed;
            left: 0;
            top: 4rem;
            z-index: 200;  /* Por encima de Leaflet (z-index 400 tiles, pero el sidebar del mapa es internal) */
            height: calc(100vh - 4rem);
            border-right: 1px solid var(--sidebar-border);
            background-color: var(--sidebar);
            overflow: hidden;
            transition: width 280ms cubic-bezier(0.4, 0, 0.2, 1);
            width: var(--sidebar-w);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-w);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 199;
            background-color: rgba(0, 0, 0, 0.55);
        }

        /* ─── MAIN CONTENT ─── */
        .main-content {
            margin-top: 4rem;
            min-height: calc(100vh - 4rem);
            padding: 1.5rem;
            transition: margin-left 280ms cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ─── NAV ITEMS ─── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            height: 2.5rem;
            border-radius: var(--radius);
            padding: 0 0.75rem;
            color: var(--muted-foreground);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: background-color 150ms, color 150ms, box-shadow 150ms;
            white-space: nowrap;
            overflow: hidden;
        }

        .nav-item:hover {
            background-color: var(--secondary);
            color: var(--foreground);
        }

        .nav-item.active {
            background-color: var(--primary);
            color: var(--primary-foreground);
            box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 35%, transparent);
        }

        .nav-item-icon,
        .nav-item svg {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
            display: block;
        }

        .nav-item-label {
            transition: opacity 200ms, width 200ms;
            overflow: hidden;
        }

        .sidebar.collapsed #main-nav {
            padding: 0.75rem 0 !important;
            align-items: center !important;
        }

        .sidebar.collapsed .nav-item {
            width: 2.75rem !important;
            min-width: 2.75rem !important;
            height: 2.75rem !important;
            padding: 0 !important;
            margin: 0 auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 0 !important;
            border-radius: 0.85rem;
        }

        .sidebar.collapsed .nav-item-label {
            display: none !important;
            width: 0 !important;
            opacity: 0 !important;
        }

        .sidebar.collapsed .nav-item svg {
            margin: 0 !important;
            position: static !important;
            transform: none !important;
        }

        /* ─── CARDS ─── */
        .card {
            background-color: var(--card);
            color: var(--card-foreground);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-header {
            padding: 1.25rem 1.5rem 0.75rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.4;
        }

        .card-content {
            padding: 0.75rem 1.5rem 1.25rem;
        }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.125rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-primary {
            background-color: color-mix(in oklch, var(--primary) 15%, transparent);
            color: var(--primary);
        }

        .badge-accent {
            background-color: color-mix(in oklch, var(--accent) 15%, transparent);
            color: var(--accent);
        }

        .badge-muted {
            background-color: var(--muted);
            color: var(--muted-foreground);
        }

        .badge-destructive {
            background-color: color-mix(in oklch, var(--destructive) 15%, transparent);
            color: var(--destructive);
        }

        /* ─── BUTTONS ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border: none;
            transition: all 200ms ease;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-primary {
            background-color: var(--primary);
            color: var(--primary-foreground);
        }

        .btn-primary:hover {
            opacity: 0.9;
            box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 40%, transparent);
        }

        .btn-ghost {
            background-color: transparent;
            color: var(--foreground);
        }

        .btn-ghost:hover {
            background-color: var(--secondary);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--secondary-foreground);
        }

        .btn-secondary:hover {
            opacity: 0.85;
        }

        .btn-destructive {
            background-color: var(--destructive);
            color: #fff;
        }

        .btn-destructive:hover {
            opacity: 0.9;
        }

        .btn-icon {
            padding: 0.5rem;
            width: 2.25rem;
            height: 2.25rem;
            justify-content: center;
        }

        /* ─── INPUTS ─── */
        .input {
            width: 100%;
            height: 2.5rem;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            background-color: var(--secondary);
            color: var(--foreground);
            padding: 0 0.75rem;
            font-size: 0.875rem;
            font-family: inherit;
            transition: border-color 200ms;
            outline: none;
        }

        .input:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 2px color-mix(in oklch, var(--ring) 20%, transparent);
        }

        .input::placeholder {
            color: var(--muted-foreground);
        }

        textarea.input {
            height: auto;
            padding: 0.625rem 0.75rem;
            resize: vertical;
        }

        /* ─── AVATAR ─── */
        .avatar {
            border-radius: 9999px;
            overflow: hidden;
            background-color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        /* ─── LOGO ─── */
        .logo-img {
            height: 3.5rem;
            width: auto;
            display: block;
            object-fit: contain;
        }

        .logo-light {
            display: block;
            filter: invert(1);
            mix-blend-mode: multiply;
        }

        .logo-dark {
            display: none;
            mix-blend-mode: screen;
        }

        .dark .logo-light {
            display: none;
        }

        .dark .logo-dark {
            display: block;
        }

        .admin-nav-sep {
            height: 1px;
            background: var(--sidebar-border);
            margin: 0.4rem 0;
            flex-shrink: 0;
            width: 100%;
        }

        .sidebar.collapsed .admin-nav-sep {
            width: 2.75rem;
            margin: 0.4rem auto;
        }

        /* ─── DROPDOWN ─── */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: fixed; /* fixed en lugar de absolute para salir de cualquier contenedor */
            min-width: 14rem;
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            z-index: 9999;
            overflow: hidden;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            color: var(--foreground);
            cursor: pointer;
            transition: background-color 150ms;
            text-decoration: none;
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background-color: var(--secondary);
        }

        .dropdown-item.danger {
            color: var(--destructive);
        }

        .dropdown-separator {
            border-top: 1px solid var(--border);
            margin: 0.25rem 0;
        }

        /* ─── NOTIF ─── */
        .notif-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 1rem;
            height: 1rem;
            border-radius: 9999px;
            background-color: var(--primary);
            color: var(--primary-foreground);
            font-size: 0.625rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--muted-foreground);
        }

        /* ─── MOBILE ─── */
        @media (max-width: 1023px) {
            .sidebar {
                width: var(--sidebar-w) !important;
                transform: translateX(-100%);
                transition: transform 280ms cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .collapse-btn {
                display: none !important;
            }
        }

        /* ─── NAVBAR RESPONSIVE ─── */
        /* Buscador central: ocultar en móvil pequeño, mostrar como fila separada */
        .navbar-search-center {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 18rem;
        }

        .navbar-mobile-search {
            display: none;
        }

        .logo-wordmark {
            display: none !important; /* oculto en móvil por defecto, visible en desktop */
        }

        /* En desktop: mostrar wordmark, ocultar botón izquierdo de darkmode */
        @media (min-width: 768px) {
            .logo-wordmark {
                display: inline !important;
            }
            .darkmode-mobile-left {
                display: none !important;
            }
            .navbar-logo-link {
                position: static !important;
                transform: none !important;
            }
        }

        /* En móvil: ocultar botón derecho de darkmode, centrar logo */
        @media (max-width: 767px) {
            .darkmode-desktop-right {
                display: none !important;
            }
            .navbar-logo-link {
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
            }
            #username-text,
            #user-chevron {
                display: none !important;
            }
            .navbar-user-btn {
                padding: 0.25rem !important;
            }
            .navbar-search-center {
                display: none !important;
            }
        }

        /* Búsqueda en fila separada en móvil pequeño */
        @media (max-width: 639px) {
            .navbar {
                flex-wrap: wrap;
                height: auto;
            }
            .navbar-inner {
                flex-wrap: wrap;
                height: auto;
                padding: 0.625rem 1rem;
                gap: 0.25rem;
            }
            .navbar-mobile-search {
                display: flex;
                width: 100%;
                padding: 0 0 0.5rem;
            }
            .navbar-mobile-search input {
                flex: 1;
            }
            .main-content {
                margin-top: 6.5rem !important;
            }
            .sidebar {
                top: 6.5rem !important;
                height: calc(100vh - 6.5rem) !important;
            }
        }

        @media (min-width: 640px) and (max-width: 900px) {
            .navbar-search-center {
                width: 12rem;
            }
            #username-text {
                display: none !important;
            }
        }

        /* Botones auth en móvil */
        @media (max-width: 480px) {
            .navbar-auth-register span {
                display: none;
            }
            .navbar-auth-login {
                display: none;
            }
        }

        /* Centrar contenido de páginas */
        .page-center {
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* ─── GLOBAL MOBILE UTILS ─── */
        @media (max-width: 639px) {
            .main-content {
                padding: 1rem 0.875rem;
            }
            /* Tablas overflow en móvil */
            .card-content table,
            table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Imágenes galería responsive */
        @media (max-width: 480px) {
            .img-gallery.two {
                grid-template-columns: 1fr !important;
            }
        }

        /* Paginación compacta */
        @media (max-width: 480px) {
            .pagination-wrap {
                gap: 0.25rem;
            }
            .pagination-wrap span,
            .pagination-wrap a {
                min-width: 2rem;
                height: 2rem;
                font-size: 0.8rem;
                padding: 0 0.375rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>

{{-- ==================== NAVBAR ==================== --}}
<header class="navbar">
    <div class="navbar-inner">

        {{-- Left: hamburger móvil + theme toggle (móvil) + logo --}}
        <div style="display:flex; align-items:center; gap:0.25rem;">
            <button class="btn btn-ghost btn-icon"
                id="mobile-menu-btn"
                style="display:none;"
                onclick="toggleMobileSidebar()"
                title="Abrir menú">
                <i data-lucide="menu" style="width:1.25rem;height:1.25rem;"></i>
            </button>

            {{-- Dark mode toggle: en móvil va aquí (izquierda), en desktop se oculta y aparece en la derecha --}}
            <button class="btn btn-ghost btn-icon darkmode-mobile-left"
                onclick="toggleDarkMode()"
                title="Cambiar tema">
                <i data-lucide="sun" id="icon-sun-left" style="width:1.25rem;height:1.25rem; display:none;"></i>
                <i data-lucide="moon" id="icon-moon-left" style="width:1.25rem;height:1.25rem;"></i>
            </button>

            <a href="{{ route('dashboard') }}" class="navbar-logo-link" style="display:flex; align-items:center; gap:0.5rem; text-decoration:none;">
                {{-- Logo claro para modo claro --}}
                <img src="{{ asset('images/logo-dark.jpg') }}"
                     class="logo-img logo-light"
                     alt="Urbexium"
                     id="logo-img-light">

                {{-- Logo blanco para modo oscuro --}}
                <img src="{{ asset('images/logo-white.jpg') }}"
                     class="logo-img logo-dark"
                     alt="Urbexium"
                     id="logo-img-dark">

                <span style="font-size:1.1rem; font-weight:800; letter-spacing:-0.04em; color:var(--foreground);" id="logo-text" class="logo-wordmark">Urbexium</span>
            </a>
        </div>

        {{-- Center: search (desktop) --}}
        <div class="navbar-search-center">
            <div style="position:relative;">
                <i data-lucide="search"
                    style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--muted-foreground);"></i>
                <input type="search" placeholder="Buscar spots..."
                    class="input"
                    id="global-search"
                    style="padding-left:2.5rem; height:2.25rem; font-size:0.8125rem;"
                    onkeydown="if(event.key==='Enter' && this.value) window.location='{{ route('spots.index') }}?search='+encodeURIComponent(this.value)" />
            </div>
        </div>

        {{-- Right: acciones + usuario --}}
        <div style="display:flex; align-items:center; gap:0.375rem;">

            {{-- Dark mode toggle: solo visible en desktop --}}
            <button class="btn btn-ghost btn-icon darkmode-desktop-right"
                onclick="toggleDarkMode()"
                id="darkmode-btn"
                title="Cambiar tema">
                <i data-lucide="sun" id="icon-sun" style="width:1.25rem;height:1.25rem; display:none;"></i>
                <i data-lucide="moon" id="icon-moon" style="width:1.25rem;height:1.25rem;"></i>
            </button>

            @guest
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <a href="{{ route('login') }}" class="btn btn-ghost navbar-auth-login" style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary navbar-auth-register" style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                    <i data-lucide="user-plus" style="width:0.9rem;height:0.9rem;"></i>
                    <span>Registrarse</span>
                </a>
            </div>
            @endguest

            @auth
            {{-- Notificaciones --}}
            @php
                $notifs       = Auth::user()->notificaciones()->latest()->take(10)->get();
                $notifNoLeidas = $notifs->where('leida', false)->count();
            @endphp
            <div style="position:relative;" id="notif-wrapper">
                <button class="btn btn-ghost btn-icon" onclick="toggleDropdown('notif-menu', this)" style="position:relative;">
                    <i data-lucide="bell" style="width:1.25rem;height:1.25rem;"></i>
                    @if($notifNoLeidas > 0)
                    <span style="position:absolute; top:0.25rem; right:0.25rem; width:0.5rem; height:0.5rem;
                        background:var(--destructive); border-radius:50%; display:block;"></span>
                    @endif
                </button>
                <div id="notif-menu" class="dropdown-menu" style="display:none; min-width:min(22rem, calc(100vw - 2rem)); max-height:28rem; overflow-y:auto;">
                    <div style="padding:0.875rem 1rem; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                        <p style="font-weight:600; font-size:0.9375rem; margin:0;">
                            Notificaciones
                            @if($notifNoLeidas > 0)
                            <span style="margin-left:0.5rem; font-size:0.75rem; font-weight:500;
                                background:var(--destructive); color:#fff; padding:0.1rem 0.45rem;
                                border-radius:9999px;">{{ $notifNoLeidas }}</span>
                            @endif
                        </p>
                        @if($notifs->isNotEmpty())
                        <form method="POST" action="{{ route('notificaciones.markAllRead') }}">
                            @csrf @method('PATCH')
                            <button type="submit" style="font-size:0.75rem; background:none; border:none;
                                color:var(--muted-foreground); cursor:pointer; padding:0;">
                                Marcar todas
                            </button>
                        </form>
                        @endif
                    </div>

                    @forelse($notifs as $notif)
                    @php
                        $tipoColor = match($notif->tipo) {
                            'info'             => 'var(--primary)',
                            'aviso'            => '#f59e0b',
                            'alerta'           => 'var(--destructive)',
                            'spot_verificado'  => '#22c55e',
                            'spot_rechazado'   => 'var(--destructive)',
                            'ban'              => 'var(--destructive)',
                            default            => 'var(--muted-foreground)',
                        };
                        $tipoIcon = match($notif->tipo) {
                            'info'             => 'info',
                            'aviso'            => 'alert-triangle',
                            'alerta'           => 'alert-circle',
                            'spot_verificado'  => 'check-circle',
                            'spot_rechazado'   => 'x-circle',
                            'ban'              => 'shield-off',
                            default            => 'bell',
                        };
                    @endphp
                    <div style="display:flex; gap:0.75rem; padding:0.875rem 1rem;
                        border-bottom:1px solid var(--border);
                        background:{{ $notif->leida ? 'transparent' : 'color-mix(in oklch, var(--primary) 5%, transparent)' }};">
                        <div style="flex-shrink:0; margin-top:0.125rem;">
                            <i data-lucide="{{ $tipoIcon }}" style="width:1rem;height:1rem; color:{{ $tipoColor }};"></i>
                        </div>
                        <div style="flex:1; min-width:0;">
                            <p style="font-size:0.8125rem; font-weight:600; margin:0 0 0.2rem;">{{ $notif->titulo }}</p>
                            <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0 0 0.35rem; line-height:1.4;">{{ $notif->mensaje }}</p>
                            <div style="display:flex; align-items:center; justify-content:space-between;">
                                <span style="font-size:0.7rem; color:var(--muted-foreground);">{{ $notif->created_at->diffForHumans() }}</span>
                                @if(!$notif->leida)
                                <form method="POST" action="{{ route('notificaciones.markRead', $notif) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="font-size:0.7rem; background:none; border:none;
                                        color:var(--primary); cursor:pointer; padding:0;">
                                        Marcar leída
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="padding:2rem; text-align:center; color:var(--muted-foreground); font-size:0.875rem;">
                        <i data-lucide="bell-off" style="width:1.5rem;height:1.5rem; margin-bottom:0.5rem; opacity:0.4; display:block; margin-inline:auto;"></i>
                        Sin notificaciones nuevas
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Usuario --}}
            <div style="position:relative;" id="user-wrapper">
                <button class="btn btn-ghost navbar-user-btn" style="padding:0.25rem 0.75rem 0.25rem 0.375rem; gap:0.5rem;"
                    onclick="toggleDropdown('user-menu', this)">
                    <div class="avatar" style="width:1.875rem; height:1.875rem; font-size:0.8125rem; color:var(--primary-foreground); background:var(--primary);">
                        @if(Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->nombre }}" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                            {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <span style="font-size:0.875rem; font-weight:500;" id="username-text">
                        {{ Auth::user()->nombre ?? '' }}
                    </span>
                    <i data-lucide="chevron-down" id="user-chevron" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground);"></i>
                </button>
                <div id="user-menu" class="dropdown-menu" style="display:none;">
                    <div style="padding:0.875rem 1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:0.75rem;">
                        <div class="avatar" style="width:2.5rem; height:2.5rem; background:var(--primary); color:var(--primary-foreground);">
                            {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <p style="font-weight:600; font-size:0.9375rem; margin:0;">{{ Auth::user()->nombre ?? '' }}</p>
                            <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0;">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                    <a href="{{ route('perfil.index') }}" class="dropdown-item">
                        <i data-lucide="user" style="width:1rem;height:1rem;"></i> Perfil
                    </a>
                    <div class="dropdown-separator"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item danger">
                            <i data-lucide="log-out" style="width:1rem;height:1rem;"></i> Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
            @endauth

        </div>
    </div>
    {{-- Mobile search row (visible solo en <640px) --}}
    <div class="navbar-mobile-search">
        <div style="position:relative; flex:1;">
            <i data-lucide="search"
                style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--muted-foreground);"></i>
            <input type="search" placeholder="Buscar spots..."
                class="input"
                style="padding-left:2.5rem; height:2.25rem; font-size:0.8125rem; width:100%;"
                onkeydown="if(event.key==='Enter' && this.value) window.location='{{ route('spots.index') }}?search='+encodeURIComponent(this.value)" />
        </div>
    </div>
</header>

{{-- ==================== OVERLAY MÓVIL ==================== --}}
<div id="sidebar-overlay" class="sidebar-overlay" style="display:none;" onclick="closeMobileSidebar()"></div>

{{-- ==================== SIDEBAR ==================== --}}
<aside class="sidebar" id="main-sidebar">
    <div style="display:flex; flex-direction:column; height:100%; overflow:hidden;">

        <nav id="main-nav" style="flex:1; padding:0.75rem; display:flex; flex-direction:column; align-items:stretch; gap:0.25rem; overflow-y:auto; overflow-x:hidden;">
            @php
                $navItems = [


                    ['route' => 'dashboard',        'icon' => 'home',         'label' => 'Inicio'],
                    ['route' => 'spots.index',      'icon' => 'map-pin',      'label' => 'Spots'],
                    ['route' => 'map',              'icon' => 'map',          'label' => 'Mapa'],
                    ['route' => 'comunidades.index','icon' => 'users',        'label' => 'Comunidades'],
                    ['route' => 'tienda.index',     'icon' => 'shopping-bag', 'label' => 'Tienda'],
                     ['route' => 'contacto.index', 'icon' => 'mail', 'label' => 'Contacto'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']) @endphp
                <a href="{{ route($item['route']) }}"
                    class="nav-item {{ $isActive ? 'active' : '' }}"
                    title="{{ $item['label'] }}">
                    <i data-lucide="{{ $item['icon'] }}" class="nav-item-icon"></i>
                    <span class="nav-item-label sidebar-label">{{ $item['label'] }}</span>
                </a>
            @endforeach

            @if(Auth::check() && Auth::user()->esAdmin())
                @php $isActive = request()->routeIs('admin.*'); @endphp
                <div class="admin-nav-sep"></div>
                <a href="{{ route('admin.index') }}"
                    class="nav-item {{ $isActive ? 'active' : '' }}"
                    title="Admin Panel">
                    <i data-lucide="shield" class="nav-item-icon"></i>
                    <span class="nav-item-label sidebar-label">Admin Panel</span>
                </a>
            @endif
        </nav>

        {{-- Enlaces legales (visibles solo cuando el sidebar está expandido) --}}
        <div class="sidebar-legal" style="padding:0.75rem; border-top:1px solid var(--sidebar-border);">
            <div class="sidebar-legal-links" style="display:flex; flex-direction:column; gap:0.125rem; margin-bottom:0.5rem;">
                <a href="{{ route('legal.privacidad') }}"
                    class="nav-item {{ request()->routeIs('legal.privacidad') ? 'active' : '' }}"
                    title="Privacidad">
                    <i data-lucide="shield" class="nav-item-icon"></i>
                    <span class="nav-item-label sidebar-label" style="font-size:0.8125rem;">Privacidad</span>
                </a>
                <a href="{{ route('legal.cookies') }}"
                    class="nav-item {{ request()->routeIs('legal.cookies') ? 'active' : '' }}"
                    title="Cookies">
                    <i data-lucide="cookie" class="nav-item-icon"></i>
                    <span class="nav-item-label sidebar-label" style="font-size:0.8125rem;">Cookies</span>
                </a>
                <a href="{{ route('legal.aviso') }}"
                    class="nav-item {{ request()->routeIs('legal.aviso') ? 'active' : '' }}"
                    title="Aviso Legal">
                    <i data-lucide="scale" class="nav-item-icon"></i>
                    <span class="nav-item-label sidebar-label" style="font-size:0.8125rem;">Aviso Legal</span>
                </a>
            </div>
        </div>

        {{-- Collapse toggle solo desktop --}}
        <div style="padding:0.5rem 0.75rem 0.75rem; border-top:1px solid var(--sidebar-border);" class="collapse-btn">
            <button class="btn btn-ghost btn-icon" onclick="toggleSidebar()" style="width:100%; justify-content:center;" title="Colapsar">
                <i data-lucide="chevron-left" id="collapse-icon" style="width:1.25rem;height:1.25rem;"></i>
            </button>
        </div>

    </div>
</aside>

{{-- ==================== MAIN CONTENT ==================== --}}
<main class="main-content" id="main-content">
    @if(session('success'))
    <div style="margin-bottom:1rem; padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--primary) 12%, transparent); border:1px solid color-mix(in oklch, var(--primary) 30%, transparent); border-radius:var(--radius); color:var(--primary); font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
        <i data-lucide="check-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="margin-bottom:1rem; padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--destructive) 12%, transparent); border:1px solid color-mix(in oklch, var(--destructive) 30%, transparent); border-radius:var(--radius); color:var(--destructive); font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
        <i data-lucide="x-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>
        {{ session('error') }}
    </div>
    @endif

    @yield('content')
</main>

@include('layout.footer')

{{-- ==================== SCRIPTS ==================== --}}
<script>
    lucide.createIcons();

    const sidebar = document.getElementById('main-sidebar');
    const mainContent = document.getElementById('main-content');
    const collapseIcon = document.getElementById('collapse-icon');
    const labels = document.querySelectorAll('.sidebar-label');

    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    let isMobile = window.innerWidth < 1024;

    function applyDesktopState() {
        sidebar.classList.remove('mobile-open');
        document.getElementById('mobile-menu-btn').style.display = 'none';
        document.getElementById('sidebar-overlay').style.display = 'none';

        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.style.marginLeft = 'var(--sidebar-collapsed-w)';

            if (collapseIcon) {
                collapseIcon.setAttribute('data-lucide', 'chevron-right');
            }

            labels.forEach(label => {
                label.style.opacity = '0';
            });
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.style.marginLeft = 'var(--sidebar-w)';

            if (collapseIcon) {
                collapseIcon.setAttribute('data-lucide', 'chevron-left');
            }

            labels.forEach(label => {
                label.style.opacity = '1';
            });
        }

        lucide.createIcons();
    }

    function applyMobileState() {
        mainContent.style.marginLeft = '0';
        sidebar.classList.remove('collapsed');
        labels.forEach(label => {
            label.style.opacity = '1';
        });
        document.getElementById('mobile-menu-btn').style.display = 'flex';
        lucide.createIcons();
    }

    function toggleSidebar() {
        isCollapsed = !isCollapsed;
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        applyDesktopState();
    }

    function toggleMobileSidebar() {
        const overlay = document.getElementById('sidebar-overlay');

        if (sidebar.classList.contains('mobile-open')) {
            closeMobileSidebar();
        } else {
            sidebar.classList.add('mobile-open');
            overlay.style.display = 'block';
        }
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-open');
        document.getElementById('sidebar-overlay').style.display = 'none';
    }

    if (isMobile) {
        applyMobileState();
    } else {
        applyDesktopState();
    }

    window.addEventListener('resize', () => {
        const nowMobile = window.innerWidth < 1024;

        if (nowMobile !== isMobile) {
            isMobile = nowMobile;
            closeMobileSidebar();

            if (isMobile) {
                applyMobileState();
            } else {
                applyDesktopState();
            }
        }
    });

    let dark = localStorage.getItem('darkMode') !== 'false';

    function applyDarkMode() {
        document.documentElement.classList.toggle('dark', dark);
        // Botón derecho (desktop)
        const sunR = document.getElementById('icon-sun');
        const moonR = document.getElementById('icon-moon');
        if (sunR) sunR.style.display = dark ? 'block' : 'none';
        if (moonR) moonR.style.display = dark ? 'none' : 'block';
        // Botón izquierdo (móvil)
        const sunL = document.getElementById('icon-sun-left');
        const moonL = document.getElementById('icon-moon-left');
        if (sunL) sunL.style.display = dark ? 'block' : 'none';
        if (moonL) moonL.style.display = dark ? 'none' : 'block';
    }

    function toggleDarkMode() {
        dark = !dark;
        localStorage.setItem('darkMode', dark);
        applyDarkMode();
    }

    applyDarkMode();

    function toggleDropdown(id, triggerEl) {
        const menu = document.getElementById(id);
        const isOpen = menu.style.display === 'block';

        document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
            dropdown.style.display = 'none';
        });

        if (!isOpen) {
            menu.style.display = 'block';

            // Posicionar el menú fixed bajo el botón disparador
            if (triggerEl) {
                const rect = triggerEl.getBoundingClientRect();
                const menuWidth = menu.offsetWidth || 224; // 14rem fallback
                let left = rect.right - menuWidth;
                if (left < 8) left = 8;
                menu.style.top = (rect.bottom + 8) + 'px';
                menu.style.left = left + 'px';
                menu.style.right = 'auto';
            }
        }
    }

    document.addEventListener('click', function(e) {
        const wrappers = ['notif-wrapper', 'user-wrapper'];
        const clickedInside = wrappers.some(id => {
            const el = document.getElementById(id);
            return el && el.contains(e.target);
        });

        if (!clickedInside) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
</script>

@stack('scripts')
</body>
</html>
