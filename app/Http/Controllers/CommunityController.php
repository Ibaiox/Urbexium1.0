<?php

namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function __construct()
    {
        // Las rutas de escritura llevan middleware auth en web.php.
        // El constructor no lo fuerza globalmente para que index/show sean públicos
        // si en el futuro quisieras abrirlos. Si no, puedes añadir:
        // $this->middleware('auth')->except(['index', 'show']);
    }

    // ─────────────────────────────────────────────────────────────────────
    // Listado de comunidades
    // ─────────────────────────────────────────────────────────────────────

    public function index()
    {
        $communities = Community::withCount('members')->latest()->get();

        // Para cada comunidad marcamos si el usuario autenticado ya pertenece
        $userId = Auth::id();
        $joinedIds = $userId
            ? Auth::user()->communities()->pluck('community_id')->toArray()
            : [];

        return view('comunidades.index', compact('communities', 'joinedIds'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Detalle de comunidad + chat
    // ─────────────────────────────────────────────────────────────────────

    public function show(Community $community)
    {
        $community->loadCount('members');
        $members  = $community->members()->with('rol')->get();
        $messages = $community->messages()
                              ->with('user')
                              ->oldest()
                              ->get();

        $isMember = Auth::check() && $community->hasMember(Auth::user());

        return view('comunidades.show', compact('community', 'members', 'messages', 'isMember'));
    }

    // ─────────────────────────────────────────────────────────────────────
    // Unirse a una comunidad
    // ─────────────────────────────────────────────────────────────────────

    public function join(Community $community)
    {
        $user = Auth::user();

        if ($community->hasMember($user)) {
            return back()->with('info', 'Ya eres miembro de esta comunidad.');
        }

        $community->members()->attach($user->id, [
            'role'      => 'member',
            'joined_at' => now(),
        ]);

        return back()->with('success', '¡Te has unido a ' . $community->name . '!');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Abandonar una comunidad
    // ─────────────────────────────────────────────────────────────────────

    public function leave(Community $community)
    {
        $user = Auth::user();

        if (! $community->hasMember($user)) {
            return back()->with('info', 'No eres miembro de esta comunidad.');
        }

        $community->members()->detach($user->id);

        return back()->with('success', 'Has abandonado la comunidad.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Enviar mensaje al chat
    // ─────────────────────────────────────────────────────────────────────

    public function storeMessage(Request $request, Community $community)
    {
        $user = Auth::user();

        // Solo miembros pueden escribir
        if (! $community->hasMember($user)) {
            abort(403, 'Debes ser miembro de la comunidad para enviar mensajes.');
        }

        $data = $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        $community->messages()->create([
            'user_id' => $user->id,
            'message' => $data['message'],
        ]);

        return back()->with('success', 'Mensaje enviado.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // Eliminar mensaje (autor, moderador de comunidad o admin global)
    // ─────────────────────────────────────────────────────────────────────

    public function destroyMessage(Community $community, CommunityMessage $message)
    {
        $user = Auth::user();

        // Aseguramos que el mensaje pertenece a esta comunidad
        if ($message->community_id !== $community->id) {
            abort(404);
        }

        $isOwner        = $message->user_id === $user->id;
        $isCommunityMod = $community->memberRole($user) === 'moderator';

        // Solo el autor del mensaje o el moderador de esta comunidad pueden eliminar
        if (! ($isOwner || $isCommunityMod)) {
            abort(403, 'No tienes permiso para eliminar este mensaje.');
        }

        $message->delete();

        return back()->with('success', 'Mensaje eliminado.');
    }
}
