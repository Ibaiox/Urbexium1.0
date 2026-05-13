<?php

namespace App\Http\Controllers;

use App\Models\Localizacion;
use App\Models\Valoracion;
use App\Models\UserActividad;
use Illuminate\Http\Request;

class ValoracionController extends Controller
{
    /**
     * Guardar o actualizar la valoración del usuario autenticado para un spot.
     * Si ya existe, la actualiza. Si no, la crea.
     */
    public function store(Request $request, Localizacion $spot)
    {
        $request->validate([
            'puntuacion' => 'required|integer|between:1,5',
        ]);

        // Un usuario no puede valorar su propio spot
        if ($spot->user_id === auth()->id()) {
            return back()->with('error', 'No puedes valorar tu propio spot.');
        }

        Valoracion::updateOrCreate(
            [
                'user_id'         => auth()->id(),
                'localizacion_id' => $spot->id,
            ],
            [
                'puntuacion' => $request->puntuacion,
            ]
        );

        UserActividad::registrar(
            'valoracion',
            "Valoraste \"{$spot->nombre}\" con {$request->puntuacion} ★",
            $spot
        );

        $media  = round($spot->valoraciones()->avg('puntuacion'), 1);
        $votos  = $spot->valoraciones()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'media' => $media,
                'votos' => $votos,
                'mi_puntuacion' => $request->puntuacion,
            ]);
        }

        return back()->with('success', '¡Valoración guardada!');
    }

    /**
     * Eliminar la valoración del usuario en un spot.
     */
    public function destroy(Localizacion $spot)
    {
        Valoracion::where('user_id', auth()->id())
                  ->where('localizacion_id', $spot->id)
                  ->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'media' => round($spot->valoraciones()->avg('puntuacion'), 1),
                'votos' => $spot->valoraciones()->count(),
                'mi_puntuacion' => null,
            ]);
        }

        return back()->with('success', 'Valoración eliminada.');
    }
}
