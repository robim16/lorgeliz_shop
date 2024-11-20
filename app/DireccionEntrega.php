<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DireccionEntrega extends Model
{
    protected $fillable = ['direccion', 'descripcion', 'activa', 'user_id'];
}
