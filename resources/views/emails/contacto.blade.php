{{-- resources/views/emails/contacto.blade.php --}}
@extends('emails.layout')

@section('email-title', 'Nuevo mensaje de contacto')

@section('email-body')
<h1 class="title"> Nuevo mensaje de contacto</h1>
<p class="subtitle">Recibido desde el formulario de Urbexium</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label"> Nombre</span>
        <span class="info-value">{{ $nombreRemitente }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Email</span>
        <span class="info-value">
            <a href="mailto:{{ $emailRemitente }}" style="color:#4ade80;">{{ $emailRemitente }}</a>
        </span>
    </div>
    <div class="info-row">
        <span class="info-label"> Asunto</span>
        <span class="info-value">{{ $asunto }}</span>
    </div>
    <div class="info-row">
        <span class="info-label"> Fecha</span>
        <span class="info-value">{{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>

<p><strong style="color:#fff;">Mensaje:</strong></p>
<div class="info-box" style="padding:1.25rem;">
    <p style="white-space:pre-wrap; margin:0; color:#cbd5e1;">{{ $mensaje }}</p>
</div>

<div style="text-align:center; margin:2rem 0;">
    <a href="mailto:{{ $emailRemitente }}?subject=Re: {{ $asunto }}" class="btn">
        Responder al usuario →
    </a>
</div>
@endsection
