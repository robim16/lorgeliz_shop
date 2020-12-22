<?php

namespace App\Http\Controllers;

use App\Carrito;
use App\ColorProducto;
use App\Producto;
use App\ProductoReferencia;
use App\Http\Requests\StockRequest;
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
    
    public function index(Request $request)
    {
        $nombre = $request->get('nombre');

        $productos = Producto::join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        ->where('productos.nombre','like',"%$nombre%")
        ->where('producto_referencia.stock', '>', '0')
        ->where('color_producto.activo', 'Si')
        ->select('productos.*', 'producto_referencia.talla_id', 'color_producto.id as cop', 'producto_referencia.stock', 'color_producto.slug as slug', 'colores.nombre as color', 'tallas.nombre as talla')
        ->orderBy('productos.id')->paginate(5);

        return view('admin.stocks.index',compact('productos'));

        
    }

    public function store(StockRequest $request)
    {
        $colorproducto = ColorProducto::where('color_id', $request->color_id)
        ->where('producto_id', $request->producto_id)
        ->first();

        $referencia = ProductoReferencia::where('color_producto_id', $colorproducto->id)
        ->where('talla_id', $request->talla_id)
        ->first();


        if ($referencia == '') {

            $producto = new ProductoReferencia();
            $producto->color_producto_id = $colorproducto->id;
            $producto->talla_id = $request->talla_id;
            $producto->stock = $request->cantidad;
    
            $producto->save();  
        }
        else{

            $referencia->stock = $referencia->stock + $request->cantidad;

            $referencia->save();
        }

        session()->flash('message', ['success', ("Se ha actualizado el inventario exitosamente")]);

        return back();

    }

    public function pdfInventarios()
    {
        $productos = Producto::join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        ->where('productos.activo', 'Si')
        ->where('producto_referencia.stock', '>', '0')
        ->select('productos.*', 'producto_referencia.talla_id', 'color_producto.id as cop', 'producto_referencia.stock', 'color_producto.slug as slug', 'colores.nombre as color', 'tallas.nombre as talla')
        ->orderBy('productos.id')->get();

        $count = 0;
        foreach ($productos as $producto) {
            $count = $count + 1;
        }

        $pdf = \PDF::loadView('admin.pdf.inventarios',['productos'=>$productos, 'count'=>$count])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('inventarioproductos.pdf');
    }

    public function verificarStock()
    {
        $carrito = Carrito::where('cliente_id', auth()->user()->cliente->id)
        ->where('carritos.estado', '1')
        ->first();
        
        $productos = ProductoReferencia::join('carrito_producto', 'producto_referencia.id', 'carrito_producto.producto_referencia_id')
        ->join('carritos', 'carrito_producto.carrito_id', 'carritos.id')
        ->where('carritos.id', $carrito->id)
        ->get();

        foreach ($productos as $producto) {
           if ($producto->cantidad > $producto->stock) {
                $response = ['data' => 'error'];
                return response()->json($response);
           }
        }

        $response = ['data' => 'success'];
        return response()->json($response);

    }
}
