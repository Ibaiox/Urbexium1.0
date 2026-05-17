<?php
// app/Http/Middleware/CheckMantenimiento.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PlatformSetting;
use Symfony\Component\HttpFoundation\Response;

class CheckMantenimiento
{
    public function handle(Request $request, Closure $next): Response
    {
        $enMantenimiento = PlatformSetting::get('modo_mantenimiento', 'false') === 'true';

        if ($enMantenimiento) {
            // Los admins pueden seguir accediendo siempre
            if (Auth::check() && Auth::user()->esAdmin()) {
                return $next($request);
            }

            // Permitir acceso a login/logout para que el admin pueda entrar
            if ($request->routeIs('login', 'logout', 'password.*')) {
                return $next($request);
            }

            // Devolver vista de mantenimiento (503)
            abort(503, 'La plataforma está en mantenimiento. Vuelve pronto.');
        }

        return $next($request);
    }
}
