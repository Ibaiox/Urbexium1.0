{{-- resources/views/map/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Mapa de Spots')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* ── Neutralizar padding del main-content para esta página ── */
    .main-content {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
    }

    /* ── Wrapper de página ───────────────────────────────────── */
    .map-page {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 4rem); /* navbar = 4rem */
        overflow: hidden;
    }

    /* ── Header strip ────────────────────────────────────────── */
    .map-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--card);
        flex-shrink: 0;
    }

    .map-header h1 {
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: -0.02em;
        margin: 0 0 0.1rem;
    }

    .map-header p {
        font-size: 0.8rem;
        color: var(--muted-foreground);
        margin: 0;
    }

    /* ── Shell (sidebar + mapa) ──────────────────────────────── */
    .map-shell {
        display: flex;
        flex: 1;
        min-height: 0; /* esencial para que flex hijo se recorte */
        overflow: hidden;
    }

    /* ── Sidebar ─────────────────────────────────────────────── */
    .map-sidebar {
        width: 19rem;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        background: var(--card);
        border-right: 1px solid var(--border);
        overflow: hidden;
        position: relative;
        z-index: 500;
    }

    .map-sidebar-top {
        padding: 1rem 1rem 0;
        flex-shrink: 0;
    }

    .map-sidebar-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--muted-foreground);
        margin: 0 0 0.6rem;
    }

    /* búsqueda */
    .s-wrap { position: relative; margin-bottom: 0.5rem; }
    .s-wrap input {
        width: 100%;
        padding: 0.45rem 0.75rem 0.45rem 2rem;
        border: 1px solid var(--border);
        border-radius: calc(var(--radius) - 2px);
        background: var(--background);
        color: var(--foreground);
        font-size: 0.8rem;
        outline: none;
        box-sizing: border-box;
        transition: border-color 150ms;
    }
    .s-wrap input:focus { border-color: var(--primary); }
    .s-wrap .si {
        position: absolute; left: 0.55rem; top: 50%;
        transform: translateY(-50%);
        color: var(--muted-foreground); display: flex; pointer-events: none;
    }

    /* filtros */
    .f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.4rem; margin-bottom: 0.4rem; }
    .f-sel {
        padding: 0.38rem 0.5rem;
        border: 1px solid var(--border);
        border-radius: calc(var(--radius) - 2px);
        background: var(--background);
        color: var(--foreground);
        font-size: 0.775rem;
        outline: none; cursor: pointer; width: 100%;
        transition: border-color 150ms;
    }
    .f-sel:focus { border-color: var(--primary); }
    .f-full {
        width: 100%; padding: 0.38rem 0.5rem;
        border: 1px solid var(--border);
        border-radius: calc(var(--radius) - 2px);
        background: var(--background); color: var(--foreground);
        font-size: 0.775rem; outline: none; cursor: pointer;
        margin-bottom: 0.4rem; transition: border-color 150ms;
    }
    .f-full:focus { border-color: var(--primary); }

    .f-btns { display: flex; gap: 0.4rem; margin-bottom: 0.875rem; }
    .f-btn {
        flex: 1; padding: 0.38rem 0.5rem;
        border-radius: calc(var(--radius) - 2px);
        font-size: 0.775rem; font-weight: 500;
        cursor: pointer; border: 1px solid var(--border);
        background: var(--background); color: var(--foreground);
        transition: background 150ms; font-family: inherit;
    }
    .f-btn:hover { background: var(--secondary); }
    .f-btn-p { background: var(--primary); color: var(--primary-foreground); border-color: var(--primary); }
    .f-btn-p:hover { opacity: 0.88; }

    .s-divider { height: 1px; background: var(--border); margin: 0 -1rem; }

    /* lista */
    .spot-list {
        flex: 1; overflow-y: auto; min-height: 0;
    }
    .spot-list::-webkit-scrollbar { width: 3px; }
    .spot-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

    .spot-item {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.575rem 1rem;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: background 120ms, border-color 120ms;
    }
    .spot-item:hover { background: var(--secondary); }
    .spot-item.active {
        background: color-mix(in oklch, var(--primary) 8%, transparent);
        border-left-color: var(--primary);
    }

    .s-thumb {
        width: 2.375rem; height: 2.375rem;
        border-radius: calc(var(--radius) - 4px);
        background: var(--secondary); overflow: hidden; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        color: var(--muted-foreground);
    }
    .s-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .s-info { flex: 1; min-width: 0; }
    .s-name {
        font-size: 0.8rem; font-weight: 600;
        margin: 0 0 0.12rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .s-sub {
        display: flex; align-items: center; gap: 0.3rem;
        font-size: 0.68rem; color: var(--muted-foreground);
    }
    .s-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }

    .map-sidebar-count {
        padding: 0.45rem 1rem;
        font-size: 0.7rem; color: var(--muted-foreground);
        border-top: 1px solid var(--border); flex-shrink: 0;
    }

    .list-empty {
        padding: 2rem 1rem; text-align: center; color: var(--muted-foreground);
    }
    .list-empty p { font-size: 0.8rem; margin: 0.5rem 0 0; line-height: 1.5; }

    /* ── Área del mapa ───────────────────────────────────────── */
    .map-area {
        flex: 1; position: relative; min-width: 0;
        isolation: isolate; /* CRÍTICO: crea nuevo stacking context, aísla z-index de Leaflet */
        z-index: 0;
    }

    /* CRÍTICO: posición absoluta para que Leaflet calcule bien */
    #leaflet-map {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    /* loading overlay */
    .map-loading {
        position: absolute; inset: 0; z-index: 2000;
        background: var(--card);
        display: flex; align-items: center; justify-content: center;
        gap: 0.6rem; font-size: 0.875rem; color: var(--muted-foreground);
        transition: opacity 400ms; pointer-events: none;
    }
    .map-loading.gone { opacity: 0; }

    /* spinner */
    .spinner {
        width: 1rem; height: 1rem;
        border: 2px solid var(--border);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* leyenda */
    .map-legend {
        position: absolute; bottom: 1.25rem; right: 1rem; z-index: 1000;
        background: var(--card); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 0.6rem 0.875rem;
        box-shadow: 0 4px 16px rgba(0,0,0,.15); pointer-events: none;
    }
    .lg-title {
        font-size: 0.62rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .07em; color: var(--muted-foreground); margin-bottom: .35rem;
    }
    .lg-row { display: flex; align-items: center; gap: .4rem; font-size: .72rem; margin-bottom: .2rem; }
    .lg-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }

    /* ── Leaflet popup ───────────────────────────────────────── */
    .leaflet-popup-content-wrapper {
        background: var(--card) !important;
        color: var(--card-foreground) !important;
        border: 1px solid var(--border) !important;
        border-radius: var(--radius) !important;
        box-shadow: 0 8px 32px rgba(0,0,0,.2) !important;
        padding: 0 !important; overflow: hidden; width: 255px !important;
    }
    .leaflet-popup-tip-container { display: none !important; }
    .leaflet-popup-content { margin: 0 !important; width: auto !important; }
    .leaflet-popup-close-button {
        color: var(--muted-foreground) !important;
        right: 6px !important; top: 6px !important;
        font-size: 1.1rem !important; z-index: 10;
        background: var(--card) !important;
        border-radius: 50% !important;
        width: 20px !important; height: 20px !important;
        display: flex !important; align-items: center !important; justify-content: center !important;
    }

    /* ── RESPONSIVE MÓVIL ───────────────────────────────────── */
    @media (max-width: 767px) {
        .map-page {
            height: auto;
            overflow: visible;
        }
        .map-shell {
            flex-direction: column;
        }
        .map-sidebar {
            width: 100% !important;
            max-height: 0;
            overflow: hidden;
            border-right: none;
            border-bottom: 1px solid var(--border);
            transition: max-height 350ms ease;
            position: relative;
            z-index: 1; /* el sidebar del layout global (z-index:200) está en fixed, no interfiere */
        }
        .map-sidebar.mobile-open {
            max-height: 420px;
            overflow-y: auto;
        }
        .map-area {
            min-height: 65vh;
            position: relative;
        }
        #leaflet-map {
            position: relative !important;
            inset: auto !important;
            height: 65vh !important;
        }
        .map-toggle-btn {
            display: flex !important;
        }
        .map-legend {
            bottom: 0.5rem;
            right: 0.5rem;
        }
    }

    .map-toggle-btn {
        display: none;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.625rem 1rem;
        background: var(--card);
        border: none;
        border-bottom: 1px solid var(--border);
        color: var(--foreground);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
    }

    .pp-img  { width: 100%; height: 105px; object-fit: cover; display: block; }
    .pp-nimg {
        width: 100%; height: 55px; background: var(--secondary);
        display: flex; align-items: center; justify-content: center; color: var(--muted-foreground);
    }
    .pp-body { padding: 0.7rem; }
    .pp-title { font-size: .875rem; font-weight: 700; margin: 0 0 .25rem; line-height: 1.3; }
    .pp-loc {
        font-size: .7rem; color: var(--muted-foreground);
        display: flex; align-items: center; gap: .25rem; margin-bottom: .45rem;
    }
    .pp-badges { display: flex; gap: .3rem; flex-wrap: wrap; margin-bottom: .55rem; }
    .pbg {
        padding: .1rem .42rem; border-radius: 999px;
        font-size: .62rem; font-weight: 600;
    }
    .pbg-baja      { background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .pbg-media     { background:color-mix(in oklch,#f59e0b 15%,transparent); color:#92400e; }
    .pbg-alta      { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .pbg-verificada{ background:color-mix(in oklch,var(--primary) 15%,transparent); color:var(--primary); }
    .pbg-pendiente { background:color-mix(in oklch,#f59e0b 15%,transparent); color:#92400e; }
    .pbg-rechazada { background:color-mix(in oklch,var(--destructive) 15%,transparent); color:var(--destructive); }
    .pbg-dudosa    { background:color-mix(in oklch,#8b5cf6 15%,transparent); color:#6d28d9; }

    .pp-btn {
        display: block; width: 100%; box-sizing: border-box;
        padding: .42rem; text-align: center;
        background: var(--primary); color: var(--primary-foreground) !important;
        border-radius: calc(var(--radius) - 4px);
        font-size: .775rem; font-weight: 600;
        text-decoration: none !important; transition: opacity 150ms;
    }
    .pp-btn:hover { opacity: .85; }
</style>
@endpush

@section('content')
<div class="map-page">

    {{-- Header --}}
    <div class="map-header">
        <div>
            <h1>Mapa de Spots</h1>
            <p>Explora todas las localizaciones registradas</p>
        </div>
        <a href="{{ route('spots.create') }}" class="btn btn-primary" style="font-size:0.8125rem;">
            <i data-lucide="plus" style="width:.875rem;height:.875rem;"></i>
            Añadir Spot
        </a>
    </div>

    {{-- Shell --}}
    <div class="map-shell">

        {{-- Botón toggle filtros en móvil --}}
        <button class="map-toggle-btn" onclick="toggleMapSidebar()" id="map-sidebar-toggle">
            <i data-lucide="sliders-horizontal" style="width:1rem;height:1rem;"></i>
            <span id="map-toggle-label">Ver filtros y spots</span>
            <i data-lucide="chevron-down" style="width:0.875rem;height:0.875rem; margin-left:auto;" id="map-toggle-icon"></i>
        </button>

        {{-- Sidebar --}}
        <div class="map-sidebar" id="map-sidebar">
            <div class="map-sidebar-top">
                <p class="map-sidebar-label">Filtros</p>

                <div class="s-wrap">
                    <span class="si">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </span>
                    <input type="text" id="searchInput" placeholder="Buscar por nombre..." />
                </div>

                <div class="f-grid">
                    <select class="f-sel" id="filterDificultad">
                        <option value="all">Dificultad</option>
                        <option value="baja">Baja</option>
                        <option value="media">Media</option>
                        <option value="alta">Alta</option>
                    </select>
                    <select class="f-sel" id="filterVerificacion">
                        <option value="all">Estado</option>
                        <option value="verificada">Verificada</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="dudosa">Dudosa</option>
                        <option value="rechazada">Rechazada</option>
                    </select>
                </div>

                <select class="f-full" id="filterCiudad">
                    <option value="all">Todas las ciudades</option>
                    @foreach($ciudades as $ciudad)
                        <option value="{{ $ciudad->id }}">{{ $ciudad->nombre }}</option>
                    @endforeach
                </select>

                <div class="f-btns">
                    <button class="f-btn" id="btnReset">Limpiar</button>
                    <button class="f-btn f-btn-p" id="btnApply">Aplicar</button>
                </div>

                <div class="s-divider"></div>
                <p class="map-sidebar-label" style="margin-top:.75rem;">Spots</p>
            </div>

            <div class="spot-list" id="spotList">
                <div class="list-empty">
                    <div class="spinner" style="margin:0 auto;"></div>
                    <p>Cargando spots...</p>
                </div>
            </div>

            <div class="map-sidebar-count" id="spotCount"></div>
        </div>

        {{-- Área mapa --}}
        <div class="map-area">
            <div class="map-loading" id="mapLoading">
                <div class="spinner"></div>
                Cargando mapa...
            </div>

            <div id="leaflet-map"></div>

            <div class="map-legend">
                <p class="lg-title">Verificación</p>
                <div class="lg-row"><div class="lg-dot" style="background:#22c55e;"></div> Verificada</div>
                <div class="lg-row"><div class="lg-dot" style="background:#f59e0b;"></div> Pendiente</div>
                <div class="lg-row"><div class="lg-dot" style="background:#8b5cf6;"></div> Dudosa</div>
                <div class="lg-row"><div class="lg-dot" style="background:#ef4444;"></div> Rechazada</div>
            </div>
        </div>

    </div>{{-- /map-shell --}}
</div>{{-- /map-page --}}
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const API = '{{ route("map.spots") }}';
    const VC  = { verificada:'#22c55e', pendiente:'#f59e0b', rechazada:'#ef4444', dudosa:'#8b5cf6', default:'#94a3b8' };

    let map, spots = [], markers = {}, activeId = null;

    /* ── Mapa ─────────────────────────────────────────────── */
    function initMap() {
        map = L.map('leaflet-map', { center:[40.2, -3.5], zoom:6, zoomControl:false });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution:'© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom:19,
        }).addTo(map);

        L.control.zoom({ position:'topright' }).addTo(map);

        // Invalidar tamaño tras render (soluciona tiles cortados)
        setTimeout(() => {
            map.invalidateSize();
            document.getElementById('mapLoading').classList.add('gone');
        }, 300);
    }

    /* ── Icono ────────────────────────────────────────────── */
    function mkIcon(ver) {
        const c = VC[ver] || VC.default;
        return L.divIcon({
            className:'',
            iconSize:[30,30], iconAnchor:[15,30], popupAnchor:[0,-32],
            html:`<div style="width:30px;height:30px;background:${c};
                border-radius:50% 50% 50% 0;transform:rotate(-45deg);
                border:2.5px solid rgba(255,255,255,0.9);
                box-shadow:0 3px 10px rgba(0,0,0,.35);
                display:flex;align-items:center;justify-content:center;">
                <svg style="transform:rotate(45deg)" xmlns="http://www.w3.org/2000/svg"
                    width="13" height="13" viewBox="0 0 24 24" fill="white">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3" fill="rgba(0,0,0,0.25)"/>
                </svg>
            </div>`,
        });
    }

    /* ── Popup ────────────────────────────────────────────── */
    function mkPopup(s) {
        const dL = {baja:'Baja',media:'Media',alta:'Alta'};
        const vL = {verificada:'Verificada',pendiente:'Pendiente',rechazada:'Rechazada',dudosa:'Dudosa'};
        const loc = [s.ciudad, s.pais].filter(Boolean).join(', ');
        return `<div>
            ${s.imagen
                ? `<img src="${s.imagen}" class="pp-img" alt="${s.nombre}">`
                : `<div class="pp-nimg"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/></svg></div>`}
            <div class="pp-body">
                <p class="pp-title">${s.nombre}</p>
                ${loc?`<div class="pp-loc"><svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>${loc}</div>`:''}
                <div class="pp-badges">
                    ${s.dificultad?`<span class="pbg pbg-${s.dificultad}">${dL[s.dificultad]||s.dificultad}</span>`:''}
                    ${s.verificacion?`<span class="pbg pbg-${s.verificacion}">${vL[s.verificacion]||s.verificacion}</span>`:''}
                </div>
                <a href="${s.url}" class="pp-btn">Ver detalles →</a>
            </div>
        </div>`;
    }

    /* ── Marcadores ───────────────────────────────────────── */
    function renderMarkers(data) {
        Object.values(markers).forEach(m => map.removeLayer(m));
        markers = {};
        data.forEach(s => {
            if (!s.latitud || !s.longitud) return;
            const m = L.marker([s.latitud, s.longitud], { icon: mkIcon(s.verificacion) });
            m.bindPopup(mkPopup(s), { maxWidth:255 });
            m.on('click', () => { setActive(s.id); scrollTo(s.id); });
            m.addTo(map);
            markers[s.id] = m;
        });
    }

    /* ── Lista ────────────────────────────────────────────── */
    function renderList(data) {
        const list  = document.getElementById('spotList');
        const count = document.getElementById('spotCount');
        count.textContent = `${data.length} spot${data.length!==1?'s':''} encontrado${data.length!==1?'s':''}`;

        if (!data.length) {
            list.innerHTML = `<div class="list-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="opacity:.3;display:block;margin:0 auto"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <p>No se encontraron spots<br>con estos filtros</p>
            </div>`;
            return;
        }

        const dL = {baja:'Baja',media:'Media',alta:'Alta'};
        list.innerHTML = data.map(s => `
            <div class="spot-item" id="item-${s.id}" onclick="focusSpot(${s.id})">
                <div class="s-thumb">
                    ${s.imagen
                        ? `<img src="${s.imagen}" alt="${s.nombre}" loading="lazy">`
                        : `<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/></svg>`}
                </div>
                <div class="s-info">
                    <p class="s-name">${s.nombre}</p>
                    <div class="s-sub">
                        <div class="s-dot" style="background:${VC[s.verificacion]||VC.default};"></div>
                        ${[s.ciudad, s.dificultad ? dL[s.dificultad] : null].filter(Boolean).join(' · ')}
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--muted-foreground);flex-shrink:0;"><path d="m9 18 6-6-6-6"/></svg>
            </div>`).join('');
    }

    /* ── Focus ────────────────────────────────────────────── */
    window.focusSpot = function(id) {
        const s = spots.find(x => x.id === id);
        if (!s || !markers[id]) return;
        setActive(id);
        map.flyTo([s.latitud, s.longitud], 15, { duration:0.9 });
        setTimeout(() => markers[id].openPopup(), 950);
    };

    function setActive(id) {
        if (activeId) { const p = document.getElementById(`item-${activeId}`); if(p) p.classList.remove('active'); }
        activeId = id;
        const el = document.getElementById(`item-${id}`);
        if (el) el.classList.add('active');
    }

    function scrollTo(id) {
        const el = document.getElementById(`item-${id}`);
        if (el) el.scrollIntoView({ behavior:'smooth', block:'nearest' });
    }

    /* ── Fetch ────────────────────────────────────────────── */
    function load(params = {}) {
        document.getElementById('spotList').innerHTML =
            `<div class="list-empty"><div class="spinner" style="margin:0 auto;"></div><p>Cargando...</p></div>`;

        const url = new URL(API, location.origin);
        Object.entries(params).forEach(([k,v]) => { if(v && v!=='all') url.searchParams.set(k,v); });

        fetch(url, { headers:{'X-Requested-With':'XMLHttpRequest'} })
            .then(r => r.json())
            .then(data => {
                spots = data;
                renderMarkers(data);
                renderList(data);
                if (data.length) {
                    const valid = data.filter(s => s.latitud && s.longitud);
                    if (valid.length) {
                        map.fitBounds(L.latLngBounds(valid.map(s=>[s.latitud,s.longitud])),
                            { padding:[50,50], maxZoom:14 });
                    }
                }
            })
            .catch(() => {
                document.getElementById('spotList').innerHTML =
                    `<div class="list-empty"><p>Error al cargar los spots.<br>Recarga la página.</p></div>`;
            });
    }

    function getFilters() {
        return {
            dificultad  : document.getElementById('filterDificultad').value,
            verificacion: document.getElementById('filterVerificacion').value,
            ciudad      : document.getElementById('filterCiudad').value,
            q           : document.getElementById('searchInput').value.trim(),
        };
    }

    document.getElementById('btnApply').addEventListener('click', () => load(getFilters()));
    document.getElementById('btnReset').addEventListener('click', () => {
        ['filterDificultad','filterVerificacion','filterCiudad'].forEach(id => document.getElementById(id).value='all');
        document.getElementById('searchInput').value = '';
        load();
    });

    let deb;
    document.getElementById('searchInput').addEventListener('input', () => {
        clearTimeout(deb); deb = setTimeout(() => load(getFilters()), 380);
    });

    /* ── Init ─────────────────────────────────────────────── */
    initMap();
    load();

    // ── Toggle sidebar móvil ──
    window.toggleMapSidebar = function() {
        const sidebar = document.getElementById('map-sidebar');
        const icon    = document.getElementById('map-toggle-icon');
        const label   = document.getElementById('map-toggle-label');
        const isOpen  = sidebar.classList.contains('mobile-open');
        sidebar.classList.toggle('mobile-open', !isOpen);
        if (icon) icon.setAttribute('data-lucide', isOpen ? 'chevron-down' : 'chevron-up');
        if (label) label.textContent = isOpen ? 'Ver filtros y spots' : 'Ocultar filtros';
        lucide.createIcons();
    };
})();
</script>
@endpush
