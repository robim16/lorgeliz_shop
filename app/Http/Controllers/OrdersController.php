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
use Illuminate\Support\Facades\Log;

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

    
        try {
        
            $pedidos = Venta::with(['pedido', 'factura'])
                ->orWhere('valor','like',"%$busqueda%")
                ->where('cliente_id', auth()->user()->cliente->id)
                // ->where('estado', '!=', 3)
                ->estado()
                ->orderBy('created_at', 'DESC')
                ->paginate(5);
                
            return view('user.orders.index', compact('pedidos'));
            
        } catch (\Exception $e) {
            Log::debug('Error consultando las ordenes del cliente.Error: '.json_encode($e));
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
    public function productos(Pedido $pedido) 
    {

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
        
        try {
            
            $productos = $this->productosOrder($id);
    
    
            $pdf = \PDF::loadView('user.pdf.factura',['productos'=>$productos]);
            return $pdf->download('factura-'.$productos[0]->venta->factura->consecutivo.'.pdf'); // imprimir factura de cliente
        
        } catch (\Exception $e) {
            Log::debug('Error imprimiendo la factura del cliente.Error: '.json_encode($e));
        }

    }



    public function showPdf(Request $request, $id)
    {
        
        // $users = $this->userPedido($id);
        
        // $pdf = \PDF::loadView('user.pdf.pedido',['productos'=>$productos, 'users'=>$users])
        // ->setPaper('a4', 'landscape');
        
        // return $pdf->download('pedido-'.$users[0]->pedido.'.pdf');

        try {
           
            $productos = $this->productosOrder($id);
    
            $pdf = \PDF::loadView('user.pdf.pedido',['productos'=>$productos])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('pedido-'.$productos[0]->venta->pedido->id.'.pdf');

        } catch (\Exception $e) {
            Log::debug('Error imprimiendo el detalle de la orden del cliente.Error: '.json_encode($e));
        }
    }



    public function productosOrder($id) //esta función se reutiliza
    {
        
        try {

            return ProductoVenta::whereHas('venta.pedido',
                function (Builder $query) use ($id) {
                $query->where('id', $id);
                })
                ->with(['venta.pedido', 'venta.cliente.user', 'venta.factura'])
                ->get();
            
        } catch (\Exception $e) {
            Log::debug('Error en la función productosOrder en ordenes del cliente.Error: '.json_encode($e));
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
