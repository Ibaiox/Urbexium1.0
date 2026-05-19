{{-- resources/views/comunidades/show.blade.php --}}
@extends('layout.masterpage')

@section('title', $community->name . ' — Comunidades')

@section('content')

{{-- ═══════════════════════════════════════════════════════════════════════
     Modal de confirmación personalizado (reemplaza confirm() del navegador)
     ═══════════════════════════════════════════════════════════════════════ --}}
<div id="urbex-modal"
     style="display:none; position:fixed; inset:0; z-index:9999;
            background:rgba(0,0,0,0.55); backdrop-filter:blur(4px);
            align-items:center; justify-content:center; padding:1rem;">
    <div style="background:var(--card); border:1px solid var(--border);
                border-radius:var(--radius); padding:1.75rem; max-width:26rem; width:100%;
                box-shadow:0 20px 60px rgba(0,0,0,0.4);">
        <div id="modal-icon-wrap" style="display:flex; align-items:center; justify-content:center;
                    width:3rem; height:3rem; border-radius:50%; margin-bottom:1.125rem;"></div>
        <h3 id="modal-title" style="font-size:1rem; font-weight:600; margin:0 0 0.5rem; line-height:1.3;"></h3>
        <p  id="modal-body"  style="font-size:0.875rem; color:var(--muted-foreground); margin:0 0 1.5rem; line-height:1.6;"></p>
        <div style="display:flex; gap:0.625rem; justify-content:flex-end;">
            <button id="modal-cancel"
                    style="padding:0.5625rem 1.125rem; border-radius:var(--radius);
                           border:1px solid var(--border); background:var(--secondary);
                           color:var(--foreground); font-size:0.875rem; font-weight:500;
                           cursor:pointer; font-family:inherit;"
                    onmouseenter="this.style.background='var(--muted)'"
                    onmouseleave="this.style.background='var(--secondary)'">
                Cancelar
            </button>
            <button id="modal-confirm"
                    style="padding:0.5625rem 1.125rem; border-radius:var(--radius);
                           border:none; font-size:0.875rem; font-weight:600;
                           cursor:pointer; font-family:inherit;">
                Confirmar
            </button>
        </div>
    </div>
</div>

