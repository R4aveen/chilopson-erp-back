<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;


Route::group(['middleware' => ['auth:api']], function() {
    Route::post('empresas',           [EmpresaController::class, 'store'])
        ->middleware('permission:crear-empresa');

    Route::get('empresas',            [EmpresaController::class, 'index'])
        ->middleware('permission:ver-empresa');

    Route::get('empresas/{id}',       [EmpresaController::class, 'show'])
        ->middleware('permission:ver-empresa');

    Route::put('empresas/{id}',       [EmpresaController::class, 'update'])
        ->middleware('permission:editar-empresa');

    Route::delete('empresas/{id}',    [EmpresaController::class, 'destroy'])
        ->middleware('permission:eliminar-empresa');
});
