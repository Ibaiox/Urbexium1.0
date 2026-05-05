<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user()->load('rol');

        $misSpots = $user->localizaciones()
            ->with(['imagenes', 'ciudad'])
            ->latest()
            ->paginate(9);

        $favoritos = $user->favoritos()
            ->with(['imagenes', 'ciudad'])
            ->latest('favoritos.created_at')
            ->paginate(9);

        $pedidos = $user->pedidos()
            ->with('items.producto')
            ->latest()
            ->paginate(10);

        $notificaciones = $user->notificaciones()
            ->with('localizacion')
            ->latest()
            ->paginate(15);

        $notificacionesCount = $user->notificaciones()->where('leida', false)->count();

        $spotsCount    = $user->localizaciones()->count();
        $favoritosCount = $user->favoritos()->count();
        $pedidosCount  = $user->pedidos()->count();

        return view('perfil.index', compact(
            'user', 'misSpots', 'favoritos', 'pedidos',
            'notificaciones', 'notificacionesCount',
            'spotsCount', 'favoritosCount', 'pedidosCount'
        ));
    }

    /** Actualizar datos personales */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'bio'    => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    /** Cambiar contraseña */
    public function password(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /** Eliminar cuenta */
    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        $user = Auth::user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Tu cuenta ha sido eliminada.');
    }
}
