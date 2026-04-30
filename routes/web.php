<?php

use Illuminate\Support\Facades\Route;

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
| Rutas de autenticación (generadas por Laravel UI)
| Incluye: login, logout, register, password reset
|--------------------------------------------------------------------------
*/
Auth::routes();

/*
|--------------------------------------------------------------------------
| Rutas protegidas — solo usuarios autenticados
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, 'index'])
        ->name('dashboard');

    // Spots

    Route::get('/spots',         [\App\Http\Controllers\SpotController::class, 'index'])  ->name('spots.index');
    Route::get('/spots/create',  [\App\Http\Controllers\SpotController::class, 'create']) ->name('spots.create');
    Route::post('/spots',        [\App\Http\Controllers\SpotController::class, 'store'])  ->name('spots.store');
    Route::get('/spots/{spot}',  [\App\Http\Controllers\SpotController::class, 'show'])   ->name('spots.show');
    Route::get('/spots/{spot}/edit',  [\App\Http\Controllers\SpotController::class, 'edit'])   ->name('spots.edit');
    Route::put('/spots/{spot}',  [\App\Http\Controllers\SpotController::class, 'update']) ->name('spots.update');
    // routes/web.php
Route::get('/spots/favorites', [SpotController::class, 'favorites'])->name('spots.favorites');
Route::get('/spots/explored',  [SpotController::class, 'explored'])->name('spots.explored');
    Route::delete('/spots/{spot}', [\App\Http\Controllers\SpotController::class, 'destroy'])->name('spots.destroy');

    // Comunidades
    Route::get('/communities', [\App\Http\Controllers\CommunityController::class, 'index'])
        ->name('communities.index');

    // Tienda
    Route::get('/store', [\App\Http\Controllers\StoreController::class, 'index'])
        ->name('store.index');

    // Perfil
    Route::get('/profile',  [\App\Http\Controllers\ProfileController::class, 'show'])
        ->name('profile');
    Route::get('/settings', [\App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('settings');

    // Mapa
    Route::get('/map', function () {
        return view('map.index'); // crea esta vista cuando llegues al módulo mapa
    })->name('map');

});
