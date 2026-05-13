{{-- resources/views/tienda/admin-pedidos.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Admin · Pedidos')

@section('content')
<div style="max-width:1100px; display:flex; flex-direction:column; gap:1.5rem;">

    <div style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.25rem;">Gestión de pedidos</h1>
            <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">Panel de administración de la tienda</p>
        </div>
        <div style="display:flex;gap:0.5rem;align-items:center;">
            <a href="{{ route('tienda.index') }}" class="btn btn-outline" style="height:2.5rem; font-size:0.875rem;">
                <i data-lucide="shopping-bag" style="width:1rem;height:1rem;"></i>
                Ver tienda
            </a>
            <a href="{{ route('admin.index') }}" class="btn btn-ghost" style="height:2.5rem; font-size:0.875rem;">
                <i data-lucide="arrow-left" style="width:1rem;height:1rem;"></i>
                Panel admin
            </a>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.875rem 1rem;border-radius:var(--radius);background:color-mix(in oklch,var(--primary) 12%,transparent);border:1px solid color-mix(in oklch,var(--primary) 30%,transparent);color:var(--primary);font-size:0.875rem;">
        <i data-lucide="check-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>
        {{ session('success') }}
    </div>
    @endif

    {{-- Filtro estado --}}
    <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
        @php
            $estados = ['todos'=>'Todos','pendiente'=>'Pendiente','procesando'=>'Procesando','enviado'=>'Enviado','entregado'=>'Entregado','cancelado'=>'Cancelado'];
            $current = request('estado','todos');
        @endphp
        @foreach($estados as $val => $label)
        <a href="{{ request()->fullUrlWithQuery(['estado' => $val === 'todos' ? null : $val]) }}"
            style="
                display:inline-flex; align-items:center; height:2.25rem; padding:0 0.875rem;
                border-radius:var(--radius); font-size:0.8125rem; font-weight:{{ $current === $val ? '600' : '400' }};
                text-decoration:none;
                border:1px solid {{ $current === $val ? 'var(--primary)' : 'var(--border)' }};
                background:{{ $current === $val ? 'color-mix(in oklch,var(--primary) 12%,transparent)' : 'var(--card)' }};
                color:{{ $current === $val ? 'var(--primary)' : 'var(--foreground)' }};
            ">{{ $label }}</a>
        @endforeach
    </div>

    {{-- ─── Acciones en lote ─────────────────────────────────────────── --}}
    <form method="POST" action="{{ route('tienda.admin.pedidos.bulk') }}" id="bulk-form">
        @csrf
        {{-- Barra de acción lote (se muestra solo cuando hay selección) --}}
        <div id="bulk-bar" style="
            display:none; align-items:center; gap:1rem; flex-wrap:wrap;
            background:color-mix(in oklch,var(--primary) 8%,transparent);
            border:1px solid color-mix(in oklch,var(--primary) 25%,transparent);
            border-radius:var(--radius); padding:0.75rem 1rem; margin-bottom:0.5rem;
        ">
            <span id="bulk-count" style="font-weight:600; font-size:0.875rem; color:var(--primary);"></span>
            <div style="display:flex;gap:0.5rem;align-items:center;flex-wrap:wrap;">
                <span style="font-size:0.875rem;color:var(--muted-foreground);">Cambiar estado a:</span>
                @foreach(['procesando','enviado','entregado','cancelado'] as $e)
                <button type="submit" name="nuevo_estado" value="{{ $e }}"
                    style="height:2rem;padding:0 0.75rem;border-radius:calc(var(--radius)-4px);border:1px solid var(--border);background:var(--card);color:var(--foreground);font-size:0.8125rem;cursor:pointer;font-weight:500;"
                    onmouseover="this.style.background='var(--secondary)'" onmouseout="this.style.background='var(--card)'">
                    {{ ucfirst($e) }}
                </button>
                @endforeach
            </div>
            <button type="button" onclick="clearSelection()"
                style="margin-left:auto;height:2rem;padding:0 0.75rem;border-radius:calc(var(--radius)-4px);border:1px solid var(--border);background:transparent;color:var(--muted-foreground);font-size:0.8125rem;cursor:pointer;">
                Limpiar selección
            </button>
        </div>

        {{-- Tabla --}}
        <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden;">
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.875rem;">
                    <thead>
                        <tr style="border-bottom:1px solid var(--border); background:var(--secondary);">
                            <th style="padding:0.875rem 1rem; width:2.5rem;">
                                <input type="checkbox" id="check-all" style="cursor:pointer;"
                                    title="Seleccionar todos">
                            </th>
                            <th style="padding:0.875rem 1.25rem; text-align:left; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Pedido</th>
                            <th style="padding:0.875rem 1.25rem; text-align:left; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Cliente</th>
                            <th style="padding:0.875rem 1.25rem; text-align:left; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Artículos</th>
                            <th style="padding:0.875rem 1.25rem; text-align:right; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Total</th>
                            <th style="padding:0.875rem 1.25rem; text-align:center; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Estado</th>
                            <th style="padding:0.875rem 1.25rem; text-align:right; font-weight:600; color:var(--muted-foreground); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em;">Cambiar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos as $pedido)
                        <tr style="border-bottom:{{ !$loop->last ? '1px solid var(--border)' : 'none' }}; transition:background 150ms;"
                            onmouseover="this.style.background='var(--secondary)'"
                            onmouseout="this.style.background='transparent'">

                            <td style="padding:1rem 1rem; text-align:center;">
                                <input type="checkbox" name="pedidos[]" value="{{ $pedido->id }}"
                                    class="row-check" style="cursor:pointer;">
                            </td>

                            <td style="padding:1rem 1.25rem;">
                                <a href="{{ route('tienda.pedidos.show', $pedido) }}" style="font-weight:700; color:var(--foreground); text-decoration:none;"
                                    onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--foreground)'">
                                    #{{ $pedido->id }}
                                </a>
                                <p style="color:var(--muted-foreground); font-size:0.75rem; margin:0.125rem 0 0;">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                            </td>

                            <td style="padding:1rem 1.25rem;">
                                <p style="font-weight:500; margin:0;">{{ $pedido->user->nombre }}</p>
                                <p style="color:var(--muted-foreground); font-size:0.75rem; margin:0;">{{ $pedido->user->email }}</p>
                            </td>

                            <td style="padding:1rem 1.25rem; color:var(--muted-foreground);">
                                {{ $pedido->items->count() }} artículo(s)
                            </td>

                            <td style="padding:1rem 1.25rem; text-align:right; font-weight:700; color:var(--primary);">
                                {{ number_format($pedido->total, 2, ',', '.') }} €
                            </td>

                            <td style="padding:1rem 1.25rem; text-align:center;">
                                <span style="
                                    display:inline-flex; align-items:center; gap:0.3rem;
                                    padding:0.2rem 0.625rem; border-radius:9999px; font-size:0.75rem; font-weight:600;
                                    background:{{ $pedido->estadoColor() }}22;
                                    color:{{ $pedido->estadoColor() }};
                                    border:1px solid {{ $pedido->estadoColor() }}44;
                                ">
                                    <span style="width:0.375rem;height:0.375rem;border-radius:50%;background:{{ $pedido->estadoColor() }};"></span>
                                    {{ $pedido->estadoLabel() }}
                                </span>
                            </td>

                            <td style="padding:1rem 1.25rem; text-align:right;">
                                {{-- Cambio individual (fuera del bulk form) --}}
                                <form method="POST" action="{{ route('tienda.admin.pedidos.estado', $pedido) }}" style="display:inline-flex; align-items:center; gap:0.5rem;">
                                    @csrf @method('PATCH')
                                    <select name="estado" onchange="this.form.submit()"
                                        style="height:2rem; padding:0 0.5rem; border-radius:calc(var(--radius)-4px); border:1px solid var(--border); background:var(--secondary); color:var(--foreground); font-size:0.8125rem; cursor:pointer; outline:none;">
                                        @foreach(['pendiente','procesando','enviado','entregado','cancelado'] as $e)
                                        <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="padding:3rem; text-align:center; color:var(--muted-foreground);">
                                No hay pedidos con este filtro
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    {{-- Paginación --}}
    @if($pedidos->hasPages())
    <div>{{ $pedidos->links() }}</div>
    @endif
</div>

<script>
const checkAll  = document.getElementById('check-all');
const rowChecks = document.querySelectorAll('.row-check');
const bulkBar   = document.getElementById('bulk-bar');
const bulkCount = document.getElementById('bulk-count');

function updateBulkBar() {
    const checked = document.querySelectorAll('.row-check:checked').length;
    if (checked > 0) {
        bulkBar.style.display = 'flex';
        bulkCount.textContent = checked + ' pedido' + (checked > 1 ? 's' : '') + ' seleccionado' + (checked > 1 ? 's' : '');
    } else {
        bulkBar.style.display = 'none';
    }
    checkAll.indeterminate = checked > 0 && checked < rowChecks.length;
    checkAll.checked = checked === rowChecks.length && rowChecks.length > 0;
}

checkAll.addEventListener('change', () => {
    rowChecks.forEach(c => c.checked = checkAll.checked);
    updateBulkBar();
});

rowChecks.forEach(c => c.addEventListener('change', updateBulkBar));

function clearSelection() {
    rowChecks.forEach(c => c.checked = false);
    checkAll.checked = false;
    updateBulkBar();
}
</script>
@endsection
