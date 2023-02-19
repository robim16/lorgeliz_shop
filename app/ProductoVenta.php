<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoVenta extends Pivot
{
    protected $table = 'producto_venta';
    protected $fillable = [
        'producto_referencia_id', 
        'venta_id',
        'cantidad',
        'precio_venta',
        'porcentaje_descuento'
    ];

    public $timestamps = false;

    public function productoReferencia (){
        return $this->belongsTo(ProductoReferencia::class, 'producto_referencia_id');
    }

    public function venta (){
        return $this->belongsTo(Venta::class);
    }

}
