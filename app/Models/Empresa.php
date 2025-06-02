<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['nombre_empresa','rut_empresa','web_empresa','telefono_empresa'];

    public function subempresas()
    {
        return $this->hasMany(Subempresa::class);
    }
}
