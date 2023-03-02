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
use Illuminate\Support\Facades\Validator;

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


        $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
            $query->where('activo', 'Si');
        })
        ->with(['talla:id,nombre', 'colorProducto:id,color_id,producto_id,slug',
            'colorProducto.producto:id,nombre', 'colorProducto.color:id,nombre',
            'colorProducto.imagenes' => function($query) {
                $query->select('id', 'url', 'imageable_id');
            }
        ])
        ->where('stock', '>', '0')
        ->when($busqueda, function ($query) use ($busqueda) {
            return $query->whereHas('colorProducto.producto', function (Builder $query) use($busqueda){
                $query->where('nombre','like',"%$busqueda%");
            })
            ->orWhereHas('colorProducto.color', function (Builder $query) use($busqueda){
                $query->where('nombre','like',"%$busqueda%");
            });
        })
        ->orderBy('color_producto_id')
        ->paginate(5);


        
        return view('admin.stocks.index',compact('productos'));

    }



    public function store(Request $request)
    {
        

        $validator = Validator::make($request->all(), [
            'producto_id'   => 'required',
            'talla_id'      => 'required',
            'color_id'      => 'required',
            'cantidad'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
           
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
    
                if ($request->operacion == 1) {
                   
                    $referencia->stock = $referencia->stock + $request->cantidad; //sino, se actualiza el stock
                }
                else{
                    $referencia->stock = $referencia->stock - $request->cantidad;
                }
    
                $referencia->save();
            }
    
            session()->flash('message', ['success', ("Se ha actualizado el inventario exitosamente")]);
    
            $product = array();
            $product['data'] = array();
    
            $product['data'] = $colorproducto;
            
            // broadcast(new AddProductEvent($product));

    
            $response = ['data' => 'success'];
            
            return response()->json($response);


        } catch (\Exception $e) {
            session()->flash('message', ['warning', ("Ha ocurrido un error".$e)]);
        }

    }



    public function pdfInventarios()
    {
       

        $productos = ProductoReferencia::whereHas('colorProducto', function (Builder $query) {
            $query->where('activo', 'Si');
        })
        ->with(['talla', 'colorProducto'])
        ->where('stock', '>', '0')
        ->orderBy('color_producto_id')
        ->get();

        // $count = 0;
        // foreach ($productos as $producto) {
        //     $count = $count + 1;
        // }

        $count = $productos->count();

        $pdf = \PDF::loadView('admin.pdf.inventarios',['productos'=>$productos, 'count'=>$count])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('inventarioproductos.pdf');
        
    }
}
