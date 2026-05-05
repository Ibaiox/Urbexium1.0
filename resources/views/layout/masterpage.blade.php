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
            --sidebar-collapsed-w: 4rem;
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

        *, *::before, *::after { box-sizing: border-box; border-color: var(--border); }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            margin: 0;
        }

        [x-cloak] { display: none !important; }

        /* ─── NAVBAR ─── */
        .navbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 50;
            height: 4rem;
            border-bottom: 1px solid var(--border);
            background-color: color-mix(in oklch, var(--background) 85%, transparent);
            backdrop-filter: blur(20px);
        }
        .navbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 1rem;
        }

        /* ─── SIDEBAR ─── */
        .sidebar {
            position: fixed;
            left: 0;
            top: 4rem;
            z-index: 40;
            height: calc(100vh - 4rem);
            border-right: 1px solid var(--sidebar-border);
            background-color: var(--sidebar);
            overflow: hidden;
            transition: width 280ms cubic-bezier(0.4, 0, 0.2, 1);
            width: var(--sidebar-w);
        }
        .sidebar.collapsed { width: var(--sidebar-collapsed-w); }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 39;
            background-color: rgba(0,0,0,0.55);
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
            transition: background-color 150ms, color 150ms;
            white-space: nowrap;
            overflow: hidden;
        }
        .nav-item:hover { background-color: var(--secondary); color: var(--foreground); }
        .nav-item.active {
            background-color: var(--primary);
            color: var(--primary-foreground);
            box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 35%, transparent);
        }
        .nav-item-icon { flex-shrink: 0; width: 1.25rem; height: 1.25rem; }
        .nav-item-label {
            transition: opacity 200ms, width 200ms;
            overflow: hidden;
        }

        /* ─── CARDS ─── */
        .card {
            background-color: var(--card);
            color: var(--card-foreground);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .card-header { padding: 1.25rem 1.5rem 0.75rem; }
        .card-title { font-size: 1rem; font-weight: 600; line-height: 1.4; }
        .card-content { padding: 0.75rem 1.5rem 1.25rem; }

        /* ─── BADGES ─── */
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            padding: 0.125rem 0.625rem;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-primary { background-color: color-mix(in oklch, var(--primary) 15%, transparent); color: var(--primary); }
        .badge-accent  { background-color: color-mix(in oklch, var(--accent) 15%, transparent); color: var(--accent); }
        .badge-muted   { background-color: var(--muted); color: var(--muted-foreground); }
        .badge-destructive { background-color: color-mix(in oklch, var(--destructive) 15%, transparent); color: var(--destructive); }

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
        .btn-primary { background-color: var(--primary); color: var(--primary-foreground); }
        .btn-primary:hover { opacity: 0.9; box-shadow: 0 4px 14px color-mix(in oklch, var(--primary) 40%, transparent); }
        .btn-ghost { background-color: transparent; color: var(--foreground); }
        .btn-ghost:hover { background-color: var(--secondary); }
        .btn-secondary { background-color: var(--secondary); color: var(--secondary-foreground); }
        .btn-secondary:hover { opacity: 0.85; }
        .btn-destructive { background-color: var(--destructive); color: #fff; }
        .btn-destructive:hover { opacity: 0.9; }
        .btn-icon { padding: 0.5rem; width: 2.25rem; height: 2.25rem; justify-content: center; }

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
        .input:focus { border-color: var(--ring); box-shadow: 0 0 0 2px color-mix(in oklch, var(--ring) 20%, transparent); }
        .input::placeholder { color: var(--muted-foreground); }
        textarea.input { height: auto; padding: 0.625rem 0.75rem; resize: vertical; }

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
        .logo-box {
            width: 2.25rem; height: 2.25rem;
            border-radius: var(--radius);
            background-color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1.125rem;
            color: var(--primary-foreground);
        }

        /* ─── DROPDOWN ─── */
        .dropdown { position: relative; }
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
            width: 100%;
            background: none;
            border: none;
            text-align: left;
            font-family: inherit;
        }
        .dropdown-item:hover { background-color: var(--secondary); }
        .dropdown-item.danger { color: var(--destructive); }
        .dropdown-separator { border-top: 1px solid var(--border); margin: 0.25rem 0; }

        /* ─── NOTIF ─── */
        .notif-dot {
            position: absolute; top: -2px; right: -2px;
            width: 1rem; height: 1rem;
            border-radius: 9999px;
            background-color: var(--primary);
            color: var(--primary-foreground);
            font-size: 0.625rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* ─── SCROLLBAR ─── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted-foreground); }

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
            .collapse-btn { display: none !important; }
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- ==================== NAVBAR ==================== --}}
<header class="navbar">
    <div class="navbar-inner">

        {{-- Left: hamburger (móvil) + logo --}}
        <div style="display:flex; align-items:center; gap:1rem;">
            <button class="btn btn-ghost btn-icon"
                id="mobile-menu-btn"
                style="display:none;"
                onclick="toggleMobileSidebar()"
                title="Abrir menú">
                <i data-lucide="menu" style="width:1.25rem;height:1.25rem;"></i>
            </button>

            <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:0.5rem; text-decoration:none;">
                <div class="logo-box">U</div>
                <span style="font-size:1.25rem; font-weight:700; color:var(--foreground);" id="logo-text">Urbexium</span>
            </a>
        </div>

        {{-- Center: search --}}
        <div style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:18rem;">
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

            {{-- Dark mode toggle --}}
            <button class="btn btn-ghost btn-icon"
                onclick="toggleDarkMode()"
                id="darkmode-btn"
                title="Cambiar tema">
                <i data-lucide="sun"  id="icon-sun"  style="width:1.25rem;height:1.25rem; display:none;"></i>
                <i data-lucide="moon" id="icon-moon" style="width:1.25rem;height:1.25rem;"></i>
            </button>

            @guest
            <div style="display:flex; align-items:center; gap:0.5rem;">
                <a href="{{ route('login') }}" class="btn btn-ghost" style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary" style="font-size:0.875rem; padding:0.4rem 0.875rem;">
                    <i data-lucide="user-plus" style="width:0.9rem;height:0.9rem;"></i>
                    Registrarse
                </a>
            </div>
            @endguest

            @auth
            {{-- Notificaciones --}}
            <div style="position:relative;" id="notif-wrapper">
                <button class="btn btn-ghost btn-icon" onclick="toggleDropdown('notif-menu')" style="position:relative;">
                    <i data-lucide="bell" style="width:1.25rem;height:1.25rem;"></i>
                </button>
                <div id="notif-menu" class="dropdown-menu" style="display:none; min-width:20rem;">
                    <div style="padding:1rem; border-bottom:1px solid var(--border);">
                        <p style="font-weight:600; font-size:0.9375rem; margin:0;">Notificaciones</p>
                    </div>
                    <div style="padding:2rem; text-align:center; color:var(--muted-foreground); font-size:0.875rem;">
                        Sin notificaciones nuevas
                    </div>
                </div>
            </div>

            {{-- Usuario --}}
            <div style="position:relative;" id="user-wrapper">
                <button class="btn btn-ghost" style="padding:0.25rem 0.75rem 0.25rem 0.375rem; gap:0.5rem;"
                    onclick="toggleDropdown('user-menu')">
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
                    <i data-lucide="chevron-down" style="width:0.875rem;height:0.875rem; color:var(--muted-foreground);"></i>
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
</header>

