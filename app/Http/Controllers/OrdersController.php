<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\Pedido;
use App\Producto;
use App\ProductoReferencia;
use App\ProductoVenta;
use App\User;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
// use App\Mail\OrderStatusMail;
// use App\Notifications\NotificationClient;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Mail;



class OrdersController extends Controller
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

        $busqueda = $request->get('busqueda');

        //     $pedidos = Pedido::orWhere('pedidos.id','like',"%$busqueda%")
        //    //->orWhere('pedidos.fecha','like',"%$busqueda%")
        //     //orWhere('facturas.id','like',"%$busqueda%")
        //     ->orWhere('ventas.valor','like',"%$busqueda%")
        //     ->join('ventas','pedidos.venta_id', '=','ventas.id')
        //     ->join('facturas','ventas.factura_id', '=', 'facturas.id')
        //     ->select('ventas.valor','facturas.prefijo','facturas.consecutivo', 'pedidos.*')
        //     ->where('ventas.cliente_id', auth()->user()->cliente->id)
        //     ->where('ventas.estado', '!=', 3)
        //     ->orderBy('pedidos.created_at', 'DESC')
        //     ->paginate(5);
        
        try {
            
            $pedidos = Venta::with(['pedido', 'factura'])
                // ->orWhere('valor','like',"%$busqueda%")
                // ->where('estado', '!=', 3)
                ->when($busqueda, function($query) use($busqueda){
                    return $query->where('valor','like',"%$busqueda%")
                    ->orWhere('fecha','like',"%$busqueda%")
                    ->orWhere('id','like',"%$busqueda%");
                })
                ->estado()
                ->where('cliente_id', auth()->user()->cliente->id)
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
            
            return view('user.orders.index', compact('pedidos'));

        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $productos = Producto::join('color_producto','productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('imagenes', 'color_producto.id', '=', 'imagenes.imageable_id')
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->join('producto_venta','producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->join('ventas','ventas.id', '=', 'producto_venta.venta_id')
        // ->join('pedidos','ventas.id', '=', 'pedidos.venta_id')
        // ->select('productos.precio_actual', 'productos.nombre', 'producto_venta.cantidad',
        // 'ventas.valor', 'colores.nombre as color', 'tallas.nombre as talla', 'producto_referencia.id as referencia',
        // 'color_producto.id as cop', 'color_producto.slug as slug','pedidos.id as pedido', 'ventas.id as venta', 'imagenes.url as imagen') 
        // ->where('pedidos.id', '=', $id)
        // ->where('ventas.cliente_id', auth()->user()->cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('producto_referencia.id')
        // ->get();


        // $productos = ProductoVenta::whereHas('venta.pedido',
        //  function (Builder $query) use ($id) {
        //     $query->where('id', $id);
        // })
        // ->whereHas('venta',
        //  function (Builder $query) use ($id) {
        //     $query->where('cliente_id', auth()->user()->cliente->id);
        // })
        // ->with(['venta'])
        // ->get();

        // return view('user.orders.show',compact('productos'));

        return view('user.orders.show',compact('id'));

    }


    //envía la data al componente orderDetail. Implementado en api
    public function productos(Pedido $pedido) {

        // $productos = Producto::join('color_producto','productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('imagenes', 'color_producto.id', '=', 'imagenes.imageable_id')
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->join('producto_venta','producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->join('ventas','ventas.id', '=', 'producto_venta.venta_id')
        // ->join('pedidos','ventas.id', '=', 'pedidos.venta_id')
        // ->select('productos.precio_actual', 'productos.nombre', 'producto_venta.cantidad',
        // 'ventas.valor', 'colores.nombre as color', 'tallas.nombre as talla', 'producto_referencia.id as referencia',
        // 'color_producto.id as cop', 'color_producto.slug as slug','pedidos.id as pedido', 'ventas.id as venta', 'imagenes.url as imagen') 
        // ->where('pedidos.id', '=', $id)
        // ->where('ventas.cliente_id', auth()->user()->cliente->id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('producto_referencia.id')
        // ->get();

        try {

            $productos = ProductoVenta::whereHas('venta.pedido',
            function (Builder $query) use ($pedido) {
               $query->where('id', $pedido->id);
            })
            ->whereHas('venta',
            function (Builder $query) {
               $query->where('cliente_id', auth()->user()->cliente->id);
            })
            ->with(['productoReferencia.colorProducto.color', 'productoReferencia.colorProducto.producto',
            'productoReferencia.talla','venta.pedido', 'productoReferencia.colorProducto.imagenes',
                'productoReferencia.devoluciones'=>function($query) use($pedido){
                    $query->where('venta_id', $pedido->venta_id);
                }
            ])
            ->get();
    
            return ['productos' => $productos];
            
        } catch (\Exception $e) {
            //throw $th;
        }


    }



    public function facturas(Request $request, $id)
    {
        $productos = $this->productosOrder($id);

        // $users = Venta::join('clientes','ventas.cliente_id', '=', 'clientes.id')
        // ->join('pedidos','ventas.id', '=', 'pedidos.venta_id')
        // ->join('users','clientes.user_id', '=', 'users.id')
        // ->join('facturas', 'ventas.factura_id', '=', 'facturas.id')
        // ->select('users.nombres','users.apellidos','users.identificacion','users.departamento',
        // 'users.municipio','users.direccion','users.telefono','users.email', 'pedidos.id','ventas.fecha',
        // 'facturas.prefijo', 'facturas.consecutivo', 'ventas.id as venta')
        // ->where('pedidos.id', '=', $id)
        // ->get();

        // $users = Venta::with('cliente.user','pedido:id','factura')
        // ->whereHas('pedido', function (Builder $query) use ($id) {
        //    $query->where('id', $id);
        // })
        // ->get();

        // $pdf = \PDF::loadView('user.pdf.factura',['productos'=>$productos,'users'=>$users]);
        // return $pdf->download('factura-'.$users[0]->factura->consecutivo.'.pdf');

        $pdf = \PDF::loadView('user.pdf.factura',['productos'=>$productos]);
        return $pdf->download('factura-'.$productos[0]->venta->factura->consecutivo.'.pdf'); // imprimir factura de cliente

    }



    public function showPdf(Request $request, $id)
    {
        $productos = $this->productosOrder($id);
    
        // $users = $this->userPedido($id);

        // $pdf = \PDF::loadView('user.pdf.pedido',['productos'=>$productos, 'users'=>$users])
        // ->setPaper('a4', 'landscape');
        
        // return $pdf->download('pedido-'.$users[0]->pedido.'.pdf');

        $pdf = \PDF::loadView('user.pdf.pedido',['productos'=>$productos])
        ->setPaper('a4', 'landscape');
        
        return $pdf->download('pedido-'.$productos[0]->venta->pedido->id.'.pdf');
    }

    

    public function productosOrder($id) //esta función se reutiliza
    {
        // return Producto::join('color_producto','productos.id', '=', 'color_producto.producto_id')
        // ->join('imagenes', 'color_producto.id', '=', 'imagenes.imageable_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->join('producto_venta','producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->join('ventas','ventas.id', '=', 'producto_venta.venta_id')
        // ->join('pedidos','ventas.id', '=', 'pedidos.venta_id')
        // ->select('productos.id','productos.nombre', 'productos.precio_anterior', 'productos.precio_actual',
        // 'productos.porcentaje_descuento', 'producto_venta.cantidad', 'ventas.valor', 'colores.nombre as color',
        // 'tallas.nombre as talla', 'producto_referencia.id as referencia','color_producto.id as cop',
        // 'color_producto.slug as slug','pedidos.id as pedido', 'imagenes.url as imagen') 
        // ->where('pedidos.id', '=', $id)
        // ->where('imagenes.imageable_type', 'App\ColorProducto')
        // ->groupBy('producto_referencia.id')
        // ->get();

        try {
            
            return ProductoVenta::whereHas('venta.pedido',
            function (Builder $query) use ($id) {
               $query->where('id', $id);
            })
            ->with(['venta.pedido', 'venta.cliente.user', 'venta.factura'])
            ->get();
           
        } catch (\Exception $e) {
            //throw $th;
        }

    }

    // public function userPedido($id)
    // {
    //     return Venta::join('clientes','ventas.cliente_id', '=', 'clientes.id')
    //     ->join('pedidos','ventas.id', '=', 'pedidos.venta_id')
    //     ->join('users','clientes.user_id', '=', 'users.id')
    //     ->select('pedidos.id as pedido','pedidos.fecha','users.nombres','users.apellidos',
    //     'users.direccion','users.departamento','users.municipio','users.telefono','users.email',
    //     'users.identificacion','clientes.id as cliente')
    //     ->where('pedidos.id', '=', $id)
    //     ->get();
    // }

}
