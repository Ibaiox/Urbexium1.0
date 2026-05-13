<?php
// app/Mail/SpotRechazadoMail.php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpotRechazadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $spotNombre
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Aviso sobre tu spot — Urbexium',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spot-rechazado',
        );
    }
}
