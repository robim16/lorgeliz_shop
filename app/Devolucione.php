<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Devolucione extends Model implements Auditable
{
    const PENDIENTE = 1;
    const EN_PROCESO = 2;
    const RECHAZADA = 3;
    const COMPLETADA = 4;

    use \OwenIt\Auditing\Auditable;
    
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
