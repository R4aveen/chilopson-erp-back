<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación e Invitaciones de Usuario
|--------------------------------------------------------------------------
*/

// ** Rutas públicas (registro e iniciar sesión) **
Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);

// ** Rutas protegidas (requieren auth:api) **
Route::group(['middleware' => ['auth:api']], function() {
    // Invitaciones de usuarios
    Route::post('invitar',            [AuthController::class, 'invitarUsuario'])
        ->middleware('permission:invitar-usuario');

    Route::get('usuarios',            [AuthController::class, 'listarUsuarios'])
        ->middleware('permission:ver-usuarios');

    Route::put('usuarios/{id}',       [AuthController::class, 'actualizarUsuario'])
        ->middleware('permission:editar-usuarios');

    Route::delete('usuarios/{id}',    [AuthController::class, 'eliminarUsuario'])
        ->middleware('permission:eliminar-usuarios');

    // Activación de cuenta (nota: en tu original estaba dentro del grupo protegido)
    Route::post('activar/{token}',    [AuthController::class, 'activarCuenta']);

    // Logout
    Route::post('logout',             [AuthController::class, 'logout']);
});
