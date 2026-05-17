<?php
// app/Http/Middleware/CheckMantenimiento.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlatformSetting;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class CheckMantenimiento
{
    public function handle(Request $request, Closure $next): Response
    {
        $enMantenimiento = PlatformSetting::get('modo_mantenimiento', 'false') === 'true';

        if ($enMantenimiento) {
            // Permitir siempre login, logout y recuperación de contraseña
            if ($request->routeIs('login', 'logout', 'password.*') || $request->is('login', 'logout')) {
                return $next($request);
            }

            // Con prepend, StartSession ya corrió antes, así que la sesión está disponible.
            // Leemos el userId directamente de la sesión sin pasar por Auth::check()
            try {
                $sessionKey = 'login_web_' . sha1(\Illuminate\Auth\SessionGuard::class);
                $userId = $request->session()->get($sessionKey);
                if ($userId) {
                    $user = User::find($userId);
                    if ($user && $user->esAdmin()) {
                        return $next($request);
                    }
                }
            } catch (\Exception $e) {
                // Sesión no disponible todavía, continuamos
            }

            // Fallback por si Auth sí está resuelto
            if (Auth::check() && Auth::user()->esAdmin()) {
                return $next($request);
            }

            abort(503, 'La plataforma está en mantenimiento. Vuelve pronto.');
        }

        return $next($request);
    }
}
