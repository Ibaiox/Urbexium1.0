<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ComunidadesController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\NotificacionController;

/*
|--------------------------------------------------------------------------
| Ruta raíz
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Autenticación (Laravel UI)
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'no.banned'])->group(function () {

    // ── Dashboard ─────────────────────────────────────────────────────────
    Route::get('/dashboard', [HomeController::class, 'index'])
        ->name('dashboard');

    // ── Mapa ──────────────────────────────────────────────────────────────
    Route::get('/map',       [MapController::class, 'index'])->name('map');
    Route::get('/map/spots', [MapController::class, 'spots'])->name('map.spots');

    // ── Spots ─────────────────────────────────────────────────────────────
    Route::get('/spots',                 [SpotController::class, 'index'])          ->name('spots.index');
    Route::get('/spots/create',          [SpotController::class, 'create'])         ->name('spots.create');
    Route::post('/spots',                [SpotController::class, 'store'])           ->name('spots.store');
    Route::get('/spots/favorites',       [SpotController::class, 'favorites'])       ->name('spots.favorites');
    Route::get('/spots/{spot}',          [SpotController::class, 'show'])            ->name('spots.show');
    Route::get('/spots/{spot}/edit',     [SpotController::class, 'edit'])            ->name('spots.edit');
    Route::put('/spots/{spot}',          [SpotController::class, 'update'])          ->name('spots.update');
    Route::delete('/spots/{spot}',       [SpotController::class, 'destroy'])         ->name('spots.destroy');
    Route::post('/spots/{spot}/fav',     [SpotController::class, 'toggleFavorito'])  ->name('spots.fav');
    Route::post('/spots/{spot}/fav',     [SpotController::class, 'toggleFavorito'])  ->name('favoritos.toggle');
    Route::post('/spots/{spot}/comment', [SpotController::class, 'storeComentario']) ->name('spots.comment');

    // ── Comunidades ───────────────────────────────────────────────────────
    Route::get('/comunidades', [ComunidadesController::class, 'index'])->name('comunidades.index');

    // ── Tienda ────────────────────────────────────────────────────────────
    Route::get('/tienda', [TiendaController::class, 'index'])->name('store.index');

    Route::prefix('tienda')->name('tienda.')->group(function () {
        Route::get('/',                    [TiendaController::class, 'index'])           ->name('index');
        Route::get('/crear',               [TiendaController::class, 'create'])          ->name('create');
        Route::post('/',                   [TiendaController::class, 'store'])            ->name('store');
        Route::get('/{producto}/editar',   [TiendaController::class, 'edit'])            ->name('edit');
        Route::put('/{producto}',          [TiendaController::class, 'update'])          ->name('update');
        Route::delete('/{producto}',       [TiendaController::class, 'destroy'])         ->name('destroy');
        Route::post('/checkout',           [TiendaController::class, 'checkout'])        ->name('checkout');
        Route::get('/pedidos',             [TiendaController::class, 'misPedidos'])      ->name('pedidos.index');
        Route::get('/pedidos/{pedido}',    [TiendaController::class, 'showPedido'])      ->name('pedidos.show');
        Route::get('/admin/pedidos',                   [TiendaController::class, 'adminPedidos'])       ->name('admin.pedidos');
        Route::patch('/admin/pedidos/{pedido}/estado', [TiendaController::class, 'updateEstadoPedido']) ->name('admin.pedidos.estado');
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

});
