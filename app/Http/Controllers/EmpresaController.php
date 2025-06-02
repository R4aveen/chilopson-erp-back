<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Usamos Auth::user() en lugar de auth()->user()
        $user = Auth::user();

        if (! $user->hasRole('super-admin') && ! $user->hasRole('admin-empresa')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Si es admin-empresa, verifica que el id coincida con su empresa_id
        if ($user->hasRole('admin-empresa') && $user->empresa_id != $id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $empresa = Empresa::with('subempresas')->findOrFail($id);
        return response()->json($empresa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
