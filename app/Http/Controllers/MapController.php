<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localizacion;
use App\Models\Ciudad;

class MapController extends Controller
{
    /**
     * Vista principal del mapa
     */
    public function index()
    {
        $ciudades = Ciudad::orderBy('nombre')->get();

        return view('map.index', compact('ciudades'));
    }

    /**
     * API JSON — devuelve los spots filtrados para el mapa
     */
    public function spots(Request $request)
    {
        $query = Localizacion::with(['ciudad.pais', 'imagenes', 'user'])
            ->where('visibility', true)
            ->where('is_active', true);

        // Filtro: dificultad
        if ($request->filled('dificultad') && $request->dificultad !== 'all') {
            $query->where('dificultad', $request->dificultad);
        }

        // Filtro: estado de verificación
        if ($request->filled('verificacion') && $request->verificacion !== 'all') {
            $query->where('verification_status', $request->verificacion);
        }

        // Filtro: ciudad
        if ($request->filled('ciudad') && $request->ciudad !== 'all') {
            $query->where('ciudad_id', $request->ciudad);
        }

        // Filtro: búsqueda por nombre
        if ($request->filled('q')) {
            $query->where('nombre', 'like', '%' . $request->q . '%');
        }

        $spots = $query->get()->map(function ($spot) {
            return [
                'id'           => $spot->id,
                'nombre'       => $spot->nombre,
                'descripcion'  => $spot->descripcion,
                'latitud'      => (float) $spot->latitud,
                'longitud'     => (float) $spot->longitud,
                'dificultad'   => $spot->dificultad,
                'estado'       => $spot->estado,
                'verificacion' => $spot->verification_status,
                'ciudad'       => $spot->ciudad?->nombre,
                'pais'         => $spot->ciudad?->pais?->nombre,
                'imagen'       => $spot->imagenPrincipal,
                'autor'        => $spot->user?->nombre,
                'url'          => route('spots.show', $spot->id),
            ];
        });

        return response()->json($spots);
    }
}
