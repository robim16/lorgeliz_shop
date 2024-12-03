<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $fillable = ['fecha', 'subtotal', 'envio', 'total', 'cliente_id', 'estado'];
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function productoReferencias()
    {
        return $this->belongsToMany(ProductoReferencia::class, 'carrito_producto');
        //->using('App\CarritoProducto');
    }

    public function carritoProductos()
    {
        return $this->hasMany(CarritoProducto::class);
    }

    public function scopeEstado($query)
    {
        return $query->where('estado', 1);
    }

    public function scopeCliente($query, $id)
    {
        return $query->where('cliente_id', $id);
    }

}
