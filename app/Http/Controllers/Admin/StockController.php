<?php

namespace App\Http\Controllers\Admin;

use App\Carrito;
use App\ColorProducto;
use App\Events\AddProductEvent;
use App\Producto;
use App\ProductoReferencia;
use App\Http\Controllers\Controller;
use App\Http\Requests\StockRequest;
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
    
    public function index(Request $request)
    {
        $busqueda = $request->get('busqueda');

        // $productos = Producto::orWhere('productos.nombre','like',"%$busqueda%")
        // //->orWhere('colores.nombre','like',"%$busqueda%")
        // //->orWhere('tallas.nombre','like',"%$busqueda%")
        // ->join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->where('producto_referencia.stock', '>', '0')
        // ->where('color_producto.activo', 'Si')
        // ->select('productos.*', 'producto_referencia.talla_id', 'color_producto.id as cop',
        // 'producto_referencia.stock', 'color_producto.slug as slug', 'colores.nombre as color',
        // 'tallas.nombre as talla', 'colores.id as color_id')
        // ->orderBy('productos.id')
        // ->paginate(5);

        $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
            $query->where('activo', 'Si');
        })
        ->with(['talla', 'colorProducto'])//faltan los filtros
        ->where('stock', '>', '0')
        ->orderBy('color_producto_id')
        ->paginate(5);


        return view('admin.stocks.index',compact('productos'));

    }

    public function store(StockRequest $request)
    {
        // $colorproducto = ColorProducto::where('color_id', $request->color_id)
        // ->where('producto_id', $request->producto_id)
        // ->first(); //obtener el color

        $colorproducto = ColorProducto::where('color_id', $request->color_id)
        ->where('producto_id', $request->producto_id)
        ->with('producto:id,slider_principal,estado')
        ->first();
        
        $referencia = ProductoReferencia::where('color_producto_id', $colorproducto->id)
        ->where('talla_id', $request->talla_id)
        ->first(); // buscar la referencia

       
        if ($referencia == '') {//si no existe la referencia, se crea

            $producto = new ProductoReferencia();
            $producto->color_producto_id = $colorproducto->id;
            $producto->talla_id = $request->talla_id;
            $producto->stock = $request->cantidad;
    
            $producto->save();  
        }
        else{

            $referencia->stock = $referencia->stock + $request->cantidad; //sino, se actualiza el stock

            $referencia->save();
        }

        session()->flash('message', ['success', ("Se ha actualizado el inventario exitosamente")]);

        $product = array();
        $product['data'] = array();

        $product['data'] = $colorproducto;
        broadcast(new AddProductEvent($product));

        return back();

    }

    public function pdfInventarios()
    {
        // $productos = Producto::join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->where('color_producto.activo', 'Si')
        // ->where('producto_referencia.stock', '>', '0')
        // ->select('productos.*', 'producto_referencia.talla_id', 'color_producto.id as cop',
        // 'producto_referencia.stock', 'color_producto.slug as slug', 'colores.nombre as color',
        // 'tallas.nombre as talla')
        // ->orderBy('productos.id')
        // ->get();

        $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
            $query->where('activo', 'Si');
        })
        ->with(['talla', 'colorProducto'])
        ->where('stock', '>', '0')
        ->orderBy('color_producto_id')
        ->get();

        $count = 0;
        foreach ($productos as $producto) {
            $count = $count + 1;
        }

        $pdf = \PDF::loadView('admin.pdf.inventarios',['productos'=>$productos, 'count'=>$count])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('inventarioproductos.pdf');
    }
}