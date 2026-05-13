<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
use App\Mail\BienvenidaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Validación — el campo del form se llama 'nombre'
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nombre'   => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Crear usuario — columnas reales: nombre, rol_id
     * El seeder debe tener un rol con nombre = 'usuario'
     */
       protected function create(array $data)
    {
        // Busca el rol "usuario" (o el que tengas por defecto)
        $rolUsuario = \App\Models\Rol::where('nombre', 'usuario')->first();

        $user = \App\Models\User::create([
            'nombre'   => $data['nombre'] ?? $data['name'],
            'email'    => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'rol_id'   => $rolUsuario?->id,
        ]);

        // ── Enviar email de bienvenida ──────────────────────────────────
        try {
            Mail::to($user->email)->send(new BienvenidaMail($user));
        } catch (\Throwable $e) {
            // Si el correo falla, el registro sigue adelante igualmente
            \Illuminate\Support\Facades\Log::warning('Email bienvenida fallido: ' . $e->getMessage());
        }

        return $user;
    }
}
