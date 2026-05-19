<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\ValoracionController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ComunidadesController;  // Controlador viejo (por si aún lo usas)
use App\Http\Controllers\CommunityController;    // ← NUEVO controlador de comunidades
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactoController;

/*
|--------------------------------------------------------------------------
| Ruta raíz — redirige al dashboard (público)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Páginas legales (públicas, sin auth)
|--------------------------------------------------------------------------
*/
Route::get('/privacidad', fn() => view('legal.privacidad'))->name('legal.privacidad');
Route::get('/cookies',    fn() => view('legal.cookies'))->name('legal.cookies');
Route::get('/aviso-legal', fn() => view('legal.aviso'))->name('legal.aviso');

/*
|--------------------------------------------------------------------------
| Autenticación (Laravel UI)
|--------------------------------------------------------------------------
*/
Auth::routes();

// ── Webhook Stripe (sin CSRF, fuera del grupo auth) ───────────────────
Route::post('/stripe/webhook', [TiendaController::class, 'stripeWebhook'])
     ->name('stripe.webhook')
     ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Rutas públicas (visibles sin login)
|--------------------------------------------------------------------------
|
| Dashboard, listado de spots y mapa son accesibles sin autenticación.
| Si el usuario está autenticado se aplica el middleware 'no.banned'.
|
*/
Route::middleware(['no.banned'])->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────────────
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    // ── Spots — sólo índice público ───────────────────────────────────────
    Route::get('/spots', [SpotController::class, 'index'])->name('spots.index');

    // ── Mapa — índice y datos públicos ────────────────────────────────────
    Route::get('/map',       [MapController::class, 'index'])->name('map');
    Route::get('/map/spots', [MapController::class, 'spots'])->name('map.spots');

});

