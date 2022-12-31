<?php

namespace App;

use App\Mail\OrderStatusMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use OwenIt\Auditing\Contracts\Auditable;

class Pedido extends Model implements Auditable
{
    protected $fillable = ['fecha', 'direccion_entrega', 'estado', 'venta_id'];

    const PENDIENTE = 1;
    const PROCESADO = 2;
    const ENVIADO = 3;
    const ENTREGADO = 4;

    use \OwenIt\Auditing\Auditable;

    public static function boot () {
        parent::boot();
        
        static::updating(function(Pedido $pedido) {

            try {
                
                $details = [
                    'cliente' => $pedido->venta->cliente->user->nombres,
                    'fecha' => date('d/m/Y', strtotime($pedido->fecha)),
                    'estado' => $pedido->estado,
                    'url' => url('/pedidos/'. $pedido->id),
                ];
                
                Mail::to($pedido->venta->cliente->user->email)->send(new OrderStatusMail($details));

            } catch (\Exception $e) {
                Log::debug('Error enviando el email al editar el estado del pedido.
                    Error: '.json_encode($e));
            }

        });
    }

    public function venta (){
        return $this->belongsTo(Venta::class);
    }

    public function scopeBuscar($query, $tipo, $keyword) {
        if (($tipo) && ($keyword)) {
            return $query->where($tipo,'like',"%$keyword%");
        }
    }

    //protected $dateFormat = 'U'; establecer formato de almacenamiento de fechas para el modelo

}
