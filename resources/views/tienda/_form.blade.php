{{-- resources/views/tienda/_form.blade.php --}}
{{-- Partial compartido por create.blade.php y edit.blade.php --}}

@php $p = $producto ?? null; @endphp

@php
$fieldStyle = "
    width:100%; height:2.75rem; padding:0 0.875rem;
    border:1px solid var(--border); border-radius:var(--radius);
    background:var(--secondary); color:var(--foreground);
    font-family:inherit; font-size:0.9375rem; outline:none;
    box-sizing:border-box; transition:border-color 150ms;
";
$labelStyle = "display:block; font-size:0.875rem; font-weight:500; margin-bottom:0.375rem;";
$errorStyle = "font-size:0.8125rem; color:var(--destructive); margin:0.25rem 0 0;";
@endphp

{{-- Nombre --}}
<div>
    <label style="{{ $labelStyle }}">Nombre del producto *</label>
    <input type="text" name="nombre" value="{{ old('nombre', $p?->nombre) }}" required
        style="{{ $fieldStyle }}"
        onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'">
    @error('nombre') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
</div>

{{-- Descripción --}}
<div>
    <label style="{{ $labelStyle }}">Descripción</label>
    <textarea name="descripcion" rows="4"
        style="{{ $fieldStyle }} height:auto; padding:0.75rem 0.875rem; resize:vertical;"
        onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'"
    >{{ old('descripcion', $p?->descripcion) }}</textarea>
    @error('descripcion') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
</div>

{{-- Precio y Stock --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
    <div>
        <label style="{{ $labelStyle }}">Precio (€) *</label>
        <input type="number" name="precio" value="{{ old('precio', $p?->precio) }}" min="0" step="0.01" required
            style="{{ $fieldStyle }}"
            onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'">
        @error('precio') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
    </div>
    <div>
        <label style="{{ $labelStyle }}">Stock *</label>
        <input type="number" name="stock" value="{{ old('stock', $p?->stock ?? 0) }}" min="0" required
            style="{{ $fieldStyle }}"
            onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'">
        @error('stock') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
    </div>
</div>

{{-- Categoría --}}
<div>
    <label style="{{ $labelStyle }}">Categoría *</label>
    <select name="categoria" required
        style="{{ $fieldStyle }} cursor:pointer;"
        onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'">
        @foreach(['equipo'=>'Equipo','ropa'=>'Ropa','seguridad'=>'Seguridad','accesorios'=>'Accesorios'] as $val=>$label)
        <option value="{{ $val }}" {{ old('categoria', $p?->categoria) === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('categoria') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
</div>

{{-- Imagen --}}
<div>
    <label style="{{ $labelStyle }}">Imagen del producto</label>

    @if($p?->imagen)
    <div style="margin-bottom:0.75rem; display:flex; align-items:center; gap:1rem;">
        <img src="{{ Storage::url($p->imagen) }}" alt="{{ $p->nombre }}"
            style="width:5rem; height:5rem; object-fit:cover; border-radius:var(--radius); border:1px solid var(--border);">
        <span style="font-size:0.8125rem; color:var(--muted-foreground);">Imagen actual. Sube una nueva para reemplazarla.</span>
    </div>
    @endif

    <input type="file" name="imagen" accept="image/*"
        style="
            width:100%; padding:0.625rem 0.875rem;
            border:1px dashed var(--border); border-radius:var(--radius);
            background:var(--secondary); color:var(--foreground);
            font-size:0.875rem; cursor:pointer; box-sizing:border-box;
        "
        onchange="previewImage(this)">
    <img id="img-preview" style="display:none; margin-top:0.75rem; width:8rem; height:8rem; object-fit:cover; border-radius:var(--radius); border:1px solid var(--border);">
    @error('imagen') <p style="{{ $errorStyle }}">{{ $message }}</p> @enderror
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('img-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
@media (max-width:500px) {
    div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns:1fr !important; }
}
</style>
