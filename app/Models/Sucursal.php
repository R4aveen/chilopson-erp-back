<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $fillable = ['subempresa_id',
    'nombre_sucursal',
    'direccion_sucursal',
    'telefono_sucursal',
    'email_sucursal',
    'fecha_creacion_sucursal',
    'fecha_modificacion_sucursal',
    'estado_sucursal',
    'nombre_encargado_sucursal',
    'telefono_encargado_sucursal',
    'email_encargado_sucursal',
    'horario_atencion_sucursal',
    'ubicacion_sucursal'
];

    public function subempresa()
    {
        return $this->belongsTo(Subempresa::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }
}
