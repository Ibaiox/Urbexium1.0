{{-- resources/views/emails/spot-aprobado.blade.php --}}
@extends('emails.layout')

@section('email-title', '¡Tu spot ha sido aprobado!')

@section('email-body')
<h1 class="title"> ¡Tu spot ha sido aprobado!</h1>
<p class="subtitle">La comunidad ya puede verlo</p>

<p>
    Hola <span class="highlight">{{ $user->nombre }}</span>, nos alegra informarte de que tu localización
    ha pasado la revisión de nuestro equipo y ya está disponible para todos los exploradores.
</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label"> Spot</span>
        <span class="info-value">{{ $spot->nombre }}</span>
    </div>
    @if($spot->ciudad)
    <div class="info-row">
        <span class="info-label"> Ciudad</span>
        <span class="info-value">{{ $spot->ciudad->nombre }}</span>
    </div>
    @endif
    <div class="info-row">
        <span class="info-label"> Dificultad</span>
        <span class="info-value">{{ ucfirst($spot->dificultad) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Estado</span>
        <span class="info-value" style="color:#4ade80;">Verificado ✓</span>
    </div>
</div>

<div style="text-align:center; margin:2rem 0;">
    <a href="{{ config('app.url') }}/spots/{{ $spot->id }}" class="btn">
        Ver mi spot →
    </a>
</div>

<p style="font-size:0.875rem; color:#8892a4;">
    Gracias por contribuir a la comunidad Urbexium. Sigue añadiendo localizaciones y ayudando
    a que la comunidad crezca.
</p>
@endsection
