<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'tipo_id'];

    public function tipos() {
        return $this->belongsToMany(Tipo::class);
    }

    //public function productoReferencias() {
        //return $this->hasMany(ProductoReferencia::class);
    //}

    public function colorProductos() {
        return $this->belongsToMany(ColorProducto::class);
    }

}
