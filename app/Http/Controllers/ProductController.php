<?php

namespace App\Http\Controllers;


use App\ColorProducto;
use App\Producto;
use Illuminate\Http\Request;
use App\Events\VisitEvent;
use Illuminate\Support\Facades\Log;




class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth')->except('setVisitas');
    }

    
    public function show($slug)
    {

        try {
            
            $producto = ColorProducto::with(['imagenes:imageable_id,url', 'color:id,nombre',
                'producto.tipo.subcategoria.categoria'])
                ->where('color_producto.slug',$slug)
                ->firstOrFail();
    
            $related_products = ColorProducto::with('imagenes:imageable_id,url')
                ->where('producto_id', $producto->producto_id)
                ->where('id', '!=', $producto->id)
                ->get();
            
            return view('tienda.product', compact('producto', 'related_products'));
            
        } catch (\Exception $e) {
            Log::debug('Error consultando la información del producto.Error: '.json_encode($e));
        }

    }


    //implementado en api/productController
    public function setVisitas(Request $request, $id)
    {

        if ( ! request()->ajax()) {
            abort(401, 'Acceso denegado');
        };

        try {

            $producto = ColorProducto::where('id', $id)->first();
            
            $producto->visitas += 1;
    
            $producto->save(); // se incrementa el campo visitas
    
            $response = ['data' => 'success'];
                
            return response()->json($response);
    
            broadcast(new VisitEvent());
            
        } catch (\Exception $e) {
           Log::debug('Error actualizando las visitas del producto'.'Error:'.' '.json_encode($e));
        }

    }

}
