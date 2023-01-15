<?php

namespace App\Http\Controllers\Api;

use App\CarritoProducto;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function verify(Request $request)
    {
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}
        
        $productos = CarritoProducto::whereHas('carrito', function (Builder $query) {
           $query->where('estado', 1)
           ->where('cliente_id', auth()->user()->cliente->id);
        })
        ->with('productoReferencia')
        ->get();


        foreach ($productos as $producto) {
            if ($producto->cantidad > $producto->productoReferencia->stock) {
                $response = ['data' => 'error'];
                return response()->json($response); // si un producto está agotado o la cantidad supera el stock
            }
        }

        $response = ['data' => 'success'];
        return response()->json($response);// se ejecuta antes de levantar el formulario de epayco

    }
}
