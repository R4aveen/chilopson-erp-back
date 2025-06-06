<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SucursalController;

/*
|--------------------------------------------------------------------------
| Rutas para el recurso “Sucursal”
|--------------------------------------------------------------------------
|
| Mismito esquema: auth:api + permisos.
|
*/
Route::group(['middleware' => ['auth:api']], function() {
    Route::post('sucursales',         [SucursalController::class, 'store'])
        ->middleware('permission:crear-sucursal');

    Route::get('sucursales',          [SucursalController::class, 'index'])
        ->middleware('permission:ver-sucursal');

    Route::get('sucursales/{id}',     [SucursalController::class, 'show'])
        ->middleware('permission:ver-sucursal');

    Route::put('sucursales/{id}',     [SucursalController::class, 'update'])
        ->middleware('permission:editar-sucursal');

    Route::delete('sucursales/{id}',  [SucursalController::class, 'destroy'])
        ->middleware('permission:eliminar-sucursal');
});
