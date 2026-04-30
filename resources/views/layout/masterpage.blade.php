<!DOCTYPE html>
<html lang="es" class="dark" x-data="{ darkMode: true, sidebarCollapsed: false, sidebarOpen: false }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Urbexium') — Explora lo Inexplorado</title>

    {{-- Fuente: Geist (similar al original) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    {{-- Descomenta esto cuando uses npm run dev. Con UniServerZ no hace falta. --}}

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

        * {
            border-color: var(--border);
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            margin: 0;
        }

        /* --- NAVBAR --- */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 50;
            height: 4rem;
            border-bottom: 1px solid var(--border);
            background-color: color-mix(in oklch, var(--background) 80%, transparent);
            backdrop-filter: blur(20px);
        }

        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1rem;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            position: fixed;
            left: 0;
            top: 4rem;
            z-index: 40;
            height: calc(100vh - 4rem);
            border-right: 1px solid var(--border);
            background-color: var(--sidebar);
            transition: width 300ms cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar-expanded { width: 16rem; }
        .sidebar-collapsed { width: 4rem; }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 30;
            background-color: rgba(0,0,0,0.5);
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-top: 4rem;
            transition: margin-left 300ms cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - 4rem);
            padding: 1.5rem;
        }

        /* --- NAV ITEMS --- */
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
            transition: all 200ms ease;
            white-space: nowrap;
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

        .nav-item-icon {
            flex-shrink: 0;
            width: 1.25rem;
            height: 1.25rem;
        }

        /* --- CARDS --- */
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

        /* --- BADGES --- */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.125rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 500;
            line-height: 1.25;
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

        /* --- BUTTONS --- */
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

        .btn-icon {
            padding: 0.5rem;
            width: 2.25rem;
            height: 2.25rem;
            justify-content: center;
        }

        /* --- INPUTS --- */
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

        /* --- AVATAR --- */
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

        /* --- LOGO --- */
        .logo-box {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: var(--radius);
            background-color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--primary-foreground);
        }

        /* --- DROPDOWN --- */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 0.5rem);
            min-width: 14rem;
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 100;
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

        /* --- NOTIFICATION DOT --- */
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

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted-foreground); }

        /* --- STAT CARD --- */
        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 9999px;
            opacity: 0.06;
            background-color: currentColor;
        }

        /* --- TRANSITION HELPERS --- */
        [x-cloak] { display: none !important; }

        /* Responsive: hide sidebar on mobile, use overlay */
        @media (max-width: 1023px) {
            .sidebar-desktop { display: none; }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- ==================== NAVBAR ==================== --}}
    <header class="navbar">
        <div class="navbar-inner">

            {{-- Left: hamburger + logo --}}
            <div style="display:flex; align-items:center; gap:1rem;">
                {{-- Mobile menu button --}}
                <button class="btn btn-ghost btn-icon lg:hidden" @click="sidebarOpen = !sidebarOpen"
                    style="display:none;" id="mobile-menu-btn">
                    <i data-lucide="menu" style="width:1.25rem;height:1.25rem;"></i>
                </button>

                <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:0.5rem; text-decoration:none;">
                    <div class="logo-box">U</div>
                    <span style="font-size:1.25rem; font-weight:700; color:var(--foreground);"
                        class="hidden-mobile">Urbexium</span>
                </a>
            </div>

            {{-- Center: search --}}
            <div style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:16rem;">
                <div style="position:relative;">
                    <i data-lucide="search"
                        style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--muted-foreground);"></i>
                    <input type="search" placeholder="Buscar spots, comunidades..."
                        class="input"
                        style="padding-left:2.5rem; height:2.25rem; font-size:0.8125rem;" />
                </div>
            </div>

            {{-- Right: actions + user --}}
            <div style="display:flex; align-items:center; gap:0.375rem;">

                {{-- Dark mode toggle --}}
                <button class="btn btn-ghost btn-icon" @click="darkMode = !darkMode"
                    title="Cambiar tema">
                    <i data-lucide="sun"
                        style="width:1.25rem;height:1.25rem;"
                        x-show="darkMode"></i>
                    <i data-lucide="moon"
                        style="width:1.25rem;height:1.25rem;"
                        x-show="!darkMode"
                        x-cloak></i>
                </button>

                {{-- Notifications --}}
                <div class="dropdown" x-data="{ open: false }">
                    <button class="btn btn-ghost btn-icon" @click="open = !open"
                        style="position:relative;">
                        <i data-lucide="bell" style="width:1.25rem;height:1.25rem;"></i>
                        @if(isset($unreadNotifications) && $unreadNotifications > 0)
                            <span class="notif-dot">{{ $unreadNotifications }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu" x-show="open" @click.outside="open = false" x-cloak
                        style="min-width:20rem;">
                        <div style="padding:1rem; border-bottom:1px solid var(--border);">
                            <p style="font-weight:600; font-size:0.9375rem;">Notificaciones</p>
                        </div>
                        <div style="max-height:20rem; overflow-y:auto;">
                            @forelse($notifications ?? [] as $notif)
                                <div style="display:flex; gap:0.75rem; padding:0.875rem 1rem;
                                    border-bottom:1px solid var(--border);
                                    {{ !$notif->read_at ? 'background-color: color-mix(in oklch, var(--primary) 5%, transparent);' : '' }}">
                                    <div style="flex:1;">
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <p style="font-size:0.875rem; font-weight:500;">{{ $notif->data['title'] ?? '' }}</p>
                                            @if(!$notif->read_at)
                                                <span style="width:0.5rem; height:0.5rem; border-radius:9999px; background:var(--primary); display:inline-block;"></span>
                                            @endif
                                        </div>
                                        <p style="font-size:0.8125rem; color:var(--muted-foreground);">{{ $notif->data['message'] ?? '' }}</p>
                                        <p style="font-size:0.75rem; color:var(--muted-foreground); margin-top:0.25rem;">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @empty
                                <div style="padding:2rem; text-align:center; color:var(--muted-foreground); font-size:0.875rem;">
                                    Sin notificaciones nuevas
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- GUEST: botones Login / Registro --}}
                @guest
                <div style="display:flex; align-items:center; gap:0.5rem;">
                    <a href="{{ route('login') }}" class="btn btn-ghost"
                        style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                        Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary"
                        style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                        <i data-lucide="user-plus" style="width:0.9rem;height:0.9rem;"></i>
                        Registrarse
                    </a>
                </div>
                @endguest

                {{-- AUTH: menú de usuario --}}
                @auth
                <div class="dropdown" x-data="{ open: false }">
                    <button class="btn btn-ghost"
                        style="padding:0.25rem 0.75rem 0.25rem 0.375rem; gap:0.5rem;"
                        @click="open = !open">
                        <div class="avatar"
                            style="width:1.875rem; height:1.875rem; font-size:0.8125rem;
                            color:var(--primary-foreground); background:var(--primary);">
                            @if(Auth::user()->avatar ?? null)
                                <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->nombre ?? '' }}"
                                    style="width:100%;height:100%;object-fit:cover;" />
                            @else
                                {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <span style="font-size:0.875rem; font-weight:500;" class="hidden-mobile">
                            {{ Auth::user()->nombre ?? '' }}
                        </span>
                        <i data-lucide="chevron-down" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground);"></i>
                    </button>
                    <div class="dropdown-menu" x-show="open" @click.outside="open = false" x-cloak>
                        {{-- Info usuario --}}
                        <div style="padding:0.875rem 1rem; border-bottom:1px solid var(--border);
                            display:flex; align-items:center; gap:0.75rem;">
                            <div class="avatar"
                                style="width:2.5rem; height:2.5rem; background:var(--primary); color:var(--primary-foreground);">
                                {{ strtoupper(substr(Auth::user()->nombre ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-weight:600; font-size:0.9375rem;">{{ Auth::user()->nombre ?? '' }}</p>
                                <p style="font-size:0.8125rem; color:var(--muted-foreground);">{{ Auth::user()->email ?? '' }}</p>
                            </div>
                        </div>
                        <a href="{{ route('profile') }}" class="dropdown-item">
                            <i data-lucide="user" style="width:1rem;height:1rem;"></i>
                            Perfil
                        </a>
                        <a href="{{ route('settings') }}" class="dropdown-item">
                            <i data-lucide="settings" style="width:1rem;height:1rem;"></i>
                            Configuración
                        </a>
                        <div class="dropdown-separator"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item danger"
                                style="width:100%; background:none; font-family:inherit; text-align:left;">
                                <i data-lucide="log-out" style="width:1rem;height:1rem;"></i>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

            </div>
        </div>
    </header>

    {{-- ==================== SIDEBAR ==================== --}}
    {{-- Mobile overlay --}}
    <div class="sidebar-overlay" x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak
        style="display:none;" id="sidebar-overlay"></div>

    <aside class="sidebar"
        :class="sidebarCollapsed ? 'sidebar-collapsed' : 'sidebar-expanded'"
        style="width:16rem;">
        <div style="display:flex; flex-direction:column; height:100%;">
            <nav style="flex:1; padding:0.75rem; display:flex; flex-direction:column; gap:0.25rem;">
                @php
                    $navItems = [
                        ['route' => 'dashboard',    'label' => 'Dashboard',    'icon' => 'layout-dashboard'],
                        ['route' => 'map',          'label' => 'Mapa',         'icon' => 'map'],
                        ['route' => 'spots.index',  'label' => 'Spots',        'icon' => 'map-pin'],
                        ['route' => 'communities.index', 'label' => 'Comunidades', 'icon' => 'users'],
                        ['route' => 'store.index',  'label' => 'Tienda',       'icon' => 'shopping-bag'],
                        ['route' => 'profile',      'label' => 'Perfil',       'icon' => 'user'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    @php $isActive = request()->routeIs($item['route']) @endphp
                    <a href="{{ route($item['route']) }}"
                        class="nav-item {{ $isActive ? 'active' : '' }}"
                        title="{{ $item['label'] }}">
                        <i data-lucide="{{ $item['icon'] }}" class="nav-item-icon"></i>
                        <span x-show="!sidebarCollapsed">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            {{-- Collapse toggle --}}
            <div style="padding:0.75rem; border-top:1px solid var(--sidebar-border);">
                <button class="btn btn-ghost btn-icon"
                    @click="sidebarCollapsed = !sidebarCollapsed"
                    style="width:2.5rem; height:2.5rem;">
                    <i data-lucide="chevron-left" x-show="!sidebarCollapsed"
                        style="width:1.25rem;height:1.25rem;"></i>
                    <i data-lucide="chevron-right" x-show="sidebarCollapsed"
                        style="width:1.25rem;height:1.25rem;" x-cloak></i>
                </button>
            </div>
        </div>
    </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="main-content"
        :style="sidebarCollapsed ? 'margin-left:4rem' : 'margin-left:16rem'">
        @yield('content')
    </main>

    {{-- ==================== FOOTER ==================== --}}
    @include('layout.footer')

    {{-- Init Lucide icons --}}
    <script>lucide.createIcons();</script>

    {{-- Mobile responsive sidebar --}}
    <script>
        // Show mobile menu button on small screens
        const mobileBtn = document.getElementById('mobile-menu-btn');
        if (window.innerWidth < 1024 && mobileBtn) {
            mobileBtn.style.display = 'flex';
        }
    </script>

    @stack('scripts')
</body>
</html>
