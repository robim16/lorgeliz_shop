<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoVenta extends Pivot
{
    protected $table = 'producto_venta';
    protected $fillable = [
        'producto_id', 
        'venta_id',
        'cantidad',
    ];

    public $timestamps = false;

    public function productoReferencia (){
        return $this->belongsTo(ProductoReferencia::class, 'producto_referencia_id');
    }

    public function venta (){
        return $this->belongsTo(Venta::class);
    }

}
