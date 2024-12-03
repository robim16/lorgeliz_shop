<?php

namespace App\Http\Controllers;


// use App\Color;
use App\ColorProducto;
// use App\Imagene;
use App\Producto;
// use App\ProductoReferencia;
// use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Events\VisitEvent;
use App\Helpers\Cart;

// use Illuminate\Support\Facades\Storage;
// use Intervention\Image\Facades\Image;
// use Spatie\Dropbox\Client;


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

            // return Cart::moveCartItemsIntoDb();
            
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
            //throw $th;
        }

    }
    

    //implementado en api/productController
    public function setVisitas(Request $request, $id)
    {

        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        $producto = ColorProducto::where('id', $id)->first();
        // $visitas = $producto->visitas;
        // $producto->visitas = $visitas + 1;
        $producto->visitas += 1;

        $producto->save(); // se incrementa el campo visitas

        $response = ['data' => 'success'];
            
        return response()->json($response);

        broadcast(new VisitEvent());

    }

}
