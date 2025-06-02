<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Subempresa extends Model
{
    protected $fillable = ['empresa_id',
    'nombre_subempresa',
    'rut_subempresa',
    'web_subempresa',   
    'teléfono_subempresa',
    'direccion_subempresa',
    'email_subempresa',
    'fecha_creacion_subempresa',
    'fecha_modificacion_subempresa',
    'nombre_encargado_subempresa',
    'telefono_encargado_subempresa',
    'email_encargado_subempresa',
    'estado_subempresa'
    ];   
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sucursales()
    {
        return $this->hasMany(Sucursal::class);
    }
}
