<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Localizacion;
use App\Models\UserActividad;

class HomeController extends Controller
{
    // Sin middleware('auth') aquí — el control lo hacen las rutas en web.php

    public function index()
    {
        // Spots verificados más recientes — visible para todos
        $nearbySpots = Localizacion::where('is_active', true)
            ->where('verification_status', 'verificado')
            ->with('imagenes', 'ciudad')
            ->withCount('comentarios')
            ->latest()
            ->take(5)
            ->get();

        // Datos solo disponibles para usuarios autenticados
        $user          = null;
        $recentSpots   = collect();
        $recentActivity = collect();
        $favoriteSpots = collect();
        $exploredSpots = collect();

        if (Auth::check()) {
            $user = Auth::user()->load('rol');

            // Spots vistos recientemente
            $recentSpots = UserActividad::where('user_id', $user->id)
                ->where('tipo', 'vista')
                ->whereNotNull('localizacion_id')
                ->with('localizacion.imagenes', 'localizacion.ciudad')
                ->latest()
                ->get()
                ->unique('localizacion_id')
                ->take(5);

            // Actividad reciente
            $recentActivity = UserActividad::where('user_id', $user->id)
                ->latest()
                ->take(10)
                ->get();

            // Spots favoritos
            $favoriteSpots = $user->favoritos()
                ->with('imagenes', 'ciudad')
                ->latest('favoritos.created_at')
                ->take(5)
                ->get();

            // Explorados = spots con comentario o valoración del usuario
            $exploredSpots = Localizacion::whereHas('comentarios', fn($q) => $q->where('user_id', $user->id))
                ->orWhereHas('valoraciones', fn($q) => $q->where('user_id', $user->id))
                ->with('imagenes', 'ciudad')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard.index', compact(
            'user',
            'recentSpots',
            'recentActivity',
            'nearbySpots',
            'favoriteSpots',
            'exploredSpots',
        ));
    }
}
