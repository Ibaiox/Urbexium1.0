<?php
// app/Mail/PedidoConfirmadoMail.php

namespace App\Mail;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PedidoConfirmadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Pedido $pedido
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🧾 Pedido confirmado #' . $this->pedido->id . ' — Urbexium',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pedido-confirmado',
        );
    }
}
