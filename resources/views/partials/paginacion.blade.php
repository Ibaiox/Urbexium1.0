{{-- resources/views/partials/paginacion.blade.php --}}
@if($paginator->hasPages())
<div style="display:flex;align-items:center;justify-content:center;gap:0.375rem;margin-top:1.5rem;flex-wrap:wrap;">

    {{-- Anterior --}}
    @if($paginator->onFirstPage())
    <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--muted-foreground);opacity:0.4;cursor:default;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </span>
    @else
    <a href="{{ $paginator->previousPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--foreground);text-decoration:none;transition:all 150ms;"
        onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--foreground)'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </a>
    @endif

    {{-- Páginas --}}
    @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
        @if($page == $paginator->currentPage())
        <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);background:var(--primary);color:var(--primary-foreground);font-size:0.875rem;font-weight:600;border:1px solid var(--primary);">
            {{ $page }}
        </span>
        @else
        <a href="{{ $url }}" style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--foreground);font-size:0.875rem;text-decoration:none;transition:all 150ms;"
            onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--foreground)'">
            {{ $page }}
        </a>
        @endif
    @endforeach

    {{-- Siguiente --}}
    @if($paginator->hasMorePages())
    <a href="{{ $paginator->nextPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--foreground);text-decoration:none;transition:all 150ms;"
        onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)'"
        onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--foreground)'">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
    @else
    <span style="display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:var(--radius);border:1px solid var(--border);color:var(--muted-foreground);opacity:0.4;cursor:default;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </span>
    @endif

    {{-- Info total --}}
    <span style="font-size:0.8125rem;color:var(--muted-foreground);margin-left:0.5rem;">
        {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}
    </span>
</div>
@endif
