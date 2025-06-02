<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subempresa;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitacionMail;
use Illuminate\Support\Facades\Auth;   // <--- IMPORTAR Auth FACADE
use Illuminate\Support\Facades\DB;     // <--- IMPORTAR DB FACADE

class AuthController extends Controller
{
    /**
     * Registro tradicional (no recomendado para ERP cerrado).
     */
    public function register(Request $request)
    {
        $data = $request->only(['nombre', 'apellido', 'email', 'password']);
        $validator = Validator::make($data, [
            'nombre'   => 'required|string',
            'apellido' => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::create([
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Generar un token JWT para el nuevo usuario
        // Aquí JWTAuth::fromUser() es correcto, Intelephense ya entiende que JWTAuth tiene ese método
        $token = JWTAuth::fromUser($user);

        return response()->json(['token' => $token], 201);
    }

    /**
     * Login con JWT
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Reemplazamos auth()->attempt por Auth::attempt para que Intelephense lo reconozca
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }

        return response()->json(['token' => $token]);
    }

    /**
     * Logout
     */
    public function logout()
    {
        // Reemplazamos auth()->logout() por Auth::logout()
        Auth::logout();

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Invitar usuario a una sucursal con rol asignado.
     */
    public function invitarUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'           => 'required|string',
            'apellido'         => 'required|string',
            'email'            => 'required|email|unique:users',
            'rut'              => 'required|string|unique:users',
            'cargo'            => 'required|string',
            'numero_telefono'  => 'nullable|string',
            'direccion'        => 'nullable|string',
            'sucursal_id'      => 'required|exists:sucursales,id',
            'rol'              => 'required|string|exists:roles,name',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Generar password temporal y token de invitación
        $passwordTemp = Str::random(10);

        $user = User::create([
            'nombre'           => $request->nombre,
            'apellido'         => $request->apellido,
            'email'            => $request->email,
            'password'         => Hash::make($passwordTemp),
            'cargo'            => $request->cargo,
            'rut'              => $request->rut,
            'numero_telefono'  => $request->numero_telefono,
            'direccion'        => $request->direccion,
            'sucursal_id'      => $request->sucursal_id,
            'permisos_laborales'=> json_encode([]),
        ]);

        // Asignar rol con Spatie
        $user->assignRole($request->rol);

        // Generar un UUID para usar como token de invitación
        $tokenInvitacion = Str::uuid()->toString();

        // Guardar el token en la tabla `password_resets` (usando DB::table)
        DB::table('password_resets')->insert([
            'email'      => $user->email,
            'token'      => $tokenInvitacion,
            'created_at' => now(),
        ]);

        // Enviar un correo de invitación
        // Intelephense espera Mail::to()->send() y un Mailable válido
        Mail::to($user->email)->send(new InvitacionMail($tokenInvitacion, $passwordTemp));

        return response()->json(['message' => 'Invitación enviada'], 201);
    }

    /**
     * Listar usuarios (con scope según rol y sucursal).
     */
    public function listarUsuarios()
    {
        // Reemplazamos auth()->user() por Auth::user() para que Intelephense entienda el tipo devuelto
        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            $usuarios = User::with('roles', 'sucursal')->get();
        }
        elseif ($user->hasRole('admin-empresa')) {
            $empresaId = $user->empresa_id; // Debes tener ese campo en la tabla users
            $subempresas = Subempresa::where('empresa_id', $empresaId)->pluck('id');
            $sucursales  = Sucursal::whereIn('subempresa_id', $subempresas)->pluck('id');
            $usuarios    = User::whereIn('sucursal_id', $sucursales)
                                ->with('roles', 'sucursal')
                                ->get();
        }
        elseif ($user->hasRole('admin-subempresa')) {
            $subempresaId = $user->subempresa_id; // Debes tener ese campo
            $sucursales   = Sucursal::where('subempresa_id', $subempresaId)->pluck('id');
            $usuarios     = User::whereIn('sucursal_id', $sucursales)
                                ->with('roles', 'sucursal')
                                ->get();
        }
        elseif ($user->hasRole('admin-sucursal')) {
            $sucursalId = $user->sucursal_id; // Campo en usuarios
            $usuarios   = User::where('sucursal_id', $sucursalId)
                              ->with('roles', 'sucursal')
                              ->get();
        }
        else {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        return response()->json($usuarios);
    }

    /**
     * Actualizar usuario (asignación de permisos dinámicos).
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $datos = $request->only([
            'nombre',
            'apellido',
            'cargo',
            'numero_telefono',
            'direccion',
            'dias_vacaciones',
            'dias_administrativos',
        ]);
        $user->update($datos);

        if ($request->has('permisos_laborales')) {
            $user->permisos_laborales = json_encode($request->permisos_laborales);
            $user->save();
        }

        if ($request->has('rol')) {
            $user->syncRoles([$request->rol]);
        }

        return response()->json(['message' => 'Usuario actualizado']);
    }

    /**
     * Eliminar usuario.
     */
    public function eliminarUsuario($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }

    /**
     * Activar cuenta de usuario con token.
     */
    public function activarCuenta(Request $request, $token)
    {
        $record = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (!$record || \Carbon\Carbon::parse($record->created_at)->addHours(24)->isPast()) {
            return response()->json(['error'=>'Token inválido o expirado'], 400);
        }

        $user = User::where('email', $record->email)->first();
        if (!$user) {
            return response()->json(['error'=>'Usuario no existe'],404);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar token para no reutilizarlo
        DB::table('password_resets')->where('email',$user->email)->delete();

        return response()->json(['message'=>'Cuenta activada. Ahora puedes iniciar sesión.']);
    }



}
