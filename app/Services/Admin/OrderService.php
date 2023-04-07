<?php

namespace App\Services\Admin;

use App\Cliente;
use App\Jobs\SendOrderStatusMail;
use App\Notifications\NotificationClient;
use App\Pedido;
use Illuminate\Http\Request;

class OrderService 
{
    public function updateOrderStatus(Request $request)
    {
        
        $pedido = Pedido::where('id', $request->pedido_id)->firstOrFail();
        $pedido->estado = $request->estado;

        $pedido->save(); // se actualiza el estado

        $details = [
            'cliente' => $pedido->venta->cliente->user->nombres,
            'fecha' => date('d/m/Y', strtotime($pedido->fecha)),
            'estado' => $pedido->estado,
            'url' => url('/pedidos/'. $pedido->id),
        ];

        if ($pedido->estado == 2) {
           $mensaje = 'Tu pedido está siendo preparado';
        }
        if ($pedido->estado == 3) {
            $mensaje = 'Tu pedido está siendo enviado';
        }
        if ($pedido->estado == 4) {
            $mensaje = 'Tu pedido ha sido entregado';
        }

        
        $arrayData = [
            'notificacion' => [
                'msj' => $mensaje,
                'url' => url('/pedidos/'. $pedido->id)
            ]
        ];


        Cliente::findOrFail($pedido->venta->cliente->id)->notify(new NotificationClient($arrayData));

       
        SendOrderStatusMail::dispatch($details, $pedido->venta->cliente->user);

    }
}
