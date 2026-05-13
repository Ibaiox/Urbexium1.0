{{-- resources/views/emails/spot-rechazado.blade.php --}}
@extends('emails.layout')

@section('email-title', 'Aviso sobre tu spot — Urbexium')

@section('email-body')
<h1 class="title">Aviso sobre tu localización</h1>
<p class="subtitle">Tu spot ha sido revisado por nuestro equipo</p>

<p>
    Hola <span class="highlight">{{ $user->nombre }}</span>, tras revisar tu localización
    <strong style="color:#fff;">«{{ $spotNombre }}»</strong>, nuestro equipo ha determinado
    que no cumple con las normas de la comunidad y no ha podido ser publicada.
</p>

<div class="info-box" style="border-color:#ef4444; background:rgba(239,68,68,0.05);">
    <div class="info-row">
        <span class="info-label"> Spot</span>
        <span class="info-value">{{ $spotNombre }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Estado</span>
        <span class="info-value" style="color:#ef4444;">No publicado</span>
    </div>
</div>

<p>
    Algunos motivos habituales de rechazo son: información insuficiente, imágenes de baja calidad,
    localización duplicada o contenido que no se ajusta a la temática de exploración urbana.
</p>

<p>
    Puedes volver a enviar otro spot asegurándote de incluir una descripción detallada,
    coordenadas precisas e imágenes de calidad.
</p>

<div style="text-align:center; margin:2rem 0;">
    <a href="{{ config('app.url') }}/spots/create" class="btn" style="background:#3b82f6; color:#fff;">
        Enviar un nuevo spot →
    </a>
</div>

<hr class="divider">

<p style="font-size:0.8125rem; color:#4a5568; margin:0;">
    Si crees que ha sido un error, <a href="{{ config('app.url') }}/contacto" style="color:#4ade80;">contáctanos</a>
    y lo revisaremos.
</p>
@endsection
