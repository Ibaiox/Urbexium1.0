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
        <div class="logo-box" style="width:1.5rem; height:1.5rem; font-size:0.75rem; background:var(--primary); color:var(--primary-foreground); border-radius:6px; display:flex; align-items:center; justify-content:center; font-weight:800;">U</div>
        <span style="font-size:0.8125rem; color:var(--muted-foreground);">
            Urbexium &copy; {{ date('Y') }} — Explora lo inexplorado
        </span>
    </div>
    <nav style="display:flex; gap:1.25rem; flex-wrap:wrap; align-items:center;">
        <a href="{{ route('contacto.index') }}"
            style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; transition:color 150ms;"
            onmouseover="this.style.color='var(--foreground)'" onmouseout="this.style.color='var(--muted-foreground)'">
            Contacto
        </a>
        <span style="color:var(--border);">·</span>
        <a href="https://astonwolf.com/aviso-legal" target="_blank" rel="noopener"
            style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; transition:color 150ms;"
            onmouseover="this.style.color='var(--foreground)'" onmouseout="this.style.color='var(--muted-foreground)'">
            Aviso Legal
        </a>
        <span style="color:var(--border);">·</span>
        <a href="https://astonwolf.com/privacidad" target="_blank" rel="noopener"
            style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; transition:color 150ms;"
            onmouseover="this.style.color='var(--foreground)'" onmouseout="this.style.color='var(--muted-foreground)'">
            Privacidad
        </a>
        <span style="color:var(--border);">·</span>
        <a href="https://astonwolf.com/cookies" target="_blank" rel="noopener"
            style="font-size:0.8125rem; color:var(--muted-foreground); text-decoration:none; transition:color 150ms;"
            onmouseover="this.style.color='var(--foreground)'" onmouseout="this.style.color='var(--muted-foreground)'">
            Cookies
        </a>
    </nav>
</footer>
