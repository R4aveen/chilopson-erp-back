<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
}
