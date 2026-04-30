{{-- resources/views/layout/footer.blade.php --}}
<footer style="
    margin-left: inherit;
    padding: 1.5rem;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
">
    <div style="display:flex; align-items:center; gap:0.5rem;">
        <div class="logo-box" style="width:1.5rem; height:1.5rem; font-size:0.75rem;">U</div>
        <span style="font-size:0.8125rem; color:var(--muted-foreground);">
            Urbexium &copy; {{ date('Y') }} — Explora lo inexplorado
        </span>
    </div>
    <nav style="display:flex; gap:1rem;">
        <a href="#" style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none;">Términos</a>
        <a href="#" style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none;">Privacidad</a>
        <a href="#" style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none;">Contacto</a>
    </nav>
</footer>
