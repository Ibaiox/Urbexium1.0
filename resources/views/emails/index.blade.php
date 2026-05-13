{{-- resources/views/contacto/index.blade.php --}}
@extends('layout.masterpage')

@section('title', 'Contacto')

@section('content')
<div style="max-width:700px; margin:0 auto;">

    {{-- Header --}}
    <div style="margin-bottom:2rem;">
        <h1 style="font-size:1.75rem; font-weight:700; letter-spacing:-0.02em; margin:0 0 0.375rem;">
            Contacto
        </h1>
        <p style="color:var(--muted-foreground); margin:0; font-size:0.9375rem;">
            ¿Tienes alguna duda, sugerencia o incidencia? Escríbenos y te respondemos.
        </p>
    </div>

    <div style="display:grid; gap:1.5rem; grid-template-columns:1fr 1.7fr; align-items:start;">

        {{-- Info lateral --}}
        <div style="display:flex; flex-direction:column; gap:1rem;">

            <div class="card" style="padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                    <div style="width:2.25rem; height:2.25rem; border-radius:50%; background:color-mix(in oklch, var(--primary) 15%, transparent); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i data-lucide="mail" style="width:1rem;height:1rem; color:var(--primary);"></i>
                    </div>
                    <strong style="font-size:0.9rem;">Email</strong>
                </div>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0; line-height:1.5;">
                    Respondemos en menos de 48 horas hábiles.
                </p>
            </div>

            <div class="card" style="padding:1.25rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:0.5rem;">
                    <div style="width:2.25rem; height:2.25rem; border-radius:50%; background:color-mix(in oklch, var(--accent) 15%, transparent); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i data-lucide="shield-alert" style="width:1rem;height:1rem; color:var(--accent);"></i>
                    </div>
                    <strong style="font-size:0.9rem;">Reportar un spot</strong>
                </div>
                <p style="font-size:0.8125rem; color:var(--muted-foreground); margin:0; line-height:1.5;">
                    Usa el botón "Reportar" en la página de cada spot para notificar contenido inapropiado.
                </p>
            </div>

        </div>

        {{-- Formulario --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Envíanos un mensaje</h2>
            </div>
            <div class="card-content">

                @if(session('success'))
                <div style="padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--primary) 12%, transparent); border:1px solid color-mix(in oklch, var(--primary) 30%, transparent); border-radius:var(--radius); color:var(--primary); font-size:0.875rem; display:flex; align-items:center; gap:0.5rem; margin-bottom:1.25rem;">
                    <i data-lucide="check-circle" style="width:1rem;height:1rem;flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div style="padding:0.875rem 1.25rem; background:color-mix(in oklch, var(--destructive) 10%, transparent); border:1px solid color-mix(in oklch, var(--destructive) 30%, transparent); border-radius:var(--radius); color:var(--destructive); font-size:0.875rem; margin-bottom:1.25rem;">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('contacto.send') }}" style="display:flex; flex-direction:column; gap:1rem;">
                    @csrf

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.875rem;">
                        <div>
                            <label style="font-size:0.8125rem; font-weight:500; display:block; margin-bottom:0.375rem;">
                                Nombre <span style="color:var(--destructive);">*</span>
                            </label>
                            <input type="text" name="nombre" class="input"
                                value="{{ old('nombre', Auth::user()->nombre ?? '') }}"
                                placeholder="Tu nombre" required />
                        </div>

                        <div>
                            <label style="font-size:0.8125rem; font-weight:500; display:block; margin-bottom:0.375rem;">
                                Email <span style="color:var(--destructive);">*</span>
                            </label>
                            <input type="email" name="email" class="input"
                                value="{{ old('email', Auth::user()->email ?? '') }}"
                                placeholder="tu@email.com" required />
                        </div>
                    </div>

                    <div>
                        <label style="font-size:0.8125rem; font-weight:500; display:block; margin-bottom:0.375rem;">
                            Asunto <span style="color:var(--destructive);">*</span>
                        </label>
                        <select name="asunto" class="input" required style="cursor:pointer;">
                            <option value="" disabled {{ old('asunto') ? '' : 'selected' }}>Selecciona un asunto</option>
                            <option value="Consulta general" {{ old('asunto') === 'Consulta general' ? 'selected' : '' }}>Consulta general</option>
                            <option value="Problema técnico" {{ old('asunto') === 'Problema técnico' ? 'selected' : '' }}>Problema técnico</option>
                            <option value="Solicitud de moderación" {{ old('asunto') === 'Solicitud de moderación' ? 'selected' : '' }}>Solicitud de moderación</option>
                            <option value="Pedido o tienda" {{ old('asunto') === 'Pedido o tienda' ? 'selected' : '' }}>Pedido o tienda</option>
                            <option value="Solicitud de eliminación de cuenta" {{ old('asunto') === 'Solicitud de eliminación de cuenta' ? 'selected' : '' }}>Solicitud de eliminación de cuenta</option>
                            <option value="Otro" {{ old('asunto') === 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-size:0.8125rem; font-weight:500; display:block; margin-bottom:0.375rem;">
                            Mensaje <span style="color:var(--destructive);">*</span>
                        </label>
                        <textarea name="mensaje" class="input" rows="6"
                            placeholder="Describe tu consulta con el mayor detalle posible..."
                            required style="resize:vertical; min-height:140px;">{{ old('mensaje') }}</textarea>
                        <p style="font-size:0.72rem; color:var(--muted-foreground); margin:0.25rem 0 0;">
                            Máximo 3000 caracteres.
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; height:2.75rem;">
                        <i data-lucide="send" style="width:1rem;height:1rem;"></i>
                        Enviar mensaje
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
