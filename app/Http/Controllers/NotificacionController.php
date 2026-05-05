<?php
namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function markRead(Notificacion $notificacion)
    {
        abort_unless($notificacion->user_id === Auth::id(), 403);
        $notificacion->update(['leida' => true]);
        return back();
    }

    public function markAllRead()
    {
        Auth::user()->notificaciones()->update(['leida' => true]);
        return back()->with('success', 'Todas las notificaciones marcadas como leídas.');
    }
}
