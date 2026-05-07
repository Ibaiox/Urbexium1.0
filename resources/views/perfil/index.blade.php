{{-- resources/views/perfil/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Mi Perfil')

@php
function paginacionEstilizada($paginator) {
    // Esta función no funciona en blade, usamos el componente inline
}
@endphp

@section('content')
<div style="display:flex; flex-direction:column; gap:1.5rem; max-width:1400px;">

    {{-- Flash --}}
    @if(session('success'))
    <div style="
        display:flex; align-items:center; gap:0.75rem; padding:0.875rem 1rem;
        border-radius:var(--radius); background:color-mix(in oklch, var(--primary) 12%, transparent);
        border:1px solid color-mix(in oklch, var(--primary) 30%, transparent); color:var(--primary); font-size:0.875rem;
    "><i data-lucide="check-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>{{ session('success') }}</div>
    @endif

    {{-- ===== PERFIL HERO ===== --}}
    <div class="card" style="overflow:hidden;">
        {{-- Banner --}}
        <div style="
            height:8rem; position:relative;
            background:linear-gradient(135deg,
                color-mix(in oklch, var(--primary) 30%, transparent),
                color-mix(in oklch, var(--accent) 20%, transparent)
            );
        ">
            <svg style="position:absolute;inset:0;width:100%;height:100%;opacity:0.12;" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="24" height="24" patternUnits="userSpaceOnUse">
                        <path d="M 24 0 L 0 0 0 24" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>
            <button
                onclick="showTab('configuracion')"
                style="
                    position:absolute; top:1rem; right:1rem;
                    display:flex; align-items:center; gap:0.5rem;
                    padding:0.5rem 1rem; border-radius:var(--radius);
                    background:rgba(0,0,0,0.35); backdrop-filter:blur(4px);
                    border:1px solid rgba(255,255,255,0.2); color:#fff;
                    font-size:0.8125rem; font-weight:500; cursor:pointer;
                "
                onmouseover="this.style.background='rgba(0,0,0,0.55)'"
                onmouseout="this.style.background='rgba(0,0,0,0.35)'"
            >
                <i data-lucide="settings" style="width:0.875rem;height:0.875rem;"></i>
                Editar perfil
            </button>
        </div>

        <div class="card-content" style="padding-top:0;">
            <div style="display:flex; flex-wrap:wrap; align-items:flex-end; justify-content:space-between; gap:1rem; margin-top:-3rem; padding-top:0;">
                <div style="display:flex; align-items:flex-end; gap:1rem; flex-wrap:wrap;">
                    <div style="position:relative;">
                        <div style="width:6rem; height:6rem; border-radius:50%; border:4px solid var(--card); overflow:hidden; background:var(--secondary);">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->nombre }}" style="width:100%;height:100%;object-fit:cover;" />
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--primary);color:var(--primary-foreground);font-size:2rem;font-weight:700;">
                                    {{ strtoupper(substr($user->nombre, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        @if($user->rol->nombre === 'admin')
                        <span style="position:absolute; bottom:0; right:-0.25rem; display:flex; align-items:center; justify-content:center; width:1.75rem; height:1.75rem; border-radius:50%; background:var(--accent); border:2px solid var(--card);">
                            <i data-lucide="shield" style="width:0.875rem;height:0.875rem;color:var(--accent-foreground);"></i>
                        </span>
                        @endif
                    </div>
                    <div style="margin-bottom:0.25rem;">
                        <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
                            <h1 style="font-size:1.375rem; font-weight:700; margin:0;">{{ $user->nombre }}</h1>
                            @if($user->baneado)
                            <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.2rem 0.625rem;border-radius:9999px;background:color-mix(in oklch, var(--destructive) 12%, transparent);color:var(--destructive);font-size:0.75rem;font-weight:600;">
                                <i data-lucide="ban" style="width:0.75rem;height:0.75rem;"></i>Baneado
                            </span>
                            @else
                            <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.2rem 0.625rem;border-radius:9999px;background:color-mix(in oklch, var(--primary) 12%, transparent);color:var(--primary);font-size:0.75rem;font-weight:600;">
                                <i data-lucide="check" style="width:0.75rem;height:0.75rem;"></i>Activo
                            </span>
                            @endif
                        </div>
                        <p style="color:var(--muted-foreground); font-size:0.875rem; margin:0.25rem 0 0;">{{ $user->email }}</p>
                        @if($user->bio)
                        <p style="font-size:0.875rem; margin:0.5rem 0 0; max-width:36rem; line-height:1.6;">{{ $user->bio }}</p>
                        @endif
                        <div style="display:flex; align-items:center; gap:1rem; margin-top:0.75rem; flex-wrap:wrap;">
                            <span style="display:flex;align-items:center;gap:0.375rem;font-size:0.8125rem;color:var(--muted-foreground);">
                                <i data-lucide="calendar" style="width:0.875rem;height:0.875rem;"></i>
                                Miembro desde {{ $user->created_at->translatedFormat('F Y') }}
                            </span>
                            <span style="display:flex;align-items:center;gap:0.375rem;font-size:0.8125rem;color:var(--muted-foreground);">
                                <i data-lucide="map-pin" style="width:0.875rem;height:0.875rem;"></i>
                                {{ $spotsCount ?? 0 }} spots creados
                            </span>
                        </div>
                    </div>
                </div>
                <div style="display:flex; gap:0.75rem; flex-wrap:wrap; margin-bottom:0.25rem;">
                    @php
                        $stats = [
                            ['icon' => 'map-pin',     'val' => $spotsCount ?? 0,     'label' => 'Spots'],
                            ['icon' => 'heart',       'val' => $favoritosCount ?? 0, 'label' => 'Favoritos'],
                            ['icon' => 'shopping-bag','val' => $pedidosCount ?? 0,   'label' => 'Pedidos'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                    <div style="display:flex;flex-direction:column;align-items:center;padding:0.75rem 1.25rem;border-radius:var(--radius);background:var(--secondary);border:1px solid var(--border);min-width:5rem;">
                        <span style="font-size:1.375rem; font-weight:700;">{{ $s['val'] }}</span>
                        <span style="font-size:0.75rem; color:var(--muted-foreground); margin-top:0.125rem;">{{ $s['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ===== TABS NAV ===== --}}
    <div style="display:flex;gap:0.25rem;flex-wrap:wrap;background:var(--secondary);padding:0.375rem;border-radius:var(--radius);border:1px solid var(--border);">
        @php
            $tabs = [
                ['id' => 'spots',          'icon' => 'map-pin',      'label' => 'Mis Spots'],
                ['id' => 'favoritos',      'icon' => 'heart',        'label' => 'Favoritos'],
                ['id' => 'pedidos',        'icon' => 'shopping-bag', 'label' => 'Pedidos'],
                ['id' => 'notificaciones', 'icon' => 'bell',         'label' => 'Notificaciones'],
                ['id' => 'configuracion',  'icon' => 'settings',     'label' => 'Configuración'],
            ];
        @endphp
        @foreach($tabs as $tab)
        <button
            id="tab-btn-{{ $tab['id'] }}"
            onclick="showTab('{{ $tab['id'] }}')"
            style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;border-radius:calc(var(--radius) - 4px);border:none;cursor:pointer;font-size:0.875rem;font-weight:500;transition:all 150ms;white-space:nowrap;background:transparent;color:var(--muted-foreground);"
        >
            <i data-lucide="{{ $tab['icon'] }}" style="width:0.875rem;height:0.875rem;"></i>
            {{ $tab['label'] }}
            @if($tab['id'] === 'notificaciones' && isset($notificacionesCount) && $notificacionesCount > 0)
            <span style="min-width:1.25rem;height:1.25rem;padding:0 0.25rem;border-radius:9999px;background:var(--destructive);color:#fff;font-size:0.7rem;font-weight:700;display:inline-flex;align-items:center;justify-content:center;">{{ $notificacionesCount }}</span>
            @endif
        </button>
        @endforeach
    </div>

    {{-- ===== TAB: MIS SPOTS ===== --}}
    <div id="tab-spots" class="tab-content" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1rem;">
            <h2 style="font-size:1.0625rem;font-weight:600;margin:0;">Spots que has creado</h2>
            <a href="{{ route('spots.create') }}" class="btn btn-primary" style="font-size:0.8125rem;">
                <i data-lucide="plus" style="width:0.875rem;height:0.875rem;"></i> Añadir spot
            </a>
        </div>

        @if(isset($misSpots) && $misSpots->count())
        <div style="display:grid;gap:1rem;grid-template-columns:repeat(auto-fill, minmax(16rem,1fr));">
            @foreach($misSpots as $spot)
            <a href="{{ route('spots.show', $spot) }}" style="text-decoration:none;color:inherit;">
                <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:all 200ms;"
                    onmouseover="this.style.borderColor='color-mix(in oklch, var(--primary) 50%, transparent)';this.style.boxShadow='0 4px 16px color-mix(in oklch, var(--primary) 8%, transparent)'"
                    onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    <div style="position:relative;height:10rem;background:var(--secondary);overflow:hidden;">
                        @if($spot->imagenes->first())
                        <img src="{{ asset('storage/'.$spot->imagenes->first()->url) }}" alt="{{ $spot->nombre }}" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <i data-lucide="image" style="width:2rem;height:2rem;color:var(--muted-foreground);opacity:0.4;"></i>
                        </div>
                        @endif
                        @php
                            $statusColors = [
                                'pendiente'  => ['bg'=>'rgba(234,179,8,0.85)',  'text'=>'#000','icon'=>'clock'],
                                'verificada' => ['bg'=>'rgba(34,197,94,0.85)',  'text'=>'#000','icon'=>'check-circle'],
                                'rechazada'  => ['bg'=>'rgba(239,68,68,0.85)',  'text'=>'#fff','icon'=>'x-circle'],
                                'dudosa'     => ['bg'=>'rgba(249,115,22,0.85)', 'text'=>'#fff','icon'=>'alert-triangle'],
                            ];
                            $sc = $statusColors[$spot->verification_status] ?? $statusColors['pendiente'];
                        @endphp
                        <span style="position:absolute;top:0.5rem;right:0.5rem;display:inline-flex;align-items:center;gap:0.375rem;padding:0.2rem 0.6rem;border-radius:9999px;background:{{ $sc['bg'] }};color:{{ $sc['text'] }};font-size:0.7rem;font-weight:600;text-transform:capitalize;">
                            <i data-lucide="{{ $sc['icon'] }}" style="width:0.7rem;height:0.7rem;"></i>
                            {{ $spot->verification_status }}
                        </span>
                        <span style="position:absolute;bottom:0.5rem;left:0.5rem;padding:0.2rem 0.6rem;border-radius:9999px;background:rgba(0,0,0,0.55);color:#fff;font-size:0.7rem;font-weight:500;text-transform:capitalize;">{{ $spot->dificultad }}</span>
                    </div>
                    <div style="padding:0.875rem;">
                        <p style="font-weight:600;font-size:0.9375rem;margin:0 0 0.25rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $spot->nombre }}</p>
                        <div style="display:flex;align-items:center;gap:0.375rem;font-size:0.8125rem;color:var(--muted-foreground);">
                            <i data-lucide="map-pin" style="width:0.75rem;height:0.75rem;"></i>
                            {{ $spot->ciudad->nombre ?? '—' }}
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @include('partials.paginacion', ['paginator' => $misSpots])
        @else
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;text-align:center;color:var(--muted-foreground);">
            <i data-lucide="map-pin" style="width:3rem;height:3rem;margin-bottom:1rem;opacity:0.4;"></i>
            <h3 style="font-size:1.0625rem;font-weight:500;margin:0 0 0.375rem;">Aún no has creado ningún spot</h3>
            <p style="font-size:0.875rem;margin:0 0 1.25rem;">Comparte tus descubrimientos con la comunidad urbexer.</p>
            <a href="{{ route('spots.create') }}" class="btn btn-primary">Crear primer spot</a>
        </div>
        @endif
    </div>

    {{-- ===== TAB: FAVORITOS ===== --}}
    <div id="tab-favoritos" class="tab-content" style="display:none;">
        <h2 style="font-size:1.0625rem;font-weight:600;margin:0 0 1rem;">Spots guardados</h2>

        @if(isset($favoritos) && $favoritos->count())
        <div style="display:grid;gap:1rem;grid-template-columns:repeat(auto-fill, minmax(16rem,1fr));">
            @foreach($favoritos as $spot)
            <a href="{{ route('spots.show', $spot) }}" style="text-decoration:none;color:inherit;">
                <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;transition:all 200ms;"
                    onmouseover="this.style.borderColor='color-mix(in oklch, var(--primary) 50%, transparent)';this.style.boxShadow='0 4px 16px color-mix(in oklch, var(--primary) 8%, transparent)'"
                    onmouseout="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    <div style="position:relative;height:10rem;background:var(--secondary);overflow:hidden;">
                        @if($spot->imagenes->first())
                        <img src="{{ asset('storage/'.$spot->imagenes->first()->url) }}" alt="{{ $spot->nombre }}" style="width:100%;height:100%;object-fit:cover;" />
                        @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                            <i data-lucide="image" style="width:2rem;height:2rem;color:var(--muted-foreground);opacity:0.4;"></i>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('spots.fav', $spot) }}" style="position:absolute;top:0.5rem;right:0.5rem;">
                            @csrf @method('DELETE')
                            <button type="submit" style="width:2rem;height:2rem;border-radius:50%;background:rgba(0,0,0,0.5);backdrop-filter:blur(4px);border:none;cursor:pointer;color:var(--destructive);display:flex;align-items:center;justify-content:center;">
                                <i data-lucide="heart" style="width:1rem;height:1rem;fill:currentColor;"></i>
                            </button>
                        </form>
                    </div>
                    <div style="padding:0.875rem;">
                        <p style="font-weight:600;font-size:0.9375rem;margin:0 0 0.25rem;">{{ $spot->nombre }}</p>
                        <div style="display:flex;align-items:center;gap:0.375rem;font-size:0.8125rem;color:var(--muted-foreground);">
                            <i data-lucide="map-pin" style="width:0.75rem;height:0.75rem;"></i>
                            {{ $spot->ciudad->nombre ?? '—' }}
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @include('partials.paginacion', ['paginator' => $favoritos])
        @else
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;text-align:center;color:var(--muted-foreground);">
            <i data-lucide="heart" style="width:3rem;height:3rem;margin-bottom:1rem;opacity:0.4;"></i>
            <h3 style="font-size:1.0625rem;font-weight:500;margin:0 0 0.375rem;">No tienes favoritos todavía</h3>
            <p style="font-size:0.875rem;margin:0 0 1.25rem;">Guarda spots que te interesen para encontrarlos fácilmente.</p>
            <a href="{{ route('spots.index') }}" class="btn btn-primary">Explorar spots</a>
        </div>
        @endif
    </div>

    {{-- ===== TAB: PEDIDOS ===== --}}
    <div id="tab-pedidos" class="tab-content" style="display:none;">
        <h2 style="font-size:1.0625rem;font-weight:600;margin:0 0 1rem;">Historial de pedidos</h2>

        @if(isset($pedidos) && $pedidos->count())
        <div style="display:flex;flex-direction:column;gap:0.875rem;">
            @foreach($pedidos as $pedido)
            @php
                $pedidoColors = [
                    'pendiente'  => ['color'=>'var(--accent)',        'icon'=>'clock'],
                    'procesando' => ['color'=>'var(--primary)',       'icon'=>'refresh-cw'],
                    'enviado'    => ['color'=>'oklch(0.6 0.15 200)',  'icon'=>'truck'],
                    'entregado'  => ['color'=>'oklch(0.55 0.15 145)','icon'=>'check-circle'],
                    'cancelado'  => ['color'=>'var(--destructive)',   'icon'=>'x-circle'],
                ];
                $pc = $pedidoColors[$pedido->estado] ?? $pedidoColors['pendiente'];
            @endphp
            <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;display:flex;flex-wrap:wrap;align-items:flex-start;gap:1rem;justify-content:space-between;">
                <div style="display:flex;flex-direction:column;gap:0.375rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                        <span style="font-weight:600;font-size:0.9375rem;">Pedido #{{ $pedido->id }}</span>
                        <span style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.2rem 0.6rem;border-radius:9999px;background:color-mix(in oklch, {{ $pc['color'] }} 15%, transparent);color:{{ $pc['color'] }};font-size:0.75rem;font-weight:600;text-transform:capitalize;">
                            <i data-lucide="{{ $pc['icon'] }}" style="width:0.75rem;height:0.75rem;"></i>
                            {{ $pedido->estado }}
                        </span>
                    </div>
                    <p style="font-size:0.8125rem;color:var(--muted-foreground);margin:0;">{{ $pedido->created_at->format('d/m/Y') }}</p>
                    @if(isset($pedido->items) && $pedido->items->count())
                    <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.25rem;">
                        @foreach($pedido->items->take(3) as $item)
                        <span style="padding:0.2rem 0.6rem;border-radius:9999px;background:var(--secondary);font-size:0.75rem;">{{ $item->producto->nombre ?? '—' }} ×{{ $item->cantidad }}</span>
                        @endforeach
                        @if($pedido->items->count() > 3)
                        <span style="padding:0.2rem 0.6rem;border-radius:9999px;background:var(--secondary);font-size:0.75rem;color:var(--muted-foreground);">+{{ $pedido->items->count() - 3 }} más</span>
                        @endif
                    </div>
                    @endif
                </div>
                <div style="text-align:right;">
                    <span style="font-size:1.125rem;font-weight:700;">{{ number_format($pedido->total, 2) }} €</span><br>
                    <a href="{{ route('tienda.pedidos.show', $pedido) }}" style="font-size:0.8125rem;color:var(--primary);text-decoration:none;">Ver detalle →</a>
                </div>
            </div>
            @endforeach
        </div>
        @include('partials.paginacion', ['paginator' => $pedidos])
        @else
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;text-align:center;color:var(--muted-foreground);">
            <i data-lucide="shopping-bag" style="width:3rem;height:3rem;margin-bottom:1rem;opacity:0.4;"></i>
            <h3 style="font-size:1.0625rem;font-weight:500;margin:0 0 0.375rem;">Sin pedidos todavía</h3>
            <p style="font-size:0.875rem;margin:0 0 1.25rem;">Visita la tienda y hazte con el mejor equipo para explorar.</p>
            <a href="{{ route('tienda.index') }}" class="btn btn-primary">Ir a la tienda</a>
        </div>
        @endif
    </div>

    {{-- ===== TAB: NOTIFICACIONES ===== --}}
    <div id="tab-notificaciones" class="tab-content" style="display:none;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:0.75rem;margin-bottom:1rem;">
            <h2 style="font-size:1.0625rem;font-weight:600;margin:0;">Notificaciones y mensajes</h2>
            @if(isset($notificaciones) && $notificaciones->count())
            <form method="POST" action="{{ route('notificaciones.markAllRead') }}">
                @csrf @method('PATCH')
                <button type="submit" style="display:flex;align-items:center;gap:0.375rem;padding:0.4rem 0.875rem;border-radius:var(--radius);border:1px solid var(--border);background:var(--card);font-size:0.8125rem;cursor:pointer;color:var(--foreground);">
                    <i data-lucide="check-check" style="width:0.875rem;height:0.875rem;"></i>
                    Marcar todo como leído
                </button>
            </form>
            @endif
        </div>

        @if(isset($notificaciones) && $notificaciones->count())
        <div style="display:flex;flex-direction:column;gap:0.625rem;">
            @foreach($notificaciones as $notif)
            @php
                $notifTypes = [
                    'spot_verificado' => ['icon'=>'check-circle',   'color'=>'var(--primary)',      'bg'=>'var(--primary)'],
                    'spot_rechazado'  => ['icon'=>'x-circle',       'color'=>'var(--destructive)',  'bg'=>'var(--destructive)'],
                    'spot_pendiente'  => ['icon'=>'clock',          'color'=>'var(--accent)',       'bg'=>'var(--accent)'],
                    'sancion'         => ['icon'=>'alert-triangle', 'color'=>'var(--destructive)',  'bg'=>'var(--destructive)'],
                    'aviso'           => ['icon'=>'alert-circle',   'color'=>'var(--accent)',       'bg'=>'var(--accent)'],
                    'ban'             => ['icon'=>'ban',            'color'=>'var(--destructive)',  'bg'=>'var(--destructive)'],
                    'info'            => ['icon'=>'info',           'color'=>'oklch(0.6 0.15 200)', 'bg'=>'oklch(0.6 0.15 200)'],
                ];
                $nt = $notifTypes[$notif->tipo] ?? $notifTypes['info'];
            @endphp
            <div style="display:flex;gap:1rem;align-items:flex-start;padding:1rem 1.25rem;border-radius:var(--radius);background:{{ $notif->leida ? 'var(--card)' : 'color-mix(in oklch, var(--primary) 5%, var(--card))' }};border:1px solid {{ $notif->leida ? 'var(--border)' : 'color-mix(in oklch, var(--primary) 25%, transparent)' }};">
                <div style="display:flex;align-items:center;justify-content:center;width:2.5rem;height:2.5rem;border-radius:50%;flex-shrink:0;background:color-mix(in oklch, {{ $nt['bg'] }} 15%, transparent);">
                    <i data-lucide="{{ $nt['icon'] }}" style="width:1.125rem;height:1.125rem;color:{{ $nt['color'] }};"></i>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:0.75rem;flex-wrap:wrap;">
                        <div>
                            <p style="font-weight:{{ $notif->leida ? '400' : '600' }};font-size:0.9375rem;margin:0 0 0.25rem;">{{ $notif->titulo }}</p>
                            <p style="font-size:0.8125rem;color:var(--muted-foreground);margin:0;line-height:1.5;">{{ $notif->mensaje }}</p>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;flex-shrink:0;">
                            <span style="font-size:0.75rem;color:var(--muted-foreground);white-space:nowrap;">{{ $notif->created_at->diffForHumans() }}</span>
                            @if(!$notif->leida)
                            <form method="POST" action="{{ route('notificaciones.markRead', $notif) }}">
                                @csrf @method('PATCH')
                                <button type="submit" style="font-size:0.75rem;color:var(--primary);background:none;border:none;cursor:pointer;text-decoration:underline;padding:0;">Marcar leída</button>
                            </form>
                            @else
                            <span style="display:inline-flex;align-items:center;gap:0.25rem;font-size:0.7rem;color:var(--muted-foreground);">
                                <i data-lucide="check" style="width:0.7rem;height:0.7rem;"></i>Leída
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @include('partials.paginacion', ['paginator' => $notificaciones])
        @else
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;text-align:center;color:var(--muted-foreground);">
            <i data-lucide="bell" style="width:3rem;height:3rem;margin-bottom:1rem;opacity:0.4;"></i>
            <h3 style="font-size:1.0625rem;font-weight:500;margin:0 0 0.375rem;">Sin notificaciones</h3>
            <p style="font-size:0.875rem;margin:0;">Aquí aparecerán tus avisos, verificaciones y mensajes del equipo.</p>
        </div>
        @endif
    </div>

    {{-- ===== TAB: CONFIGURACIÓN ===== --}}
    <div id="tab-configuracion" class="tab-content" style="display:none;">
        <div style="display:grid;gap:1.5rem;grid-template-columns:repeat(auto-fit, minmax(22rem, 1fr));">

            {{-- Datos personales --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex;align-items:center;gap:0.625rem;">
                        <i data-lucide="user" style="width:1.125rem;height:1.125rem;color:var(--primary);"></i>
                        Datos personales
                    </h2>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:1.125rem;">
                        @csrf @method('PATCH')
                        <div style="display:flex;flex-direction:column;gap:0.5rem;">
                            <label style="font-size:0.875rem;font-weight:500;">Foto de perfil</label>
                            <div style="display:flex;align-items:center;gap:1rem;">
                                <div style="width:3.5rem;height:3.5rem;border-radius:50%;overflow:hidden;background:var(--secondary);flex-shrink:0;">
                                    @if($user->avatar)
                                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;" />
                                    @else
                                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:var(--primary);color:var(--primary-foreground);font-weight:700;">
                                        {{ strtoupper(substr($user->nombre, 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                                <label style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 0.875rem;border-radius:var(--radius);border:1px solid var(--border);background:var(--secondary);font-size:0.8125rem;cursor:pointer;font-weight:500;"
                                    onmouseover="this.style.borderColor='var(--ring)'" onmouseout="this.style.borderColor='var(--border)'">
                                    <i data-lucide="upload" style="width:0.875rem;height:0.875rem;"></i>
                                    Cambiar foto
                                    <input type="file" name="avatar" accept="image/*" style="display:none;" />
                                </label>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.375rem;">
                            <label for="nombre" style="font-size:0.875rem;font-weight:500;">Nombre de usuario</label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $user->nombre) }}"
                                style="height:2.625rem;padding:0 0.875rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'" />
                            @error('nombre')<span style="font-size:0.75rem;color:var(--destructive);">{{ $message }}</span>@enderror
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.375rem;">
                            <label for="email" style="font-size:0.875rem;font-weight:500;">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                style="height:2.625rem;padding:0 0.875rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;outline:none;"
                                onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'" />
                            @error('email')<span style="font-size:0.75rem;color:var(--destructive);">{{ $message }}</span>@enderror
                        </div>
                        <div style="display:flex;flex-direction:column;gap:0.375rem;">
                            <label for="bio" style="font-size:0.875rem;font-weight:500;">Biografía</label>
                            <textarea id="bio" name="bio" rows="3" placeholder="Cuéntanos un poco sobre ti..."
                                style="padding:0.625rem 0.875rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;outline:none;resize:vertical;font-family:inherit;"
                                onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="align-self:flex-start;">
                            <i data-lucide="save" style="width:0.875rem;height:0.875rem;"></i>
                            Guardar cambios
                        </button>
                    </form>
                </div>
            </div>

            {{-- Seguridad --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex;align-items:center;gap:0.625rem;">
                        <i data-lucide="lock" style="width:1.125rem;height:1.125rem;color:var(--primary);"></i>
                        Seguridad
                    </h2>
                </div>
                <div class="card-content">
                    <form method="POST" action="{{ route('perfil.password') }}" style="display:flex;flex-direction:column;gap:1.125rem;">
                        @csrf @method('PATCH')
                        @foreach([
                            ['id'=>'current_password', 'label'=>'Contraseña actual',   'name'=>'current_password'],
                            ['id'=>'new_password',     'label'=>'Nueva contraseña',     'name'=>'password'],
                            ['id'=>'confirm_password', 'label'=>'Confirmar contraseña', 'name'=>'password_confirmation'],
                        ] as $field)
                        <div style="display:flex;flex-direction:column;gap:0.375rem;">
                            <label for="{{ $field['id'] }}" style="font-size:0.875rem;font-weight:500;">{{ $field['label'] }}</label>
                            <div style="position:relative;">
                                <input type="password" id="{{ $field['id'] }}" name="{{ $field['name'] }}"
                                    style="width:100%;height:2.625rem;padding:0 2.5rem 0 0.875rem;border:1px solid var(--border);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;outline:none;box-sizing:border-box;"
                                    onfocus="this.style.borderColor='var(--ring)'" onblur="this.style.borderColor='var(--border)'" />
                                <button type="button" onclick="togglePwd('{{ $field['id'] }}')" style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted-foreground);display:flex;align-items:center;">
                                    <i data-lucide="eye" style="width:1rem;height:1rem;"></i>
                                </button>
                            </div>
                            @error($field['name'])<span style="font-size:0.75rem;color:var(--destructive);">{{ $message }}</span>@enderror
                        </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary" style="align-self:flex-start;">
                            <i data-lucide="shield-check" style="width:0.875rem;height:0.875rem;"></i>
                            Cambiar contraseña
                        </button>
                    </form>
                </div>
            </div>

            {{-- Zona peligrosa --}}
            <div class="card" style="border-color:color-mix(in oklch, var(--destructive) 30%, transparent);">
                <div class="card-header">
                    <h2 class="card-title" style="display:flex;align-items:center;gap:0.625rem;color:var(--destructive);">
                        <i data-lucide="alert-triangle" style="width:1.125rem;height:1.125rem;"></i>
                        Zona peligrosa
                    </h2>
                </div>
                <div class="card-content" style="display:flex;flex-direction:column;gap:1rem;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding:1rem;border-radius:calc(var(--radius) - 2px);background:color-mix(in oklch, var(--destructive) 6%, transparent);border:1px solid color-mix(in oklch, var(--destructive) 20%, transparent);">
                        <div>
                            <p style="font-weight:600;font-size:0.9375rem;margin:0 0 0.25rem;">Eliminar cuenta</p>
                            <p style="font-size:0.8125rem;color:var(--muted-foreground);margin:0;line-height:1.5;">Se eliminarán permanentemente todos tus datos. Esta acción no puede deshacerse.</p>
                        </div>
                        <button onclick="document.getElementById('delete-account-modal').style.display='flex'" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;border-radius:var(--radius);background:var(--destructive);color:#fff;border:none;font-size:0.8125rem;font-weight:600;cursor:pointer;white-space:nowrap;">
                            <i data-lucide="trash-2" style="width:0.875rem;height:0.875rem;"></i>
                            Eliminar cuenta
                        </button>
                    </div>
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;padding:1rem;border-radius:calc(var(--radius) - 2px);background:var(--secondary);border:1px solid var(--border);">
                        <div>
                            <p style="font-weight:600;font-size:0.9375rem;margin:0 0 0.25rem;">Cerrar sesión</p>
                            <p style="font-size:0.8125rem;color:var(--muted-foreground);margin:0;">Cierra tu sesión en este dispositivo.</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" style="display:flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;border-radius:var(--radius);border:1px solid var(--border);background:var(--card);font-size:0.8125rem;font-weight:500;cursor:pointer;">
                                <i data-lucide="log-out" style="width:0.875rem;height:0.875rem;"></i>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

{{-- Modal eliminar cuenta --}}
<div id="delete-account-modal" style="display:none;position:fixed;inset:0;z-index:200;align-items:center;justify-content:center;background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);">
    <div style="background:var(--card);border:1px solid var(--border);border-radius:var(--radius);padding:2rem;max-width:26rem;width:calc(100% - 2rem);display:flex;flex-direction:column;gap:1.25rem;">
        <div style="text-align:center;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:50%;margin-bottom:1rem;background:color-mix(in oklch, var(--destructive) 12%, transparent);">
                <i data-lucide="trash-2" style="width:1.75rem;height:1.75rem;color:var(--destructive);"></i>
            </div>
            <h3 style="font-size:1.125rem;font-weight:700;margin:0 0 0.5rem;">¿Eliminar tu cuenta?</h3>
            <p style="font-size:0.875rem;color:var(--muted-foreground);margin:0;line-height:1.6;">Esta acción es permanente e irreversible. Escribe tu contraseña para confirmar.</p>
        </div>
        <form method="POST" action="{{ route('perfil.destroy') }}" style="display:flex;flex-direction:column;gap:1rem;">
            @csrf @method('DELETE')
            <input type="password" name="password" placeholder="Tu contraseña actual"
                style="height:2.625rem;padding:0 0.875rem;border:1px solid var(--destructive);border-radius:var(--radius);background:var(--card);color:var(--foreground);font-size:0.875rem;outline:none;"
                required />
            <div style="display:flex;gap:0.75rem;">
                <button type="button" onclick="document.getElementById('delete-account-modal').style.display='none'"
                    style="flex:1;height:2.5rem;border-radius:var(--radius);border:1px solid var(--border);background:transparent;font-size:0.875rem;cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit" style="flex:1;height:2.5rem;border-radius:var(--radius);border:none;background:var(--destructive);color:#fff;font-size:0.875rem;font-weight:600;cursor:pointer;">
                    Sí, eliminar cuenta
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const TABS = ['spots','favoritos','pedidos','notificaciones','configuracion'];

function showTab(id) {
    TABS.forEach(t => {
        const content = document.getElementById('tab-' + t);
        const btn     = document.getElementById('tab-btn-' + t);
        if (!content || !btn) return;
        const active = t === id;
        content.style.display   = active ? 'block' : 'none';
        btn.style.background    = active ? 'var(--card)' : 'transparent';
        btn.style.color         = active ? 'var(--foreground)' : 'var(--muted-foreground)';
        btn.style.boxShadow     = active ? '0 1px 4px rgba(0,0,0,0.08)' : 'none';
    });
    history.replaceState(null, '', '#' + id);
}

function togglePwd(id) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
}

document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    showTab(TABS.includes(hash) ? hash : 'spots');
});
</script>
@endsection