<div style="display:flex; flex-direction:column; gap:1.75rem; max-width:1200px; margin:0 auto; width:100%;">

    {{-- Breadcrumb ───────────────────────────────────────────────────────────── --}}
    <div style="display:flex; align-items:center; gap:0.5rem; font-size:0.8125rem; color:var(--muted-foreground);">
        <a href="{{ route('comunidades.index') }}" style="color:var(--muted-foreground); text-decoration:none;">Comunidades</a>
        <i data-lucide="chevron-right" style="width:0.875rem; height:0.875rem;"></i>
        <span style="color:var(--foreground); font-weight:500;">{{ $community->name }}</span>
    </div>

    {{-- Flash messages ───────────────────────────────────────────────────────── --}}
    @foreach(['success' => 'check-circle', 'info' => 'info', 'error' => 'alert-circle'] as $type => $icon)
    @if(session($type))
    <div style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.25rem;
                background:color-mix(in oklch, var(--primary) 10%, transparent);
                border:1px solid color-mix(in oklch, var(--primary) 30%, transparent);
                border-radius:var(--radius); font-size:0.875rem; color:var(--foreground);">
        <i data-lucide="{{ $icon }}" style="width:1.125rem; height:1.125rem; color:var(--primary); flex-shrink:0;"></i>
        {{ session($type) }}
    </div>
    @endif
    @endforeach

    @if($errors->any())
    <div style="display:flex; flex-direction:column; gap:0.25rem; padding:0.875rem 1.25rem;
                background:color-mix(in oklch, var(--destructive) 8%, transparent);
                border:1px solid color-mix(in oklch, var(--destructive) 30%, transparent);
                border-radius:var(--radius); font-size:0.875rem; color:var(--destructive);">
        @foreach($errors->all() as $e)<p style="margin:0;">{{ $e }}</p>@endforeach
    </div>
    @endif

    {{-- Layout principal ─────────────────────────────────────────────────────── --}}
    <div class="community-layout"
         style="display:grid; gap:1.5rem;
                grid-template-columns:clamp(240px,28%,300px) 1fr;
                align-items:start;">

        {{-- ── Columna izquierda ─────────────────────────────────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:1.25rem;">

            {{-- Tarjeta info comunidad ---------------------------------------- --}}
            <div style="background:var(--card); border:1px solid var(--border);
                        border-radius:var(--radius); overflow:hidden;">

                @if($community->image)
                <img src="{{ asset('storage/' . $community->image) }}" alt="{{ $community->name }}"
                     style="width:100%; height:9rem; object-fit:cover; display:block;" />
                @else
                <div style="height:5rem; display:flex; align-items:center; justify-content:center;
                            background:color-mix(in oklch, var(--primary) 10%, transparent);">
                    <i data-lucide="users-round" style="width:2rem; height:2rem;
                       color:color-mix(in oklch, var(--primary) 55%, transparent);"></i>
                </div>
                @endif

                <div style="padding:1.25rem; display:flex; flex-direction:column; gap:0.875rem;">
                    <div>
                        <h1 style="font-size:1.1875rem; font-weight:700; margin:0 0 0.375rem; line-height:1.3;">
                            {{ $community->name }}
                        </h1>
                        <span style="display:inline-flex; align-items:center; gap:0.3rem;
                                     font-size:0.8125rem; color:var(--muted-foreground);">
                            <i data-lucide="map-pin" style="width:0.8125rem; height:0.8125rem;"></i>
                            {{ $community->city }}
                        </span>
                    </div>

                    @if($community->description)
                    <p style="font-size:0.875rem; color:var(--muted-foreground); margin:0; line-height:1.6;">
                        {{ $community->description }}
                    </p>
                    @endif

                    <div style="display:flex; align-items:center; gap:0.375rem;
                                font-size:0.8125rem; color:var(--muted-foreground);">
                        <i data-lucide="users" style="width:0.875rem; height:0.875rem;"></i>
                        {{ $community->members_count }}
                        {{ $community->members_count === 1 ? 'miembro' : 'miembros' }}
                    </div>

                    {{-- Unirse / Abandonar --}}
                    @auth
                        @if($isMember)
                        <form id="form-leave" method="POST" action="{{ route('comunidades.leave', $community) }}">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="btn btn-ghost"
                                    style="width:100%; font-size:0.875rem; color:var(--muted-foreground);"
                                    onclick="openModal({
                                        type: 'warning',
                                        title: 'Abandonar comunidad',
                                        body: '¿Seguro que quieres abandonar {{ addslashes($community->name) }}? Podrás volver a unirte cuando quieras.',
                                        confirmText: 'Sí, abandonar',
                                        formId: 'form-leave'
                                    })">
                                <i data-lucide="log-out" style="width:0.875rem; height:0.875rem;"></i>
                                Abandonar comunidad
                            </button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('comunidades.join', $community) }}"
                              style="width:100%;">
                            @csrf
                            <button type="submit" class="btn btn-primary"
                                    style="width:100%; font-size:0.875rem; display:flex; align-items:center; justify-content:center; gap:0.4rem;">
                                <i data-lucide="user-plus" style="width:0.875rem; height:0.875rem;"></i>
                                Unirme a la comunidad
                            </button>
                        </form>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Tarjeta miembros ---------------------------------------------- --}}
            <div style="background:var(--card); border:1px solid var(--border);
                        border-radius:var(--radius); padding:1.25rem;">
                <h2 style="font-size:0.9375rem; font-weight:600; margin:0 0 1rem;
                            display:flex; align-items:center; gap:0.5rem;">
                    <i data-lucide="users" style="width:1rem; height:1rem; color:var(--primary);"></i>
                    Miembros
                </h2>

                @if($members->isEmpty())
                <p style="font-size:0.875rem; color:var(--muted-foreground); margin:0;
                           text-align:center; padding:1.5rem 0;">
                    Aún no hay miembros.
                </p>
                @else
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach($members as $member)
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <div style="width:2.25rem; height:2.25rem; border-radius:50%; overflow:hidden;
                                    flex-shrink:0;
                                    background:color-mix(in oklch, var(--primary) 15%, transparent);
                                    display:flex; align-items:center; justify-content:center;">
                            @if($member->avatar)
                            <img src="{{ asset('storage/' . $member->avatar) }}"
                                 alt="{{ $member->nombre }}"
                                 style="width:100%; height:100%; object-fit:cover;" />
                            @else
                            <span style="font-size:0.8125rem; font-weight:600; color:var(--primary);">
                                {{ strtoupper(substr($member->nombre, 0, 1)) }}
                            </span>
                            @endif
                        </div>
                        <p style="font-size:0.875rem; font-weight:500; margin:0; min-width:0;
                                  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $member->nombre }}
                        </p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        {{-- ── Columna derecha: Chat ─────────────────────────────────────────── --}}
        <div style="background:var(--card); border:1px solid var(--border);
                    border-radius:var(--radius); display:flex; flex-direction:column;
                    min-height:28rem; max-height:75vh;">

            {{-- Cabecera --}}
            <div style="padding:1rem 1.25rem; border-bottom:1px solid var(--border);
                        display:flex; align-items:center; gap:0.625rem; flex-shrink:0;">
                <i data-lucide="message-square" style="width:1.125rem; height:1.125rem; color:var(--primary);"></i>
                <span style="font-size:0.9375rem; font-weight:600;">Chat de la comunidad</span>
                <span style="margin-left:auto; font-size:0.75rem; color:var(--muted-foreground);">
                    {{ $messages->count() }} {{ $messages->count() === 1 ? 'mensaje' : 'mensajes' }}
                </span>
            </div>

            {{-- Mensajes --}}
            <div id="chat-messages"
                 style="flex:1; overflow-y:auto; padding:1.25rem;
                        display:flex; flex-direction:column; gap:1rem;">

                @if($messages->isEmpty())
                <div style="display:flex; flex-direction:column; align-items:center;
                            justify-content:center; flex:1; gap:0.875rem;
                            color:var(--muted-foreground); text-align:center; padding:2rem 1rem;">
                    <i data-lucide="message-circle" style="width:2.5rem; height:2.5rem; opacity:0.35;"></i>
                    <div>
                        <p style="font-weight:500; margin:0 0 0.25rem; font-size:0.9375rem;">Sin mensajes aún</p>
                        <p style="font-size:0.875rem; margin:0; opacity:0.75;">
                            @if($isMember)
                                ¡Sé el primero en escribir algo!
                            @else
                                Únete para participar en la conversación.
                            @endif
                        </p>
                    </div>
                </div>

                @else
                @foreach($messages as $msg)
                @php
                    $isOwn = auth()->check() && auth()->id() === $msg->user_id;

                    // Solo puede eliminar: el moderador de esta comunidad concretamente
                    $canDelete = auth()->check()
                        && $community->memberRole(auth()->user()) === 'moderator';
                @endphp

                <div style="display:flex; gap:0.75rem; {{ $isOwn ? 'flex-direction:row-reverse;' : '' }}">

                    {{-- Avatar --}}
                    <div style="width:2rem; height:2rem; border-radius:50%; overflow:hidden; flex-shrink:0;
                                background:color-mix(in oklch, var(--primary) 15%, transparent);
                                display:flex; align-items:center; justify-content:center;">
                        @if($msg->user?->avatar)
                        <img src="{{ asset('storage/' . $msg->user->avatar) }}"
                             alt="{{ $msg->user->nombre }}"
                             style="width:100%; height:100%; object-fit:cover;" />
                        @else
                        <span style="font-size:0.75rem; font-weight:600; color:var(--primary);">
                            {{ strtoupper(substr($msg->user?->nombre ?? '?', 0, 1)) }}
                        </span>
                        @endif
                    </div>

                    {{-- Burbuja --}}
                    <div style="max-width:72%; display:flex; flex-direction:column;
                                {{ $isOwn ? 'align-items:flex-end;' : 'align-items:flex-start;' }}">

                        <div style="display:flex; align-items:baseline; gap:0.5rem; margin-bottom:0.25rem;
                                    {{ $isOwn ? 'flex-direction:row-reverse;' : '' }}">
                            <span style="font-size:0.8125rem; font-weight:600;">
                                {{ $msg->user?->nombre ?? 'Usuario eliminado' }}
                            </span>
                            <span style="font-size:0.6875rem; color:var(--muted-foreground);">
                                {{ $msg->created_at->format('d/m H:i') }}
                            </span>
                        </div>

                        <div style="padding:0.625rem 0.875rem; border-radius:0.75rem;
                                    font-size:0.875rem; line-height:1.55; word-break:break-word;
                                    {{ $isOwn
                                        ? 'background:var(--primary); color:var(--primary-foreground); border-bottom-right-radius:0.25rem;'
                                        : 'background:var(--secondary); color:var(--foreground); border-bottom-left-radius:0.25rem;' }}">
                            {{ $msg->message }}
                        </div>

                        {{-- Botón eliminar: solo autor o moderador de la comunidad --}}
                        @if($canDelete)
                        <form id="form-del-{{ $msg->id }}"
                              method="POST"
                              action="{{ route('comunidades.messages.destroy', [$community, $msg]) }}"
                              style="margin-top:0.3rem;">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    style="background:none; border:none; cursor:pointer; font-size:0.75rem;
                                           color:var(--muted-foreground); padding:0;
                                           display:flex; align-items:center; gap:0.25rem; font-family:inherit;"
                                    onmouseenter="this.style.color='var(--destructive)'"
                                    onmouseleave="this.style.color='var(--muted-foreground)'"
                                    onclick="openModal({
                                        type: 'danger',
                                        title: 'Eliminar mensaje',
                                        body: '¿Seguro que quieres eliminar este mensaje? Esta acción no se puede deshacer.',
                                        confirmText: 'Sí, eliminar',
                                        formId: 'form-del-{{ $msg->id }}'
                                    })">
                                <i data-lucide="trash-2" style="width:0.75rem; height:0.75rem;"></i>
                                Eliminar
                            </button>
                        </form>
                        @endif

                    </div>
                </div>
                @endforeach
                @endif
            </div>

            {{-- Barra de escritura --}}
            <div style="padding:1rem 1.25rem; border-top:1px solid var(--border); flex-shrink:0;">
                @auth
                    @if($isMember)
                    <form method="POST" action="{{ route('comunidades.messages.store', $community) }}"
                          style="display:flex; gap:0.625rem; align-items:flex-end;">
                        @csrf
                        <textarea name="message"
                                  rows="1"
                                  placeholder="Escribe un mensaje… (máx. 1000 caracteres)"
                                  maxlength="1000"
                                  required
                                  style="flex:1; resize:none; padding:0.625rem 0.875rem;
                                         border:1px solid var(--border); border-radius:var(--radius);
                                         background:var(--secondary); color:var(--foreground);
                                         font-size:0.875rem; font-family:inherit; outline:none;
                                         line-height:1.5; min-height:2.5rem; max-height:8rem;"
                                  onfocus="this.style.borderColor='var(--ring)'"
                                  onblur="this.style.borderColor='var(--border)'"
                                  oninput="this.style.height='auto'; this.style.height=this.scrollHeight+'px'">{{ old('message') }}</textarea>
                        <button type="submit" class="btn btn-primary"
                                style="padding:0.625rem 1rem; flex-shrink:0; align-self:flex-end;">
                            <i data-lucide="send" style="width:1rem; height:1rem;"></i>
                        </button>
                    </form>
                    @else
                    <div style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.125rem;
                                background:color-mix(in oklch, var(--accent) 8%, transparent);
                                border:1px solid color-mix(in oklch, var(--accent) 25%, transparent);
                                border-radius:var(--radius); font-size:0.875rem; color:var(--muted-foreground);">
                        <i data-lucide="lock" style="width:1rem; height:1rem; flex-shrink:0;"></i>
                        <span>Únete a la comunidad para participar en el chat.</span>
                        <form method="POST" action="{{ route('comunidades.join', $community) }}" style="margin-left:auto; flex-shrink:0;">
                            @csrf
                            <button type="submit" class="btn btn-primary"
                                    style="font-size:0.8125rem; padding:0.4375rem 0.875rem; white-space:nowrap;">
                                <i data-lucide="user-plus" style="width:0.8125rem; height:0.8125rem;"></i>
                                Unirme
                            </button>
                        </form>
                    </div>
                    @endif
                @else
                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1.125rem;
                            background:color-mix(in oklch, var(--accent) 8%, transparent);
                            border:1px solid color-mix(in oklch, var(--accent) 25%, transparent);
                            border-radius:var(--radius); font-size:0.875rem; color:var(--muted-foreground);">
                    <i data-lucide="log-in" style="width:1rem; height:1rem; flex-shrink:0;"></i>
                    <span>
                        <a href="{{ route('login') }}"
                           style="color:var(--primary); font-weight:500; text-decoration:none;">Inicia sesión</a>
                        para unirte y participar en el chat.
                    </span>
                </div>
                @endauth
            </div>

        </div>
    </div>
