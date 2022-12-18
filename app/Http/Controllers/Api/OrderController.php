<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Pedido;
use App\ProductoVenta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{

    public function productos(Request $request, Pedido $pedido) {


        try {
            
            $cliente = $request->user()->cliente->id; 
    
            $productos = ProductoVenta::whereHas('venta.pedido',
                function (Builder $query) use ($pedido) {
                    $query->where('id', $pedido->id);
                })
                ->whereHas('venta',function (Builder $query) use ($pedido, $cliente) {
                    $query->where('cliente_id', $cliente);
                })
                ->with(['productoReferencia.colorProducto.color',
                    'productoReferencia.colorProducto.producto',
                    'productoReferencia.talla','venta.pedido',
                    'productoReferencia.colorProducto.imagenes',
                    'productoReferencia.devoluciones'=> function($query) use($pedido){
                        $query->where('venta_id', $pedido->venta_id);
                    }
                ])
                ->get();
    
            return ['productos' => $productos];
            

        } catch (\Exception $e) {

           Log::debug('Error obteniendo los productos de las ordenes.Error: '.json_encode($e));
        }

    }

}