/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'no.banned'])->group(function () {

    // ── Contacto ──────────────────────────────────────────────────────────
    Route::get('/contacto',  [ContactoController::class, 'index'])->name('contacto.index');
    Route::post('/contacto', [ContactoController::class, 'send'])->name('contacto.send');

    // ── Spots — acciones que requieren login ──────────────────────────────
    Route::get('/spots/create',          [SpotController::class, 'create'])         ->name('spots.create');
    Route::post('/spots',                [SpotController::class, 'store'])           ->name('spots.store');
    Route::get('/spots/favorites',       [SpotController::class, 'favorites'])       ->name('spots.favorites');
    Route::get('/spots/{spot}',          [SpotController::class, 'show'])            ->name('spots.show');
    Route::get('/spots/{spot}/edit',     [SpotController::class, 'edit'])            ->name('spots.edit');
    Route::put('/spots/{spot}',          [SpotController::class, 'update'])          ->name('spots.update');
    Route::delete('/spots/{spot}',       [SpotController::class, 'destroy'])         ->name('spots.destroy');
    Route::post('/spots/{spot}/fav',     [SpotController::class, 'toggleFavorito'])  ->name('spots.fav');
    Route::post('/spots/{spot}/comment', [SpotController::class, 'storeComentario'])->name('spots.comment');

    Route::post('/spots/{spot}/valorar',   [ValoracionController::class, 'store'])  ->name('spots.valorar');
    Route::delete('/spots/{spot}/valorar', [ValoracionController::class, 'destroy'])->name('spots.valorar.destroy');

    // ── Comunidades ───────────────────────────────────────────────────────
    // Las rutas públicas (index, show) también están aquí dentro del grupo auth/no.banned
    // porque en tu proyecto el grupo de arriba ya las incluía así.
    // Si quieres que index/show sean públicos, sácalos al grupo 'no.banned' de arriba.
    Route::prefix('comunidades')->name('comunidades.')->group(function () {
        Route::get('/',                                          [CommunityController::class, 'index'])         ->name('index');
        Route::get('/{community}',                              [CommunityController::class, 'show'])          ->name('show');
        Route::post('/{community}/unirse',                      [CommunityController::class, 'join'])          ->name('join');
        Route::delete('/{community}/salir',                     [CommunityController::class, 'leave'])         ->name('leave');
        Route::post('/{community}/mensajes',                    [CommunityController::class, 'storeMessage'])  ->name('messages.store');
        Route::delete('/{community}/mensajes/{message}',        [CommunityController::class, 'destroyMessage'])->name('messages.destroy');
    });

    // ── Tienda ────────────────────────────────────────────────────────────
    Route::prefix('tienda')->name('tienda.')->group(function () {

        Route::get('/',                    [TiendaController::class, 'index'])              ->name('index');
        Route::get('/checkout',            [TiendaController::class, 'checkoutView'])       ->name('checkout');
        Route::get('/pago-exitoso',        [TiendaController::class, 'pagoExitoso'])        ->name('pago-exitoso');

        Route::get('/crear',               [TiendaController::class, 'create'])             ->name('create');
        Route::post('/',                   [TiendaController::class, 'store'])              ->name('store');

        Route::post('/payment-intent',     [TiendaController::class, 'createPaymentIntent'])->name('payment-intent');

        Route::get('/pedidos',             [TiendaController::class, 'misPedidos'])         ->name('pedidos.index');
        Route::get('/pedidos/{pedido}',    [TiendaController::class, 'showPedido'])         ->name('pedidos.show');

        Route::post('/admin/pedidos/bulk',             [TiendaController::class, 'bulkEstadoPedidos']) ->name('admin.pedidos.bulk');
        Route::get('/admin/pedidos',                   [TiendaController::class, 'adminPedidos'])       ->name('admin.pedidos');
        Route::patch('/admin/pedidos/{pedido}/estado', [TiendaController::class, 'updateEstadoPedido'])->name('admin.pedidos.estado');

        Route::get('/{producto}',          [TiendaController::class, 'show'])               ->name('show');
        Route::get('/{producto}/editar',   [TiendaController::class, 'edit'])               ->name('edit');
        Route::put('/{producto}',          [TiendaController::class, 'update'])             ->name('update');
        Route::delete('/{producto}',       [TiendaController::class, 'destroy'])            ->name('destroy');
    });

    // ── Perfil ────────────────────────────────────────────────────────────
    Route::get('/perfil',           [PerfilController::class, 'index'])   ->name('perfil.index');
    Route::patch('/perfil',         [PerfilController::class, 'update'])  ->name('perfil.update');
    Route::patch('/perfil/password',[PerfilController::class, 'password'])->name('perfil.password');
    Route::delete('/perfil',        [PerfilController::class, 'destroy']) ->name('perfil.destroy');

    // ── Notificaciones ────────────────────────────────────────────────────
    Route::prefix('notificaciones')->name('notificaciones.')->group(function () {
        Route::patch('/mark-all',       [NotificacionController::class, 'markAllRead'])->name('markAllRead');
        Route::patch('/{notificacion}', [NotificacionController::class, 'markRead'])  ->name('markRead');
    });

    // ── Panel de Administración ───────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->middleware('is.admin')->group(function () {

        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::prefix('usuarios')->name('users.')->group(function () {
            Route::get('/',              [AdminController::class, 'usersIndex'])->name('index');
            Route::get('/{user}',        [AdminController::class, 'usersShow']) ->name('show');
            Route::patch('/{user}/ban',  [AdminController::class, 'usersBan'])   ->name('ban');
            Route::patch('/{user}/rol',  [AdminController::class, 'usersRol'])   ->name('rol');
            Route::get('/{user}/edit',   [AdminController::class, 'usersEdit'])  ->name('edit');
            Route::put('/{user}',        [AdminController::class, 'usersUpdate'])->name('update');
        });

        Route::prefix('spots')->name('spots.')->group(function () {
            Route::get('/',                   [AdminController::class, 'spotsIndex'])     ->name('index');
            Route::get('/pendientes',         [AdminController::class, 'spotsPendientes'])->name('pendientes');
            Route::patch('/{spot}/aprobar',   [AdminController::class, 'spotsAprobar'])  ->name('aprobar');
            Route::delete('/{spot}/rechazar', [AdminController::class, 'spotsRechazar']) ->name('rechazar');
            Route::delete('/{spot}',          [AdminController::class, 'spotsDestroy'])  ->name('destroy');
        });

        Route::prefix('reportes')->name('reportes.')->group(function () {
            Route::get('/',                    [AdminController::class, 'reportesIndex'])  ->name('index');
            Route::get('/{reporte}',           [AdminController::class, 'reportesShow'])  ->name('show');
            Route::patch('/{reporte}/resolver',[AdminController::class, 'reportesResolver'])->name('resolver');
        });

        Route::post('/notificaciones/send', [AdminController::class, 'notificacionesSend'])->name('notificaciones.send');

        Route::get('/export/{tipo}', [AdminController::class, 'exportCsv'])
            ->name('export')
            ->where('tipo', 'usuarios|spots|pedidos');

        Route::post('/ajustes', [AdminController::class, 'ajustesGuardar'])
            ->name('ajustes.guardar');
    });

});
