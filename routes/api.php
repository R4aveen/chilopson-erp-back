<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\SubempresaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\AuthController;

// Rutas públicas (registro e iniciar sesión)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Rutas protegidas
Route::group(['middleware' => ['auth:api']], function() {
    // Empresa
    Route::post('empresas', [EmpresaController::class, 'store'])->middleware('permission:crear-empresa');
    Route::get('empresas', [EmpresaController::class, 'index'])->middleware('permission:ver-empresa');
    Route::get('empresas/{id}', [EmpresaController::class, 'show'])->middleware('permission:ver-empresa');
    Route::put('empresas/{id}', [EmpresaController::class, 'update'])->middleware('permission:editar-empresa');
    Route::delete('empresas/{id}', [EmpresaController::class, 'destroy'])->middleware('permission:eliminar-empresa');

    // Subempresa
    Route::post('subempresas', [SubempresaController::class, 'store'])->middleware('permission:crear-subempresa');
    Route::get('subempresas', [SubempresaController::class, 'index'])->middleware('permission:ver-subempresa');
    Route::get('subempresas/{id}', [SubempresaController::class, 'show'])->middleware('permission:ver-subempresa');
    Route::put('subempresas/{id}', [SubempresaController::class, 'update'])->middleware('permission:editar-subempresa');
    Route::delete('subempresas/{id}', [SubempresaController::class, 'destroy'])->middleware('permission:eliminar-subempresa');

    // Sucursal
    Route::post('sucursales', [SucursalController::class, 'store'])->middleware('permission:crear-sucursal');
    Route::get('sucursales', [SucursalController::class, 'index'])->middleware('permission:ver-sucursal');
    Route::get('sucursales/{id}', [SucursalController::class, 'show'])->middleware('permission:ver-sucursal');
    Route::put('sucursales/{id}', [SucursalController::class, 'update'])->middleware('permission:editar-sucursal');
    Route::delete('sucursales/{id}', [SucursalController::class, 'destroy'])->middleware('permission:eliminar-sucursal');

    // Invitaciones de usuarios
    Route::post('invitar', [AuthController::class, 'invitarUsuario'])->middleware('permission:invitar-usuario');
    Route::get('usuarios', [AuthController::class, 'listarUsuarios'])->middleware('permission:ver-usuarios');
    Route::put('usuarios/{id}', [AuthController::class, 'actualizarUsuario'])->middleware('permission:editar-usuarios');
    Route::delete('usuarios/{id}', [AuthController::class, 'eliminarUsuario'])->middleware('permission:eliminar-usuarios');

    // activacion de cuenta
    Route::post('activar/{token}', [AuthController::class, 'activarCuenta']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout']);
});
