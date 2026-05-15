{{-- resources/views/spots/create.blade.php --}}
@extends('layout.masterpage')
@section('title', 'Añadir Spot')

@push('styles')
{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-picker {
        height: 320px;
        width: 100%;
        border-radius: calc(var(--radius) - 2px);
        border: 1px solid var(--border);
        cursor: crosshair;
        z-index: 0;
    }
    .coords-display { display:flex; gap:0.75rem; margin-top:0.75rem; }
    .coord-box      { flex:1; display:flex; flex-direction:column; gap:0.25rem; }
    .coord-value {
        font-size:0.8125rem; font-weight:600; color:var(--primary);
        background:color-mix(in oklch, var(--primary) 10%, transparent);
        border:1px solid color-mix(in oklch, var(--primary) 25%, transparent);
        border-radius:calc(var(--radius) - 4px);
        padding:0.375rem 0.625rem; font-family:monospace;
    }
    .map-hint {
        font-size:0.8125rem; color:var(--muted-foreground);
        display:flex; align-items:center; gap:0.375rem; margin-bottom:0.75rem;
    }
    .upload-zone {
        border:2px dashed var(--border); border-radius:var(--radius);
        padding:1.5rem; text-align:center; cursor:pointer;
        transition:border-color 200ms, background 200ms;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color:var(--primary);
        background:color-mix(in oklch, var(--primary) 5%, transparent);
    }
    .image-previews {
        display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr));
        gap:0.625rem; margin-top:0.75rem;
    }
    .preview-item {
        position:relative; border-radius:calc(var(--radius) - 2px);
        overflow:hidden; aspect-ratio:1;
        background:var(--secondary); border:1px solid var(--border);
    }
    .preview-item img { width:100%; height:100%; object-fit:cover; }
    .preview-remove {
        position:absolute; top:4px; right:4px;
        width:1.375rem; height:1.375rem; border-radius:9999px;
        background:rgba(0,0,0,0.65); color:#fff; border:none;
        cursor:pointer; display:flex; align-items:center; justify-content:center;
        font-size:0.75rem; line-height:1; transition:background 150ms;
    }
    .preview-remove:hover { background:var(--destructive); }
    .submit-uploading { opacity:0.7; pointer-events:none; }
</style>
@endpush

