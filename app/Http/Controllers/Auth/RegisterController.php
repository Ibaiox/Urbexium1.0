<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Rol;
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
        $rol = Rol::where('nombre', 'usuario')->first();

        return User::create([
            'nombre'   => $data['nombre'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'rol_id'   => $rol ? $rol->id : null,
        ]);
    }
}