</div>

{{-- ─── Estilos responsive ─────────────────────────────────────────────────── --}}
<style>
@media (max-width: 768px) {
    .community-layout {
        grid-template-columns: 1fr !important;
    }
    .community-layout > div:last-child {
        min-height: 20rem !important;
        max-height: 60vh !important;
    }
}
@media (max-width: 480px) {
    #chat-messages {
        padding: 0.875rem !important;
    }
}
</style>

{{-- ─── Modal JS ────────────────────────────────────────────────────────────── --}}
<script>
(function () {
    const modal       = document.getElementById('urbex-modal');
    const modalTitle  = document.getElementById('modal-title');
    const modalBody   = document.getElementById('modal-body');
    const modalIcon   = document.getElementById('modal-icon-wrap');
    const btnConfirm  = document.getElementById('modal-confirm');
    const btnCancel   = document.getElementById('modal-cancel');

    let pendingFormId = null;

    window.openModal = function ({ type, title, body, confirmText, formId }) {
        pendingFormId = formId;

        modalTitle.textContent  = title;
        modalBody.textContent   = body;
        btnConfirm.textContent  = confirmText || 'Confirmar';

        // Estilo del icono y botón según tipo
        if (type === 'danger') {
            modalIcon.style.background = 'color-mix(in oklch, var(--destructive) 12%, transparent)';
            modalIcon.innerHTML = '<i data-lucide="trash-2" style="width:1.25rem;height:1.25rem;color:var(--destructive);"></i>';
            btnConfirm.style.background = 'var(--destructive)';
            btnConfirm.style.color      = '#fff';
        } else {
            modalIcon.style.background = 'color-mix(in oklch, var(--accent) 14%, transparent)';
            modalIcon.innerHTML = '<i data-lucide="log-out" style="width:1.25rem;height:1.25rem;color:var(--accent);"></i>';
            btnConfirm.style.background = 'var(--primary)';
            btnConfirm.style.color      = 'var(--primary-foreground)';
        }

        // Re-inicializar Lucide para los iconos recién insertados
        if (window.lucide) lucide.createIcons();

        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    };

    function closeModal () {
        modal.style.display = 'none';
        document.body.style.overflow = '';
        pendingFormId = null;
    }

    btnConfirm.addEventListener('click', function () {
        if (pendingFormId) {
            document.getElementById(pendingFormId)?.submit();
        }
        closeModal();
    });

    btnCancel.addEventListener('click', closeModal);

    // Cerrar al hacer click fuera del cuadro
    modal.addEventListener('click', function (e) {
        if (e.target === modal) closeModal();
    });

    // Cerrar con Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    // Scroll al fondo del chat
    document.addEventListener('DOMContentLoaded', function () {
        const chat = document.getElementById('chat-messages');
        if (chat) chat.scrollTop = chat.scrollHeight;
    });
})();
</script>
@endsection
