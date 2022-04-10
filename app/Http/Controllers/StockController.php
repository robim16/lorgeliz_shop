<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\CarritoProducto;
use App\ProductoReferencia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
    {   //requiere auth
        if (!$request->ajax()) return redirect('/');
        // $carrito = Carrito::where('cliente_id', auth()->user()->cliente->id)
        // ->where('carritos.estado', '1')
        // ->first(); // se obtiene el carrito del cliente
        
        // $productos = ProductoReferencia::join('carrito_producto', 'producto_referencia.id',
        // 'carrito_producto.producto_referencia_id')
        // ->join('carritos', 'carrito_producto.carrito_id', 'carritos.id')
        // ->where('carritos.id', $carrito->id)
        // ->get();

        $productos = CarritoProducto::whereHas('carrito', function (Builder $query) {
           $query->where('estado', 1)
           ->where('cliente_id', auth()->user()->cliente->id);
        })
        ->with('productoReferencia')
        ->get();

        // foreach ($productos as $producto) {
        //    if ($producto->cantidad > $producto->stock) {
        //         $response = ['data' => 'error'];
        //         return response()->json($response); // si un producto está agotado o la cantidad supera el stock
        //    }
        // }

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
