{{-- resources/views/emails/bienvenida.blade.php --}}
@extends('emails.layout')

@section('email-title', '¡Bienvenido/a a Urbexium!')

@section('email-body')
<h1 class="title">¡Hola, {{ $user->nombre }}! 👋</h1>
<p class="subtitle">Bienvenido/a a la comunidad de exploradores urbanos</p>

<p>
    Tu cuenta en <span class="highlight">Urbexium</span> ha sido creada correctamente.
    Ya puedes explorar spots, guardar tus favoritos, dejar comentarios y mucho más.
</p>

<hr class="divider">

<p><strong style="color:#fff;">¿Qué puedes hacer ahora?</strong></p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label"> Explorar spots</span>
        <span class="info-value">Descubre localizaciones verificadas</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Guardar favoritos</span>
        <span class="info-value">Crea tu lista de lugares</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Participar</span>
        <span class="info-value">Comenta y valora spots</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Tienda</span>
        <span class="info-value">Equipo para tus exploraciones</span>
    </div>
</div>

<div style="text-align:center; margin:2rem 0;">
    <a href="{{ config('app.url') }}/dashboard" class="btn">
        Ir a mi panel →
    </a>
</div>

<hr class="divider">

<p style="font-size:0.8125rem; color:#4a5568; margin:0;">
    Si no has creado esta cuenta, ignora este correo o
    <a href="{{ config('app.url') }}/contacto" style="color:#4ade80;">contáctanos</a>.
</p>
@endsection
