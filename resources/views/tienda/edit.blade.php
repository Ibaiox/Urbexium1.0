{{-- resources/views/tienda/edit.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Editar: ' . $producto->nombre)

@section('content')
<div style="max-width:700px; display:flex; flex-direction:column; gap:1.5rem;">

    <div>
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">Editar producto</h1>
        <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">{{ $producto->nombre }}</p>
    </div>

    @if($errors->any())
    <div style="padding:1rem; border-radius:var(--radius); background:color-mix(in oklch,var(--destructive) 10%,transparent); border:1px solid color-mix(in oklch,var(--destructive) 30%,transparent);">
        <ul style="margin:0; padding-left:1.25rem; display:flex; flex-direction:column; gap:0.25rem;">
            @foreach($errors->all() as $e)
            <li style="color:var(--destructive); font-size:0.875rem;">{{ $e }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('tienda.update', $producto) }}" enctype="multipart/form-data"
        style="display:flex; flex-direction:column; gap:1.25rem;">
        @csrf @method('PUT')

        @include('tienda._form', ['producto' => $producto])

        {{-- Toggle activo --}}
        <label style="display:flex; align-items:center; gap:0.75rem; cursor:pointer;">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1" {{ $producto->activo ? 'checked' : '' }}
                style="width:1.125rem; height:1.125rem; accent-color:var(--primary); cursor:pointer;">
            <span style="font-size:0.9375rem; font-weight:500;">Producto activo (visible en la tienda)</span>
        </label>

        <div style="display:flex; gap:0.75rem; padding-top:0.5rem;">
            <button type="submit" class="btn btn-primary" style="height:2.75rem; padding:0 1.5rem;">
                <i data-lucide="save" style="width:1rem; height:1rem;"></i>
                Guardar cambios
            </button>
            <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.75rem; padding:0 1.25rem;">Cancelar</a>
        </div>
    </form>
</div>
@endsection
