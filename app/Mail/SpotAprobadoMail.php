<?php
// app/Mail/SpotAprobadoMail.php

namespace App\Mail;

use App\Models\Localizacion;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpotAprobadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Localizacion $spot
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Tu spot ha sido aprobado — Urbexium',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spot-aprobado',
        );
    }
}
