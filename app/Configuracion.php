<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    protected $fillable = ['nombre','costo_envio','direccion','telefono', 'email'];

    protected $table = 'configuracion';

    public $timestamps = false;
}
