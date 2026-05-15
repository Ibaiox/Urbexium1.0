{{-- resources/views/spots/edit.blade.php --}}
@extends('layout.masterpage')
@section('title', 'Editar Spot')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-picker {
        height: 320px; width: 100%;
        border-radius: calc(var(--radius) - 2px);
        border: 1px solid var(--border); cursor: crosshair; z-index: 0;
    }
    .coords-display { display:flex; gap:0.75rem; margin-top:0.75rem; }
    .coord-box { flex:1; display:flex; flex-direction:column; gap:0.25rem; }
    .coord-value {
        font-size:0.8125rem; font-weight:600; color:var(--primary);
        background:color-mix(in oklch, var(--primary) 10%, transparent);
        border:1px solid color-mix(in oklch, var(--primary) 25%, transparent);
        border-radius:calc(var(--radius) - 4px);
        padding:0.375rem 0.625rem; font-family:monospace;
    }
    .map-hint { font-size:0.8125rem; color:var(--muted-foreground); display:flex; align-items:center; gap:0.375rem; margin-bottom:0.75rem; }
    .upload-zone {
        border:2px dashed var(--border); border-radius:var(--radius);
        padding:1.25rem; text-align:center; cursor:pointer;
        transition:border-color 200ms, background 200ms;
    }
    .upload-zone:hover, .upload-zone.drag-over {
        border-color:var(--primary);
        background:color-mix(in oklch, var(--primary) 5%, transparent);
    }
    .image-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:0.625rem; margin-top:0.75rem; }
    .img-existing { position:relative; border-radius:calc(var(--radius) - 2px); overflow:hidden; aspect-ratio:1; border:1px solid var(--border); }
    .img-existing img { width:100%; height:100%; object-fit:cover; display:block; }
    .img-existing.marked-delete { opacity:0.35; }
    .img-existing.marked-delete::after {
        content:'✕'; position:absolute; inset:0;
        background:color-mix(in oklch, var(--destructive) 35%, transparent);
        display:flex; align-items:center; justify-content:center;
        font-size:2rem; color:var(--destructive); font-weight:700;
    }
    .img-action-btn {
        position:absolute; top:4px; right:4px;
        width:1.375rem; height:1.375rem; border-radius:9999px;
        border:none; cursor:pointer; display:flex;
        align-items:center; justify-content:center;
        font-size:0.75rem; line-height:1; transition:background 150ms;
    }
    .img-delete-btn  { background:rgba(0,0,0,0.65); color:#fff; }
    .img-delete-btn:hover { background:var(--destructive); }
    .img-restore-btn { background:var(--primary); color:var(--primary-foreground); }
    .preview-item { position:relative; border-radius:calc(var(--radius) - 2px); overflow:hidden; aspect-ratio:1; background:var(--secondary); border:1px solid var(--border); }
    .preview-item img { width:100%; height:100%; object-fit:cover; }
    .preview-remove {
        position:absolute; top:4px; right:4px;
        width:1.375rem; height:1.375rem; border-radius:9999px;
        background:rgba(0,0,0,0.65); color:#fff; border:none;
        cursor:pointer; display:flex; align-items:center; justify-content:center;
        font-size:0.75rem; line-height:1; transition:background 150ms;
    }
    .preview-remove:hover { background:var(--destructive); }
    .section-label { font-size:0.75rem; font-weight:600; color:var(--muted-foreground); text-transform:uppercase; letter-spacing:0.05em; margin:0.75rem 0 0.375rem; }
</style>
@endpush

@section('content')
<div style="max-width:800px; margin:0 auto; display:flex; flex-direction:column; gap:1.5rem;">

    {{-- Header --}}
    <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
        <a href="{{ route('spots.show', $spot) }}" class="btn btn-ghost btn-icon">
            <i data-lucide="arrow-left" style="width:1.25rem;height:1.25rem;"></i>
        </a>
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; margin:0 0 0.125rem;">Editar Spot</h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.875rem;">{{ $spot->nombre }}</p>
        </div>
    </div>

    {{-- Sin enctype — submit via fetch+FormData --}}
    <form id="edit-form" method="POST" action="{{ route('spots.update', $spot) }}"
        style="display:flex; flex-direction:column; gap:1.25rem;">
        @csrf
        @method('PUT')

        {{-- ── Información básica ── --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Información básica</h3></div>
            <div class="card-content" style="display:flex; flex-direction:column; gap:1rem; padding-top:0;">

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">
                        Nombre <span style="color:var(--destructive);">*</span>
                    </label>
                    <input type="text" name="nombre" class="input"
                        value="{{ old('nombre', $spot->nombre) }}" required />
                    @error('nombre')<p style="font-size:0.8125rem;color:var(--destructive);margin:0.25rem 0 0;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Descripción</label>
                    <textarea name="descripcion" class="input" rows="4">{{ old('descripcion', $spot->descripcion) }}</textarea>
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 200px), 1fr)); gap:1rem;">
                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Ciudad *</label>
                        <select name="ciudad_id" class="input" required>
                            @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad->id }}" {{ old('ciudad_id', $spot->ciudad_id) == $ciudad->id ? 'selected' : '' }}>
                                {{ $ciudad->nombre }} ({{ $ciudad->pais?->nombre ?? '—' }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Dificultad *</label>
                        <select name="dificultad" class="input" required>
                            <option value="baja"  {{ old('dificultad', $spot->dificultad) === 'baja'  ? 'selected' : '' }}>Fácil</option>
                            <option value="media" {{ old('dificultad', $spot->dificultad) === 'media' ? 'selected' : '' }}>Medio</option>
                            <option value="alta"  {{ old('dificultad', $spot->dificultad) === 'alta'  ? 'selected' : '' }}>Difícil</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;">Estado</label>
                    <input type="text" name="estado" class="input" value="{{ old('estado', $spot->estado) }}" />
                </div>

                <div style="display:flex; align-items:center; gap:0.625rem; padding:0.875rem; background:var(--secondary); border-radius:calc(var(--radius)-2px);">
                    <input type="checkbox" name="visibility" value="1" id="visibility"
                        {{ old('visibility', $spot->visibility) ? 'checked' : '' }}
                        style="accent-color:var(--primary); width:1rem; height:1rem;" />
                    <label for="visibility" style="font-size:0.875rem; cursor:pointer;">
                        Spot visible públicamente
                    </label>
                </div>
            </div>
        </div>

        {{-- ── Ubicación en el mapa ── --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ubicación</h3>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                    Haz clic en el mapa para mover el pin a la ubicación exacta
                </p>
            </div>
            <div class="card-content" style="padding-top:0.5rem;">
                <div style="position:relative; margin-bottom:0.75rem;">
                    <i data-lucide="search" style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); width:1rem; height:1rem; color:var(--muted-foreground); pointer-events:none;"></i>
                    <input type="text" id="address-search" class="input" style="padding-left:2.5rem;"
                        placeholder="Busca una dirección para centrar el mapa..." />
                </div>
                <div class="map-hint">
                    <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem; color:var(--primary); flex-shrink:0;"></i>
                    Haz clic en el mapa para mover el pin · También puedes arrastrarlo
                </div>
                <div id="map-picker"></div>
                <input type="hidden" name="latitud"  id="lat-input"  value="{{ old('latitud',  $spot->latitud) }}" />
                <input type="hidden" name="longitud" id="lng-input"  value="{{ old('longitud', $spot->longitud) }}" />
                <div class="coords-display">
                    <div class="coord-box">
                        <label style="font-size:0.75rem; font-weight:500; color:var(--muted-foreground);">Latitud</label>
                        <div class="coord-value" id="lat-display">{{ old('latitud', $spot->latitud) }}</div>
                    </div>
                    <div class="coord-box">
                        <label style="font-size:0.75rem; font-weight:500; color:var(--muted-foreground);">Longitud</label>
                        <div class="coord-value" id="lng-display">{{ old('longitud', $spot->longitud) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Materiales ── --}}
        @if($materiales->count())
        <div class="card">
            <div class="card-header"><h3 class="card-title">Material necesario</h3></div>
            <div class="card-content" style="padding-top:0;">
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px, 1fr)); gap:0.5rem;">
                    @foreach($materiales as $mat)
                    @php $checked = $spot->materiales->contains($mat->id) @endphp
                    <label style="display:flex; align-items:center; gap:0.625rem; padding:0.625rem 0.875rem;
                        background:var(--secondary); border-radius:calc(var(--radius)-2px); cursor:pointer;
                        border:2px solid {{ $checked ? 'var(--primary)' : 'transparent' }}; transition:border-color 150ms;"
                        onclick="this.style.borderColor = this.querySelector('input').checked ? 'transparent' : 'var(--primary)'">
                        <input type="checkbox" name="materiales[]" value="{{ $mat->id }}"
                            {{ $checked ? 'checked' : '' }}
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
                    Gestiona las fotos del lugar · hasta 10 en total
                </p>
            </div>
            <div class="card-content" style="padding-top:0.5rem;">

                {{-- Imágenes existentes --}}
                @if($spot->imagenes->count())
                <p class="section-label">Imágenes actuales</p>
                <div class="image-grid" id="existing-grid">
                    @foreach($spot->imagenes as $img)
                    <div class="img-existing" id="img-wrap-{{ $img->id }}">
                        <img src="{{ $img->url }}" alt="Imagen del spot" />
                        <input type="checkbox" name="delete_images[]"
                            value="{{ $img->id }}"
                            id="del-{{ $img->id }}"
                            style="display:none;" />
                        <button type="button" class="img-action-btn img-delete-btn"
                            id="btn-{{ $img->id }}"
                            title="Marcar para eliminar"
                            onclick="toggleDeleteImage({{ $img->id }})">✕</button>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:0.75rem; color:var(--muted-foreground); margin:0.375rem 0 0;">
                    Haz clic en ✕ para marcar una imagen para eliminar · haz clic de nuevo para desmarcarla
                </p>
                @endif

                {{-- Subir nuevas --}}
                <p class="section-label" style="margin-top:1rem;">Añadir nuevas imágenes</p>
                <div class="upload-zone" id="upload-zone">
                    <input type="file" id="file-input"
                        accept="image/jpeg,image/png,image/webp,image/gif"
                        multiple style="display:none;" />
                    <i data-lucide="image-plus" style="width:1.75rem;height:1.75rem; color:var(--muted-foreground); margin-bottom:0.375rem;"></i>
                    <p style="margin:0; font-weight:500;">Haz clic o arrastra imágenes aquí</p>
                    <p style="margin:0.25rem 0 0; font-size:0.8125rem; color:var(--muted-foreground);">JPG, PNG, WEBP · hasta 5 MB cada una</p>
                </div>
                <div class="image-grid" id="new-previews"></div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="display:flex; justify-content:flex-end; gap:0.75rem; padding-bottom:1rem;">
            <a href="{{ route('spots.show', $spot) }}" class="btn btn-secondary">Cancelar</a>
            <button type="submit" id="submit-btn" class="btn btn-primary">
                <i data-lucide="save" style="width:1rem;height:1rem;"></i>
                <span id="submit-label">Guardar cambios</span>
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
    const initLat = {{ old('latitud',  $spot->latitud) }};
    const initLng = {{ old('longitud', $spot->longitud) }};
    const isDark  = document.documentElement.classList.contains('dark');
    const tileUrl = isDark
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

    const map = L.map('map-picker').setView([initLat, initLng], 14);
    L.tileLayer(tileUrl, {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OSM</a> · © <a href="https://carto.com/">CARTO</a>',
        maxZoom: 19,
    }).addTo(map);

    const pinIcon = L.divIcon({
        html: `<div style="width:32px;height:32px;background:var(--primary,#22c55e);border:3px solid white;border-radius:50% 50% 50% 0;transform:rotate(-45deg);box-shadow:0 4px 12px rgba(0,0,0,0.35);"></div>`,
        className: '', iconSize: [32,32], iconAnchor: [16,32],
    });

    let marker = L.marker([initLat, initLng], { icon: pinIcon, draggable: true }).addTo(map);

    function setCoords(lat, lng) {
        const latR = parseFloat(lat.toFixed(7));
        const lngR = parseFloat(lng.toFixed(7));
        document.getElementById('lat-input').value = latR;
        document.getElementById('lng-input').value = lngR;
        document.getElementById('lat-display').textContent = latR;
        document.getElementById('lng-display').textContent = lngR;
        marker.setLatLng([latR, lngR]);
    }
    marker.on('dragend', e => { const p = e.target.getLatLng(); setCoords(p.lat, p.lng); });
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
//  TOGGLE ELIMINAR IMAGEN EXISTENTE
// ══════════════════════════════════════════
function toggleDeleteImage(id) {
    const wrap = document.getElementById('img-wrap-' + id);
    const cb   = document.getElementById('del-' + id);
    const btn  = document.getElementById('btn-' + id);
    cb.checked = !cb.checked;
    if (cb.checked) {
        wrap.classList.add('marked-delete');
        btn.textContent = '↩';
        btn.title = 'Desmarcar';
        btn.classList.replace('img-delete-btn', 'img-restore-btn');
    } else {
        wrap.classList.remove('marked-delete');
        btn.textContent = '✕';
        btn.title = 'Marcar para eliminar';
        btn.classList.replace('img-restore-btn', 'img-delete-btn');
    }
}

// ══════════════════════════════════════════
//  NUEVAS IMÁGENES — fetch + FormData
// ══════════════════════════════════════════
(function () {
    const zone     = document.getElementById('upload-zone');
    const input    = document.getElementById('file-input');
    const previews = document.getElementById('new-previews');
    const MAX      = 10;
    const MAX_SIZE = 5 * 1024 * 1024;
    let files = [];

    zone.addEventListener('click', e => { if (e.target !== input) input.click(); });
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('drag-over'); });
    zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
    zone.addEventListener('drop', e => {
        e.preventDefault(); zone.classList.remove('drag-over');
        addFiles(Array.from(e.dataTransfer.files));
    });
    input.addEventListener('change', function () { addFiles(Array.from(this.files)); this.value = ''; });

    function addFiles(newFiles) {
        for (const f of newFiles) {
            if (!f.type.startsWith('image/')) continue;
            if (f.size > MAX_SIZE) { alert(`"${f.name}" supera los 5 MB.`); continue; }
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
    document.getElementById('edit-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const btn   = document.getElementById('submit-btn');
        const label = document.getElementById('submit-label');
        btn.disabled = true;
        label.textContent = files.length ? 'Subiendo imágenes…' : 'Guardando…';

        const fd = new FormData(this);

        // Añadir archivos nuevos directamente al FormData
        files.forEach(f => fd.append('imagenes[]', f, f.name));

        fetch(this.action, {
            method: 'POST',      // Laravel acepta POST con _method=PUT
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
        .then(res => {
            if (res.redirected) { window.location.href = res.url; return; }
            return res.text().then(html => {
                document.open(); document.write(html); document.close();
                history.replaceState(null, '', window.location.href);
            });
        })
        .catch(() => {
            btn.disabled = false;
            label.textContent = 'Guardar cambios';
            alert('Error de red al guardar. Inténtalo de nuevo.');
        });
    });
})();
</script>
@endpush
