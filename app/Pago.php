<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Pago extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['ref_epayco','fecha','monto','venta_id','estado'];
    //public $timestamps = false;

    public function venta(){
       return $this->belongsTo(Venta::class);
    }
}
