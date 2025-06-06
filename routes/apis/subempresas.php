<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubempresaController;

/*
|--------------------------------------------------------------------------
| Rutas para el recurso “Subempresa”
|--------------------------------------------------------------------------
|
| También bajo auth:api + permisos.
|
*/
Route::group(['middleware' => ['auth:api']], function() {
    Route::post('subempresas',        [SubempresaController::class, 'store'])
        ->middleware('permission:crear-subempresa');

    Route::get('subempresas',         [SubempresaController::class, 'index'])
        ->middleware('permission:ver-subempresa');

    Route::get('subempresas/{id}',    [SubempresaController::class, 'show'])
        ->middleware('permission:ver-subempresa');

    Route::put('subempresas/{id}',    [SubempresaController::class, 'update'])
        ->middleware('permission:editar-subempresa');

    Route::delete('subempresas/{id}', [SubempresaController::class, 'destroy'])
        ->middleware('permission:eliminar-subempresa');
});
