<?php

namespace App\Http\Controllers;

use App\Cliente;
Use App\Devolucione;
Use App\Producto;
Use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\AdminDevolucionMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class DevolucionController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $busqueda = $request->busqueda;

        // $productos = Producto::
        // //orWhere('pedidos.id','like',"%$busqueda%")
        // join('color_producto','productos.id','color_producto.producto_id')
        // ->join('colores','color_producto.color_id','colores.id') 
        // ->join('imagenes','color_producto.id','imagenes.imageable_id')
        // ->join('producto_referencia','color_producto.id','producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id','tallas.id')
        // ->join('devoluciones','producto_referencia.id','devoluciones.producto_referencia_id')
        // ->join('ventas','devoluciones.venta_id','ventas.id')
        // ->join('pedidos','ventas.id','pedidos.venta_id')
        // ->select('productos.nombre','devoluciones.id', 'devoluciones.cantidad',
        // 'devoluciones.fecha', 'colores.nombre as color', 'tallas.nombre as talla',
        // 'pedidos.id as pedido','color_producto.id as cop', 'color_producto.slug as slug',
        // 'imagenes.url as imagen')
        // ->where('ventas.cliente_id', auth()->user()->cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('devoluciones.id')
        // ->orderBy('devoluciones.created_at','DESC')
        // ->paginate(5);

        try {
          
            $productos = Devolucione::whereHas('venta',
            function (Builder $query) {
               $query->where('cliente_id', auth()->user()->cliente->id);
            })
            ->with(['venta', 'productoReferencia'])
            ->paginate(5);
    
            return view('user.devoluciones.index',compact('productos'));

        } catch (\Exception $e) {
           Log::debug('Error obteniendo las devoluciones del usuario'.'Error:'.' '.json_encode($e));
        }
    }

    public function show(Request $request, $id)
    {
        $busqueda = $request->busqueda;

        // $productos = Producto::
        // //orWhere('pedidos.id','like',"%$busqueda%")
        // join('color_producto','productos.id','color_producto.producto_id')
        // ->join('colores','color_producto.color_id','colores.id') 
        // ->join('imagenes','color_producto.id','imagenes.imageable_id')
        // ->join('producto_referencia','color_producto.id','producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id','tallas.id')
        // ->join('devoluciones','producto_referencia.id','devoluciones.producto_referencia_id')
        // ->join('ventas','devoluciones.venta_id','ventas.id')
        // ->join('pedidos','ventas.id','pedidos.venta_id')
        // ->select('productos.nombre', 'devoluciones.id', 'devoluciones.cantidad', 'devoluciones.estado',
        // 'devoluciones.fecha', 'colores.nombre as color', 'tallas.nombre as talla', 'pedidos.id as pedido',
        // 'color_producto.id as cop', 'color_producto.slug as slug', 'imagenes.url as imagen')
        // ->where('devoluciones.id', $id)
        // ->where('ventas.cliente_id', auth()->user()->cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('devoluciones.id')
        // ->orderBy('devoluciones.created_at','DESC')
        // ->paginate(5);

        try {

            $productos = Devolucione::whereHas('venta',
                function (Builder $query) {
                $query->where('cliente_id', auth()->user()->cliente->id);
                })
                ->with(['venta', 'productoReferencia'])
                ->where('id', $id)
                ->paginate(5);
           
    
            return view('user.devoluciones.show',compact('productos'));
           
        } catch (\Exception $e) {

            Log::debug('Error mostrando devoluciones del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        
        //podría implementare en api
        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
           
            // $ref = $request->ref;
            $producto = $request->producto;
            $venta = $request->venta;
            $cantidad = $request->cantidad;
    
            $devoluciones = Devolucione::where('producto_referencia_id', $producto)//$ref
                ->where('venta_id', $venta)
                ->count(); // verificamos que no se haya solicitado la devolución anteriormente
    
            if ($devoluciones == 0) {
                $devolucion = new Devolucione();
                $devolucion->fecha = \Carbon\Carbon::now();
                $devolucion->cantidad = $cantidad;
                $devolucion->producto_referencia_id = $producto; //$ref
                $devolucion->venta_id = $venta;
    
                $devolucion->save();
    
                $admin = User::where('role_id', 2)->first();
                $user = auth()->user();
    
                // return $admin->nombres;
            
                $details = [
                    'title' => 'Se ha solicitado una nueva devolucion',
                    'user' => $admin->nombres,
                    'cliente' => $user->nombres.' '.$user->apellidos,
                    'url' => url('/admin/devoluciones/'. $devolucion->id),
                ];
    
                //return new AdminDevolucionMail($details);
                Mail::to($admin->email)->send(new AdminDevolucionMail($details));
    
                User::findOrFail($admin->id)->notify(new AdminDevolucionMail($details));
    
            } 
    
            $response = ['data' => $devoluciones];
            
            return response()->json($response);

        } catch (\Exception $e) {

            Log::debug('Errorguardando la devolución del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }
        
    }

    //implementado en rutas api/devolucion
    public function verificar(Request $request){

        // if (!$request->ajax()) return redirect('/');
        if ( ! request()->ajax()) {
			abort(401, 'Acceso denegado');
		}

        try {
            
            return Devolucione::where('venta_id',$request->venta)
            ->where('producto_referencia_id',$request->producto)
            ->first();

        } catch (\Exception $e) {

            Log::debug('Error consultando las devoluciones del usuario'.'Usuario:'.' '.
                json_encode(auth()->user()->id).' '.'Error:'.json_encode($e));
        }

    }
}
