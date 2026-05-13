<?php
// app/Http/Middleware/IsAdmin.php
// Registra este middleware en app/Http/Kernel.php dentro de $routeMiddleware:
//   'is.admin' => \App\Http\Middleware\IsAdmin::class,
// Y úsalo en las rutas de admin: ->middleware('is.admin')

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->esAdmin()) {
            abort(403, 'Acceso restringido al panel de administración.');
        }

        return $next($request);
    }
}
