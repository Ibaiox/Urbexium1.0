<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Localizacion;
use App\Models\Material;
use App\Models\Ciudad;
use App\Models\UserActividad;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpotController extends Controller
{
    // ─── INDEX ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Localizacion::with(['user', 'imagenes', 'ciudad.pais', 'materiales', 'comentarios'])
            ->where('is_active', true)
            ->where('visibility', true);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('nombre', 'like', "%$s%")->orWhere('descripcion', 'like', "%$s%"));
        }

        if ($request->filled('difficulty') && $request->difficulty !== 'todos') {
            $map = ['facil' => 'baja', 'medio' => 'media', 'dificil' => 'alta'];
            $query->where('dificultad', $map[$request->difficulty] ?? $request->difficulty);
        }

        $spots  = $query->latest()->paginate(12)->withQueryString();
        $favIds = Auth::check() ? Auth::user()->favoritos()->pluck('localizaciones.id')->toArray() : [];

        return view('spots.index', compact('spots', 'favIds'));
    }

    // ─── SHOW ──────────────────────────────────────────────────────────────────
    public function show(Localizacion $spot)
    {
        $spot->load(['user', 'imagenes', 'ciudad.pais', 'materiales', 'comentarios.user']);

        $isFavorito  = Auth::check() && Auth::user()->favoritos()->where('localizaciones.id', $spot->id)->exists();
        $esModerador = Auth::check() && (Auth::user()->esAdmin() || Auth::user()->esModerador());

        // Registrar actividad de vista
        if (Auth::check()) {
            UserActividad::registrar('vista', "Visitaste \"{$spot->nombre}\"", $spot);
        }

        return view('spots.show', compact('spot', 'isFavorito', 'esModerador'));
    }

    // ─── CREATE ────────────────────────────────────────────────────────────────
    public function create()
    {
        $this->checkModerador();
        $materiales = Material::orderBy('nombre')->get();
        $ciudades   = Ciudad::with('pais')->orderBy('nombre')->get();
        return view('spots.create', compact('materiales', 'ciudades'));
    }

    // ─── STORE ─────────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->checkModerador();

        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'ciudad_id'    => 'required|exists:ciudades,id',
            'latitud'      => 'required|numeric|between:-90,90',
            'longitud'     => 'required|numeric|between:-180,180',
            'dificultad'   => 'required|in:baja,media,alta',
            'estado'       => 'nullable|string|max:255',
            'materiales'   => 'nullable|array',
            'materiales.*' => 'exists:materiales,id',
            'imagenes'     => 'nullable|array|max:10',
            'imagenes.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
        ]);

        $spot = Localizacion::create([
            'nombre'              => $validated['nombre'],
            'descripcion'        => $validated['descripcion'] ?? null,
            'ciudad_id'          => $validated['ciudad_id'],
            'latitud'            => $validated['latitud'],
            'longitud'           => $validated['longitud'],
            'dificultad'         => $validated['dificultad'],
            'estado'             => $validated['estado'] ?? null,
            'user_id'            => Auth::id(),
            'verification_status'=> 'pendiente',
        ]);

        if (!empty($validated['materiales'])) {
            $spot->materiales()->sync($validated['materiales']);
        }

        $this->subirImagenes($request, $spot);

        UserActividad::registrar('spot_creado', "Creaste el spot \"{$spot->nombre}\"", $spot);

        return redirect()->route('spots.show', $spot)->with('success', 'Spot creado correctamente.');
    }

    // ─── EDIT ──────────────────────────────────────────────────────────────────
    public function edit(Localizacion $spot)
    {
        $this->checkModerador();
        $spot->load(['materiales', 'imagenes', 'ciudad']);
        $materiales = Material::orderBy('nombre')->get();
        $ciudades   = Ciudad::with('pais')->orderBy('nombre')->get();
        return view('spots.edit', compact('spot', 'materiales', 'ciudades'));
    }

    // ─── UPDATE ────────────────────────────────────────────────────────────────
    public function update(Request $request, Localizacion $spot)
    {
        $this->checkModerador();

        $validated = $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'ciudad_id'       => 'required|exists:ciudades,id',
            'latitud'         => 'required|numeric|between:-90,90',
            'longitud'        => 'required|numeric|between:-180,180',
            'dificultad'      => 'required|in:baja,media,alta',
            'estado'          => 'nullable|string|max:255',
            'materiales'      => 'nullable|array',
            'materiales.*'    => 'exists:materiales,id',
            'imagenes'        => 'nullable|array|max:10',
            'imagenes.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'delete_images'   => 'nullable|array',
            'delete_images.*' => 'exists:imagenes_localizacion,id',
        ]);

        $spot->update([
            'nombre'      => $validated['nombre'],
            'descripcion' => $validated['descripcion'] ?? null,
            'ciudad_id'   => $validated['ciudad_id'],
            'latitud'     => $validated['latitud'],
            'longitud'    => $validated['longitud'],
            'dificultad'  => $validated['dificultad'],
            'estado'      => $validated['estado'] ?? null,
            'visibility'  => $request->boolean('visibility'),
        ]);

        $spot->materiales()->sync($validated['materiales'] ?? []);

        // Eliminar imágenes marcadas
        if (!empty($validated['delete_images'])) {
            foreach ($spot->imagenes()->whereIn('id', $validated['delete_images'])->get() as $img) {
                $this->borrarArchivoImagen($img->url);
                $img->delete();
            }
        }

        $this->subirImagenes($request, $spot);

        UserActividad::registrar('spot_editado', "Editaste el spot \"{$spot->nombre}\"", $spot);

        return redirect()->route('spots.show', $spot)->with('success', 'Spot actualizado.');
    }

    // ─── DESTROY ───────────────────────────────────────────────────────────────
    public function destroy(Localizacion $spot)
    {
        $this->checkModerador();
        $nombre = $spot->nombre;
        foreach ($spot->imagenes as $img) {
            $this->borrarArchivoImagen($img->url);
        }
        UserActividad::registrar('spot_borrado', "Eliminaste el spot \"{$nombre}\"", $spot);
        $spot->delete();
        return redirect()->route('spots.index')->with('success', 'Spot eliminado.');
    }

    // ─── TOGGLE FAVORITO ───────────────────────────────────────────────────────
    public function toggleFavorito(Localizacion $spot)
    {
        $result    = Auth::user()->favoritos()->toggle($spot->id);
        $esFav     = count($result['attached']) > 0;

        if ($esFav) {
            UserActividad::registrar('favorito_add', "Añadiste \"{$spot->nombre}\" a favoritos", $spot);
        } else {
            UserActividad::registrar('favorito_rem', "Quitaste \"{$spot->nombre}\" de favoritos", $spot);
        }

        return response()->json(['favorito' => $esFav]);
    }

    // ─── COMENTARIO ────────────────────────────────────────────────────────────
    public function storeComentario(Request $request, Localizacion $spot)
    {
        $request->validate(['contenido' => 'required|string|max:1000']);
        $spot->comentarios()->create(['user_id' => Auth::id(), 'contenido' => $request->contenido]);
        UserActividad::registrar('comentario', "Comentaste en \"{$spot->nombre}\"", $spot);
        return back()->with('success', 'Comentario añadido.');
    }

    // ─── FAVORITOS ─────────────────────────────────────────────────────────────
    public function favorites()
    {
        $spots  = Auth::user()->favoritos()->with(['imagenes', 'ciudad.pais', 'user', 'materiales', 'comentarios'])->paginate(12);
        $favIds = $spots->pluck('id')->toArray();
        return view('spots.favorites', compact('spots', 'favIds'));
    }

    // ═══ HELPERS PRIVADOS ══════════════════════════════════════════════════════

    private function subirImagenes(Request $request, Localizacion $spot): void
    {
        if (!$request->hasFile('imagenes')) return;
        foreach ($request->file('imagenes') as $file) {
            if ($file && $file->isValid()) {
                $path = $file->store('spots', 'public');
                $spot->imagenes()->create([
                    'url'     => Storage::url($path),
                    'user_id' => Auth::id(),
                ]);
            }
        }
    }

    private function borrarArchivoImagen(string $url): void
    {
        // Convierte /storage/spots/xxx.jpg → public/spots/xxx.jpg
        $path = 'public/' . ltrim(str_replace('/storage', '', $url), '/');
        if (Storage::exists($path)) {
            Storage::delete($path);
        }
    }

    private function checkModerador(): void
    {
        if (!Auth::check() || (!Auth::user()->esAdmin() && !Auth::user()->esModerador())) {
            abort(403, 'No tienes permisos para realizar esta acción.');
        }
    }
}
