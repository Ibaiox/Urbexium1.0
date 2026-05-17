<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * IMPORTANTE — modo mantenimiento:
         *
         * El middleware 'auth' en logout causaba un problema en modo mantenimiento:
         * cuando CheckMantenimiento hace abort(503), Laravel renderiza la vista de
         * error ANTES de que el guard de autenticación resuelva Auth::check().
         * El usuario tiene sesión válida, pero Auth::check() devuelve false porque
         * el ServiceProvider de auth no se ha inicializado en ese punto del ciclo.
         *
         * Resultado: el formulario POST a /logout en la vista 503 fallaba con 302
         * redirigiendo al login en lugar de ejecutar el logout.
         *
         * Solución: eliminar middleware('auth')->only('logout').
         * El trait AuthenticatesUsers gestiona el logout de forma segura
         * independientemente del estado de Auth::check() — hace
         * Auth::guard()->logout() + invalidate() + regenerate() sobre la sesión
         * directamente, sin necesidad de que el usuario esté "resuelto" por el guard.
         *
         * La ruta 'logout' ya está excluida de CheckMantenimiento, así que
         * el POST siempre llega aquí sin pasar por el bloqueo 503.
         */
        $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');  ← eliminado (ver comentario arriba)
    }
}