@section('content')
<div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('spots.index') }}" class="btn btn-ghost btn-icon">
            <i data-lucide="arrow-left" style="width:1.25rem;height:1.25rem;"></i>
        </a>
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; margin:0 0 0.125rem;">Añadir Spot</h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.875rem;">Rellena los datos del nuevo lugar</p>
        </div>
    </div>

    {{-- El form NO tiene enctype — el submit se hace via fetch+FormData --}}
    <form id="spot-form" method="POST" action="{{ route('spots.store') }}"
        style="display:flex; flex-direction:column; gap:1.25rem;">
        @csrf

        {{-- ── Información básica ── --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Información básica</h3></div>
            <div class="card-content" style="display:flex; flex-direction:column; gap:1rem; padding-top:0;">

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">
                        Nombre del spot <span style="color:var(--destructive);">*</span>
                    </label>
                    <input type="text" name="nombre" class="input" placeholder="Ej: Fábrica abandonada de Bilbao"
                        value="{{ old('nombre') }}" required />
                    @error('nombre')<p style="font-size:0.8125rem;color:var(--destructive);margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Descripción</label>
                    <textarea name="descripcion" class="input" rows="4"
                        placeholder="Describe el lugar, su historia, acceso, estado actual...">{{ old('descripcion') }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap:1rem;">
                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">
                            Ciudad <span style="color:var(--destructive);">*</span>
                        </label>
                        <select name="ciudad_id" class="input" required>
                            <option value="">Selecciona ciudad...</option>
                            @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                                {{ $ciudad->nombre }} ({{ $ciudad->pais?->nombre ?? '—' }})
                            </option>
                            @endforeach
                        </select>
                        @error('ciudad_id')<p style="font-size:0.8125rem;color:var(--destructive);margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">
                            Dificultad <span style="color:var(--destructive);">*</span>
                        </label>
                        <select name="dificultad" class="input" required>
                            <option value="baja"  {{ old('dificultad') === 'baja'  ? 'selected' : '' }}>Fácil</option>
                            <option value="media" {{ old('dificultad', 'media') === 'media' ? 'selected' : '' }}>Medio</option>
                            <option value="alta"  {{ old('dificultad') === 'alta'  ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Estado del lugar</label>
                    <input type="text" name="estado" class="input" placeholder="Ej: Abandonado, Semi-demolido, Activo..."
                        value="{{ old('estado') }}" />
                </div>
            </div>
        </div>

        {{-- ── Ubicación en el mapa ── --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ubicación</h3>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Haz clic en el mapa para marcar la ubicación exacta del spot
                </p>
            </div>
            <div class="card-content" style="padding-top:0.5rem;">
                <div style="position:relative; margin-bottom:0.75rem;">
                    <i data-lucide="search" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--muted-foreground); pointer-events:none;"></i>
                    <input type="text" id="address-search" class="input" style="padding-left:2.5rem;"
                        placeholder="Busca una dirección o lugar para centrar el mapa..." />
                </div>
                <div class="map-hint">
                    <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem; color:var(--primary); flex-shrink:0;"></i>
                    Haz clic en el mapa para colocar el pin · Puedes arrastrarlo después
                </div>
                <div id="map-picker"></div>
                <input type="hidden" name="latitud"  id="lat-input"  value="{{ old('latitud') }}" />
                <input type="hidden" name="longitud" id="lng-input"  value="{{ old('longitud') }}" />
                <div class="coords-display">
                    <div class="coord-box">
                        <label style="font-size:0.75rem; font-weight:500; color:var(--muted-foreground);">Latitud</label>
                        <div class="coord-value" id="lat-display">{{ old('latitud') ?: '— Haz clic en el mapa —' }}</div>
                    </div>
                    <div class="coord-box">
                        <label style="font-size:0.75rem; font-weight:500; color:var(--muted-foreground);">Longitud</label>
                        <div class="coord-value" id="lng-display">{{ old('longitud') ?: '— Haz clic en el mapa —' }}</div>
                    </div>
                </div>
                @error('latitud') <p style="font-size:0.8125rem;color:var(--destructive);margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                @error('longitud')<p style="font-size:0.8125rem;color:var(--destructive);margin:0.25rem 0 0;">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- ── Materiales ── --}}
        @if($materiales->count())
        <div class="card">
            <div class="card-header"><h3 class="card-title">Material necesario</h3></div>
            <div class="card-content" style="padding-top:0;">
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:0.5rem;">
                    @foreach($materiales as $mat)
                    <label style="display:flex; align-items:center; gap:0.625rem; padding:0.625rem 0.875rem;
                        background:var(--secondary); border-radius:calc(var(--radius)-2px); cursor:pointer;
                        border:2px solid transparent; transition:border-color 150ms;"
                        onclick="this.style.borderColor = this.querySelector('input').checked ? 'transparent' : 'var(--primary)'">
                        <input type="checkbox" name="materiales[]" value="{{ $mat->id }}"
                            {{ in_array($mat->id, old('materiales', [])) ? 'checked' : '' }}
                            style="accent-color:var(--primary); width:1rem; height:1rem;" />
                        <span style="font-size:0.875rem;">{{ $mat->nombre }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ── Imágenes ── --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Imágenes del spot</h3>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Sube fotos del lugar (JPG, PNG, WEBP · máx. 5 MB por imagen · hasta 10 fotos)
                </p>
            </div>
            <div class="card-content" style="padding-top:0.5rem;">
                {{-- El input visible, sin name — los archivos se gestionan en JS --}}
                <div class="upload-zone" id="upload-zone">
                    <input type="file" id="file-input"
                        accept="image/jpeg,image/png,image/webp,image/gif" multiple
                        style="display:none;" />
                    <i data-lucide="image-plus" style="width:2rem;height:2rem; color:var(--muted-foreground); margin-bottom:0.5rem;"></i>
                    <p style="margin:0; font-weight:500; font-size:0.9375rem;">Haz clic o arrastra imágenes aquí</p>
                    <p style="margin:0.25rem 0 0; font-size:0.8125rem; color:var(--muted-foreground);">JPG, PNG, WEBP · hasta 5 MB cada una</p>
                </div>
                <div class="image-previews" id="image-previews"></div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-bottom:1rem;">
            <a href="{{ route('spots.index') }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" id="submit-btn" class="btn btn-primary">
                <i data-lucide="save" style="width:1rem;height:1rem;"></i>
                <span id="submit-label">Crear Spot</span>
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// ══════════════════════════════════════════
//  MAPA
// ══════════════════════════════════════════
(function () {
    const defaultLat = {{ old('latitud', 40.4168) }};
    const defaultLng = {{ old('longitud', -3.7038) }};
    const hasOld     = {{ old('latitud') ? 'true' : 'false' }};
    const isDark     = document.documentElement.classList.contains('dark');
    const tileUrl    = isDark
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

    const map = L.map('map-picker').setView([defaultLat, defaultLng], hasOld ? 14 : 6);
    L.tileLayer(tileUrl, {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OSM</a> · © <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19,
    }).addTo(map);

    const pinIcon = L.divIcon({
        html: `<div style="width:32px;height:32px;background:var(--primary,#22c55e);border:3px solid white;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 4px 12px rgba(0,0,0,0.35);"></div>`,
        className: '', iconSize: [32,32], iconAnchor: [16,32],
    });

    let marker = null;
    function setCoords(lat, lng) {
        const latR = parseFloat(lat.toFixed(7));
        const lngR = parseFloat(lng.toFixed(7));
        document.getElementById('lat-input').value = latR;
        document.getElementById('lng-input').value = lngR;
        document.getElementById('lat-display').textContent = latR;
        document.getElementById('lng-display').textContent = lngR;
        if (marker) { marker.setLatLng([latR, lngR]); }
        else {
            marker = L.marker([latR, lngR], { icon: pinIcon, draggable: true }).addTo(map);
            marker.on('dragend', e => { const p = e.target.getLatLng(); setCoords(p.lat, p.lng); });
        }
    }
    if (hasOld) setCoords(defaultLat, defaultLng);
    map.on('click', e => setCoords(e.latlng.lat, e.latlng.lng));

    const searchInput = document.getElementById('address-search');
    let t = null;
    searchInput.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); clearTimeout(t); buscar(searchInput.value.trim()); }
    });
    searchInput.addEventListener('input', () => {
        clearTimeout(t);
        if (searchInput.value.trim().length >= 3) t = setTimeout(() => buscar(searchInput.value.trim()), 800);
    });
    function buscar(q) {
        if (!q) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1`, { headers: {'Accept-Language':'es'} })
            .then(r => r.json())
            .then(d => { if (d[0]) { map.setView([+d[0].lat, +d[0].lon], 15); setCoords(+d[0].lat, +d[0].lon); } })
            .catch(() => {});
    }
})();

// ══════════════════════════════════════════
//  IMÁGENES — fetch + FormData (sin DataTransfer)
// ══════════════════════════════════════════
(function () {
    const zone     = document.getElementById('upload-zone');
    const input    = document.getElementById('file-input');
    const previews = document.getElementById('image-previews');
    const MAX      = 10;
    const MAX_SIZE = 5 * 1024 * 1024;

    // Array de File objects — fuente de verdad
    let files = [];

    // Abrir selector al clic en la zona
    zone.addEventListener('click', e => { if (e.target !== input) input.click(); });

    // Drag & drop
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        addFiles(Array.from(e.dataTransfer.files));
    });

    // Selector nativo
    input.addEventListener('change', function () {
        addFiles(Array.from(this.files));
        this.value = '';
    });

    function addFiles(newFiles) {
        for (const f of newFiles) {
            if (!f.type.startsWith('image/')) continue;
            if (f.size > MAX_SIZE)  { alert(`"${f.name}" supera los 5 MB.`); continue; }
            if (files.length >= MAX) { alert('Máximo 10 imágenes.'); break; }
            files.push(f);
        }
        render();
    }

    function render() {
        previews.innerHTML = '';
        files.forEach((f, i) => {
            const el = document.createElement('div');
            el.className = 'preview-item';
            el.innerHTML = `<img src="${URL.createObjectURL(f)}" alt="${f.name}" /><button type="button" class="preview-remove" data-i="${i}">✕</button>`;
            previews.appendChild(el);
        });
        previews.querySelectorAll('.preview-remove').forEach(btn => {
            btn.addEventListener('click', () => { files.splice(+btn.dataset.i, 1); render(); });
        });
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // ── Submit via fetch + FormData ──
    // Razón: asignar .files a un input oculto (DataTransfer) no funciona en todos
    // los browsers. FormData.append(file) sí funciona universalmente.
    document.getElementById('spot-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn   = document.getElementById('submit-btn');
        const label = document.getElementById('submit-label');
        btn.disabled = true;
        label.textContent = files.length ? 'Subiendo imágenes…' : 'Guardando…';

        const fd = new FormData(this);

        // Añadir los File objects directamente — esto siempre funciona
        files.forEach(f => fd.append('imagenes[]', f, f.name));

        fetch(this.action, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(res => {
            // Laravel redirige con 302 → fetch.redirected = true
            if (res.redirected) {
                window.location.href = res.url;
                return;
            }
            // Errores de validación → Laravel devuelve HTML con los errores
            return res.text().then(html => {
                document.open(); document.write(html); document.close();
                // Restaurar historial para que el back funcione
                history.replaceState(null, '', window.location.href);
            });
        })
        .catch(() => {
            btn.disabled = false;
            label.textContent = 'Crear Spot';
            alert('Error de red al guardar. Inténtalo de nuevo.');
        });
    });
})();
</script>
@endpush
