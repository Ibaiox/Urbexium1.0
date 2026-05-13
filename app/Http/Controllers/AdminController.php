<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Localizacion;
use App\Models\Reporte;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Notificacion;
use App\Models\Rol;
use App\Models\AdminActivityLog;
use App\Models\PlatformSetting;
use App\Mail\SpotAprobadoMail;
use App\Mail\SpotRechazadoMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ── Middleware: solo admins ────────────────────────────────────────────
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->esAdmin()) {
                abort(403, 'Acceso restringido al administrador.');
            }
            return $next($request);
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    //  DASHBOARD PRINCIPAL
    // ══════════════════════════════════════════════════════════════════════

    public function index()
    {
        $stats = [
            // Usuarios
            'total_usuarios'       => User::count(),
            'nuevos_usuarios_mes'  => User::whereMonth('created_at', now()->month)->count(),
            'usuarios_baneados'    => User::where('baneado', true)->count(),
            'moderadores'          => User::whereHas('rol', fn($q) => $q->where('nombre', 'moderador'))->count(),

            // Spots
            'total_spots'          => Localizacion::count(),
            'spots_activos'        => Localizacion::where('is_active', true)->count(),
            'spots_ocultos'        => Localizacion::where('is_active', false)->count(),
            'spots_pendientes'     => Localizacion::where('verification_status', 'pendiente')->count(),

            // Reportes
            'total_reportes'       => Reporte::count(),
            'reportes_abiertos'    => Reporte::where('estado', 'abierto')->count(),
            'reportes_resueltos'   => Reporte::where('estado', 'resuelto')->count(),

            // Tienda
            'productos_activos'    => Producto::where('activo', true)->count(),
            'total_pedidos'        => Pedido::count(),
            'pedidos_pendientes'   => Pedido::where('estado', 'pendiente')->count(),
            'ingresos_mes'         => Pedido::whereIn('estado', ['procesando','enviado','entregado'])
                                        ->whereMonth('created_at', now()->month)
                                        ->sum('total'),
        ];

        // ── Datos para gráficas Chart.js ──────────────────────────────────

        // 1. Registros de usuarios por mes (últimos 12 meses)
        $registrosPorMes = User::selectRaw("strftime('%m', created_at) as mes, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupByRaw("strftime('%Y-%m', created_at)")
            ->orderByRaw("strftime('%Y-%m', created_at)")
            ->get()
            ->map(fn($r) => ['mes' => (int)$r->mes, 'total' => $r->total]);

        // Build full 12-month labels (fills gaps with 0)
        $mesesLabels = [];
        $mesesData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $mesesLabels[] = $mes->locale('es')->isoFormat('MMM YY');
            $mesNum = (int) $mes->format('m');
            $found  = $registrosPorMes->firstWhere('mes', $mesNum);
            $mesesData[] = $found ? $found->total : 0;
        }

        // 2. Spots aprobados vs rechazados (por mes, últimos 6 meses)
        $spotsAprobados  = Localizacion::selectRaw("strftime('%m', created_at) as mes, COUNT(*) as total")
            ->where('verification_status', 'verificado')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupByRaw("strftime('%Y-%m', created_at)")
            ->orderByRaw("strftime('%Y-%m', created_at)")
            ->pluck('total', 'mes')->toArray();

        // Rechazados = los que fueron eliminados con estado 'rechazado' (aproximación: spots deleted con notificacion aviso)
        // Como los rechazados se borran, usamos soft-deletes si existen o bien contamos los pendientes que quedaron
        // En este caso contamos los spots creados vs los verificados como proxy
        $spotsMeses     = [];
        $spotsAprobData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes = now()->subMonths($i);
            $spotsMeses[] = $mes->locale('es')->isoFormat('MMM YY');
            $mesNum = (int) $mes->format('m');
            $spotsAprobData[] = $spotsAprobados[$mesNum] ?? 0;
        }

        // 3. Pedidos por estado
        $pedidosPorEstado = Pedido::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $estadosMap = ['pendiente' => 0, 'procesando' => 0, 'enviado' => 0, 'entregado' => 0, 'cancelado' => 0];
        foreach ($pedidosPorEstado as $estado => $total) {
            $estadosMap[$estado] = $total;
        }

        // ── Datos de tablas ───────────────────────────────────────────────
        $recentUsers      = User::with('rol')->latest()->take(8)->get();
        $recentReportes   = Reporte::with(['user', 'localizacion'])->where('estado', 'abierto')->latest()->take(5)->get();
        $spotsPendientes  = Localizacion::with(['user', 'imagenes'])->where('verification_status', 'pendiente')->latest()->take(8)->get();
        $spotsRecientes   = Localizacion::with(['user'])->latest()->take(8)->get();
        $productos        = Producto::latest()->take(8)->get();
        $pedidosRecientes = Pedido::with('user')->latest()->take(8)->get();

        // ── Log de actividad admin (últimos 30 registros) ─────────────────
        $activityLogs = AdminActivityLog::with('admin')->latest()->take(30)->get();

        // ── Ajustes de plataforma ─────────────────────────────────────────
        $platformSettings = PlatformSetting::all()->keyBy('clave');

        return view('admin.index', compact(
            'stats',
            'recentUsers',
            'recentReportes',
            'spotsPendientes',
            'spotsRecientes',
            'productos',
            'pedidosRecientes',
            // Gráficas
            'mesesLabels',
            'mesesData',
            'spotsMeses',
            'spotsAprobData',
            'estadosMap',
            // Log & ajustes
            'activityLogs',
            'platformSettings',
        ));
    }

    // ══════════════════════════════════════════════════════════════════════
    //  EXPORTACIÓN CSV
    // ══════════════════════════════════════════════════════════════════════

    public function exportCsv(Request $request, string $tipo)
    {
        $allowed = ['usuarios', 'spots', 'pedidos'];
        abort_unless(in_array($tipo, $allowed), 404);

        $filename = "export_{$tipo}_" . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = match($tipo) {
            'usuarios' => function () use ($request) {
                $fh = fopen('php://output', 'w');
                fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
                fputcsv($fh, ['ID', 'Nombre', 'Email', 'Rol', 'Baneado', 'Registrado']);

                $query = User::with('rol');
                if ($baneado = $request->get('baneado')) {
                    $query->where('baneado', $baneado === '1');
                }
                if ($rol = $request->get('rol')) {
                    $query->whereHas('rol', fn($q) => $q->where('nombre', $rol));
                }

                $query->orderBy('id')->chunk(200, function ($users) use ($fh) {
                    foreach ($users as $u) {
                        fputcsv($fh, [
                            $u->id,
                            $u->nombre,
                            $u->email,
                            $u->rol?->nombre ?? '—',
                            $u->baneado ? 'Sí' : 'No',
                            $u->created_at->format('d/m/Y H:i'),
                        ]);
                    }
                });
                fclose($fh);
            },

            'spots' => function () use ($request) {
                $fh = fopen('php://output', 'w');
                fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($fh, ['ID', 'Nombre', 'Usuario', 'Estado Verificación', 'Activo', 'Creado']);

                $query = Localizacion::with('user');
                if ($estado = $request->get('estado')) {
                    $query->where('verification_status', $estado);
                }

                $query->orderBy('id')->chunk(200, function ($spots) use ($fh) {
                    foreach ($spots as $s) {
                        fputcsv($fh, [
                            $s->id,
                            $s->nombre,
                            $s->user?->nombre ?? '—',
                            $s->verification_status,
                            $s->is_active ? 'Sí' : 'No',
                            $s->created_at->format('d/m/Y H:i'),
                        ]);
                    }
                });
                fclose($fh);
            },

            'pedidos' => function () use ($request) {
                $fh = fopen('php://output', 'w');
                fprintf($fh, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($fh, ['ID', 'Cliente', 'Email', 'Total (€)', 'Estado', 'Fecha']);

                $query = Pedido::with('user');
                if ($estado = $request->get('estado')) {
                    $query->where('estado', $estado);
                }

                $query->orderBy('id')->chunk(200, function ($pedidos) use ($fh) {
                    foreach ($pedidos as $p) {
                        fputcsv($fh, [
                            $p->id,
                            $p->user?->nombre ?? '—',
                            $p->user?->email ?? '—',
                            number_format($p->total, 2, ',', '.'),
                            $p->estadoLabel(),
                            $p->created_at->format('d/m/Y H:i'),
                        ]);
                    }
                });
                fclose($fh);
            },
        };

        return Response::stream($callback, 200, $headers);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  AJUSTES DE PLATAFORMA
    // ══════════════════════════════════════════════════════════════════════

    public function ajustesGuardar(Request $request)
    {
        $request->validate([
            'modo_mantenimiento'   => 'nullable|in:true,false',
            'registro_abierto'     => 'nullable|in:true,false',
            'limite_spots_usuario' => 'required|integer|min:1|max:500',
            'mensaje_aviso_global' => 'nullable|string|max:500',
        ]);

        PlatformSetting::set('modo_mantenimiento',   $request->has('modo_mantenimiento') ? 'true' : 'false');
        PlatformSetting::set('registro_abierto',     $request->has('registro_abierto')   ? 'true' : 'false');
        PlatformSetting::set('limite_spots_usuario', $request->integer('limite_spots_usuario'));
        PlatformSetting::set('mensaje_aviso_global', $request->get('mensaje_aviso_global', ''));

        AdminActivityLog::registrar('guardar_ajustes', 'Actualizó los ajustes de la plataforma.');

        return back()->with('success', 'Ajustes guardados correctamente.');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  GESTIÓN DE USUARIOS
    // ══════════════════════════════════════════════════════════════════════

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function usersUpdate(Request $request, User $user)
    {
        $rules = [
            'nombre'   => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,'.$user->id,
            'bio'      => 'nullable|string|max:500',
            'avatar'   => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $data = $request->validate($rules);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($data['avatar']);
        }

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['password_confirmation']);
        $user->update($data);

        return redirect()->route('admin.users.edit', $user)
                         ->with('success', 'Usuario '.$user->nombre.' actualizado correctamente.');
    }

    public function usersIndex(Request $request)
    {
        $query = User::with('rol');

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($rol = $request->get('rol')) {
            $query->whereHas('rol', fn($q) => $q->where('nombre', $rol));
        }

        if ($request->get('baneado') !== null) {
            $query->where('baneado', (bool) $request->get('baneado'));
        }

        $users = $query->latest()->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function usersShow(User $user)
    {
        $user->load(['rol', 'localizaciones', 'pedidos', 'reportes']);
        return view('admin.users.show', compact('user'));
    }

    public function usersBan(User $user)
    {
        if ($user->esAdmin()) {
            return back()->with('error', 'No puedes banear a otro administrador.');
        }

        $user->update(['baneado' => !$user->baneado]);
        $accion     = $user->baneado ? 'ban_usuario' : 'desban_usuario';
        $descripcion = $user->baneado
            ? "Baneó al usuario {$user->nombre} (#{$user->id})"
            : "Desbaneó al usuario {$user->nombre} (#{$user->id})";

        AdminActivityLog::registrar($accion, $descripcion, 'usuario', $user->id);

        $label = $user->baneado ? 'baneado' : 'desbaneado';
        return back()->with('success', "Usuario {$user->nombre} {$label} correctamente.");
    }

    public function usersRol(Request $request, User $user)
    {
        $request->validate(['rol' => 'required|in:usuario,moderador,admin']);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes cambiar tu propio rol.');
        }

        $rol = Rol::where('nombre', $request->rol)->firstOrFail();
        $user->update(['rol_id' => $rol->id]);

        AdminActivityLog::registrar(
            'cambiar_rol',
            "Cambió el rol de {$user->nombre} (#{$user->id}) a '{$request->rol}'",
            'usuario',
            $user->id
        );

        return back()->with('success', "Rol de {$user->nombre} actualizado a {$request->rol}.");
    }

    // ══════════════════════════════════════════════════════════════════════
    //  GESTIÓN DE SPOTS
    // ══════════════════════════════════════════════════════════════════════

    public function spotsIndex(Request $request)
    {
        $query = Localizacion::with(['user', 'imagenes', 'ciudad']);

        if ($search = $request->get('q')) {
            $query->where('nombre', 'like', "%{$search}%");
        }

        if ($estado = $request->get('estado')) {
            $query->where('verification_status', $estado);
        }

        $spots = $query->latest()->paginate(25)->withQueryString();

        return view('admin.spots.index', compact('spots'));
    }

    public function spotsPendientes()
    {
        $spots = Localizacion::with(['user', 'imagenes', 'ciudad'])
            ->where('verification_status', 'pendiente')
            ->latest()
            ->paginate(20);

        return view('admin.spots.pendientes', compact('spots'));
    }

     public function spotsAprobar(Localizacion $spot)
    {
        $spot->update([
            'verification_status' => 'verificado',
            'is_active'           => true,
        ]);

        // Notificación interna en la app
        Notificacion::create([
            'user_id'         => $spot->user_id,
            'localizacion_id' => $spot->id,
            'tipo'            => 'spot_verificado',
            'titulo'          => '¡Tu spot ha sido aprobado!',
            'mensaje'         => "La localización \"{$spot->nombre}\" ha sido verificada y ya es visible para la comunidad.",
        ]);

        // ── Email al autor ──────────────────────────────────────────────
        try {
            $autor = $spot->user;
            if ($autor) {
                Mail::to($autor->email)->send(new SpotAprobadoMail($autor, $spot));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email spot aprobado fallido: ' . $e->getMessage());
        }

        AdminActivityLog::registrar('aprobar_spot', "Aprobó el spot \"{$spot->nombre}\" (#{$spot->id})", 'spot', $spot->id);

        return back()->with('success', "Spot \"{$spot->nombre}\" aprobado.");
    }

    public function spotsRechazar(Localizacion $spot)
    {
        $nombre = $spot->nombre;
        $userId = $spot->user_id;
        $spotId = $spot->id;
        $autor  = $spot->user; // guardamos antes de borrar

        $spot->delete();

        // Notificación interna en la app
        Notificacion::create([
            'user_id' => $userId,
            'tipo'    => 'spot_rechazado',
            'titulo'  => 'Spot rechazado',
            'mensaje' => "La localización \"{$nombre}\" no cumple con las normas de la comunidad y ha sido eliminada.",
        ]);

        // ── Email al autor ──────────────────────────────────────────────
        try {
            if ($autor) {
                Mail::to($autor->email)->send(new SpotRechazadoMail($autor, $nombre));
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Email spot rechazado fallido: ' . $e->getMessage());
        }

        AdminActivityLog::registrar('rechazar_spot', "Rechazó el spot \"{$nombre}\" (#{$spotId})", 'spot', $spotId);

        return back()->with('success', "Spot \"{$nombre}\" rechazado y eliminado.");
    }

    public function spotsDestroy(Localizacion $spot)
    {
        $nombre = $spot->nombre;
        $spotId = $spot->id;
        $spot->delete();

        AdminActivityLog::registrar('eliminar_spot', "Eliminó el spot \"{$nombre}\" (#{$spotId})", 'spot', $spotId);

        return back()->with('success', "Spot \"{$nombre}\" eliminado.");
    }

    // ══════════════════════════════════════════════════════════════════════
    //  GESTIÓN DE REPORTES
    // ══════════════════════════════════════════════════════════════════════

    public function reportesIndex(Request $request)
    {
        $query = Reporte::with(['user', 'localizacion']);

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        } else {
            $query->where('estado', 'abierto');
        }

        $reportes = $query->latest()->paginate(25)->withQueryString();

        return view('admin.reportes.index', compact('reportes'));
    }

    public function reportesShow(Reporte $reporte)
    {
        $reporte->load(['user', 'localizacion.user', 'localizacion.imagenes']);
        return view('admin.reportes.show', compact('reporte'));
    }

    public function reportesResolver(Reporte $reporte)
    {
        $reporte->update(['estado' => 'resuelto']);

        AdminActivityLog::registrar(
            'resolver_reporte',
            "Resolvió el reporte #{$reporte->id} de {$reporte->user?->nombre}",
            'reporte',
            $reporte->id
        );

        return back()->with('success', 'Reporte marcado como resuelto.');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  NOTIFICACIONES GLOBALES
    // ══════════════════════════════════════════════════════════════════════

    public function notificacionesSend(Request $request)
    {
        $request->validate([
            'destinatario' => 'required|in:todos,usuarios,moderadores',
            'titulo'       => 'required|string|max:255',
            'mensaje'      => 'required|string|max:1000',
            'tipo'         => 'required|in:info,aviso',
        ]);

        $query = User::query();

        if ($request->destinatario === 'usuarios') {
            $query->whereHas('rol', fn($q) => $q->where('nombre', 'usuario'));
        } elseif ($request->destinatario === 'moderadores') {
            $query->whereHas('rol', fn($q) => $q->where('nombre', 'moderador'));
        }

        $users = $query->pluck('id');
        $count = $users->count();

        $data = $users->map(fn($id) => [
            'user_id'    => $id,
            'tipo'       => $request->tipo,
            'titulo'     => $request->titulo,
            'mensaje'    => $request->mensaje,
            'leida'      => false,
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        foreach (array_chunk($data, 500) as $chunk) {
            Notificacion::insert($chunk);
        }

        return back()->with('success', "Notificación enviada a {$count} usuario(s).");
    }
}
