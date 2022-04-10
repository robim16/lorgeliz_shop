<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Devolucione extends Model
{
    const PENDIENTE = 1;
    const EN_PROCESO = 2;
    const RECHAZADA = 3;
    const COMPLETADA = 4;

    protected $fillable = ['fecha', 'cantidad', 'producto_referencia_id', 'venta_id', 'estado'];

    public function productoReferencia (){
        return $this->belongsTo(ProductoReferencia::class);
    }

    public function venta (){
        return $this->belongsTo(Venta::class);
    }

    // public static function activarDevolucion($venta,$producto){
    //     return Devolucione::where('venta_id',$venta)
    //     ->where('producto_referencia_id',$producto)
    //     ->first();
    // }
}
