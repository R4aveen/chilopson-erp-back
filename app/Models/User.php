<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Sucursal;
use App\Models\Subempresa;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'cargo',
        'rut',
        'numero_telefono',
        'direccion',
        'fecha_ingreso',
        'dias_vacaciones',
        'dias_administrativos',
        'permisos_laborales',
        'sucursal_id',
    ];

    protected $hidden = ['password'];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    // Métodos requeridos para JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // -----------------------------------------
    // NUEVOS SCOPES (AL FINAL)
    // -----------------------------------------

    /**
     * Filtrar usuarios que pertenecen a una empresa completa.
     */
    public function scopePorEmpresa($query, $empresaId)
    {
        $subIds = Subempresa::where('empresa_id', $empresaId)->pluck('id');
        $sucIds = Sucursal::whereIn('subempresa_id', $subIds)->pluck('id');
        return $query->whereIn('sucursal_id', $sucIds);
    }

    /**
     * Filtrar usuarios de una subempresa específica.
     */
    public function scopePorSubempresa($query, $subempresaId)
    {
        $sucIds = Sucursal::where('subempresa_id', $subempresaId)->pluck('id');
        return $query->whereIn('sucursal_id', $sucIds);
    }

    /**
     * Filtrar usuarios de una sucursal concreta.
     */
    public function scopePorSucursal($query, $sucursalId)
    {
        return $query->where('sucursal_id', $sucursalId);
    }
}
