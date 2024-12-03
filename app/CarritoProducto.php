<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CarritoProducto extends Pivot
{
    protected $table = 'carrito_producto';
    protected $fillable = [
        'producto_referencia_id', 
        'carrito_id',
        'cantidad',
    ];

    public $timestamps = false;

    public function carrito()
    {
        return $this->belongsTo(Carrito::class);
    }

    public function productoReferencia()
    {
        return $this->belongsTo(ProductoReferencia::class, 'producto_referencia_id');
    }
}
