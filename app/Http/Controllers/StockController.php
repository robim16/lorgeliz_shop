<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\CarritoProducto;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StockController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
    }

    public function verificarStock(Request $request)
    {   
        //requiere auth
     
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        

        try {
           

            $productos = CarritoProducto::whereHas('carrito', function (Builder $query) {
                $query->where('estado', 1)
               ->where('cliente_id', auth()->user()->cliente->id);
            })
            ->with('productoReferencia')
            ->get()
            ->first(function ($producto) {
                return $producto->cantidad > $producto->productoReferencia->stock;
            });// si un producto está agotado o la cantidad supera el stock


            if ($productos) {
                $response = ['data' => 'error'];
            }

            else {
                $response = ['data' => 'success'];
            }

            
            return response()->json($response);// se ejecuta antes de levantar el formulario de epayco
           
           
            // foreach ($productos as $producto) {
            //     if ($producto->cantidad > $producto->productoReferencia->stock) {
            //         $response = ['data' => 'error'];
            //         return response()->json($response); // si un producto está agotado o la cantidad supera el stock
            //     }
            // }
    
            // $response = ['data' => 'success'];
    
        } catch (\Exception $e) {
            Log::debug('Error verificando el stock'.'error:'.' '.json_encode($e));
        }

    }
}