{{-- ==================== OVERLAY MÓVIL ==================== --}}
<div id="sidebar-overlay" class="sidebar-overlay" style="display:none;" onclick="closeMobileSidebar()"></div>

{{-- ==================== SIDEBAR ==================== --}}
<aside class="sidebar" id="main-sidebar">
    <div style="display:flex; flex-direction:column; height:100%; overflow:hidden;">

        <nav style="flex:1; padding:0.75rem; display:flex; flex-direction:column; gap:0.25rem; overflow-y:auto; overflow-x:hidden;">
            @php
                $navItems = [
                    ['route' => 'dashboard',         'icon' => 'home',         'label' => 'Inicio'],
                    ['route' => 'spots.index',        'icon' => 'map-pin',      'label' => 'Spots'],
                    ['route' => 'map',                'icon' => 'map',          'label' => 'Mapa'],
                    ['route' => 'comunidades.index',  'icon' => 'users',        'label' => 'Comunidades'],
                   ['route' => 'tienda.index', 'icon' => 'shopping-bag', 'label' => 'Tienda'],
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
        </nav>

        {{-- Collapse toggle (solo desktop) --}}
        <div style="padding:0.75rem; border-top:1px solid var(--sidebar-border);" class="collapse-btn">
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

    const sidebar      = document.getElementById('main-sidebar');
    const mainContent  = document.getElementById('main-content');
    const collapseIcon = document.getElementById('collapse-icon');
    const logoText     = document.getElementById('logo-text');
    const usernameText = document.getElementById('username-text');
    const labels       = document.querySelectorAll('.sidebar-label');

    let isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    let isMobile    = window.innerWidth < 1024;

    function applyDesktopState() {
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.style.marginLeft = 'var(--sidebar-collapsed-w)';
            if (collapseIcon) collapseIcon.setAttribute('data-lucide', 'chevron-right');
            labels.forEach(l => l.style.opacity = '0');
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.style.marginLeft = 'var(--sidebar-w)';
            if (collapseIcon) collapseIcon.setAttribute('data-lucide', 'chevron-left');
            labels.forEach(l => l.style.opacity = '1');
        }
        lucide.createIcons();
    }

    function applyMobileState() {
        mainContent.style.marginLeft = '0';
        sidebar.classList.remove('collapsed');
        labels.forEach(l => l.style.opacity = '1');
        document.getElementById('mobile-menu-btn').style.display = 'flex';
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
            if (isMobile) { applyMobileState(); } else { applyDesktopState(); }
        }
    });

    let dark = localStorage.getItem('darkMode') !== 'false';

    function applyDarkMode() {
        document.documentElement.classList.toggle('dark', dark);
        document.getElementById('icon-sun').style.display  = dark ? 'block' : 'none';
        document.getElementById('icon-moon').style.display = dark ? 'none'  : 'block';
    }

    function toggleDarkMode() {
        dark = !dark;
        localStorage.setItem('darkMode', dark);
        applyDarkMode();
    }

    applyDarkMode();

    function toggleDropdown(id) {
        const menu = document.getElementById(id);
        const isOpen = menu.style.display === 'block';
        document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        if (!isOpen) menu.style.display = 'block';
    }

    document.addEventListener('click', function(e) {
        const wrappers = ['notif-wrapper', 'user-wrapper'];
        const clickedInside = wrappers.some(id => {
            const el = document.getElementById(id);
            return el && el.contains(e.target);
        });
        if (!clickedInside) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        }
    });
</script>

@stack('scripts')
</body>
</html>
