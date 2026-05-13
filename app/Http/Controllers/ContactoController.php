<?php
// app/Http/Controllers/ContactoController.php

namespace App\Http\Controllers;

use App\Mail\ContactoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    public function index()
    {
         return view('emails.index');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'nombre'  => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'asunto'  => 'required|string|max:255',
            'mensaje' => 'required|string|max:3000',
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'email.required'   => 'El email es obligatorio.',
            'email.email'      => 'El email no tiene un formato válido.',
            'asunto.required'  => 'El asunto es obligatorio.',
            'mensaje.required' => 'El mensaje es obligatorio.',
            'mensaje.max'      => 'El mensaje no puede superar los 3000 caracteres.',
        ]);

        Mail::to(config('mail.contact_address', config('mail.from.address')))
            ->send(new ContactoMail(
                nombreRemitente: $validated['nombre'],
                emailRemitente:  $validated['email'],
                asunto:          $validated['asunto'],
                mensaje:         $validated['mensaje'],
            ));

        return back()->with('success', '¡Mensaje enviado correctamente! Te responderemos lo antes posible.');
    }
}
