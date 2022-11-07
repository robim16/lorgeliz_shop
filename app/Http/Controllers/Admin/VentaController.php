<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pago;
use App\Producto;
use App\ProductoReferencia;
use App\ProductoVenta;
use App\Venta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class VentaController extends Controller
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
        $busqueda = $request->busqueda;
        
        $estado = $request->estado;

        // if (!$estado) {

        //    $ventas = Venta::join('clientes','ventas.cliente_id', '=','clientes.id')
        //    ->join('users','clientes.user_id', '=','users.id')
        //    ->select('ventas.id','ventas.fecha','ventas.valor','ventas.estado','users.nombres',
        //    'users.apellidos','clientes.id as cliente')
        //    ->orderBy('ventas.id', 'DESC')
        //    ->orWhere('ventas.valor', 'like',"%$busqueda%")
        //    ->orWhere('users.nombres', 'like',"%$busqueda%") //buscar ventas por valor a clientes
        //    ->paginate(5);

        // } else {
        //     $ventas = Venta::join('clientes','ventas.cliente_id', '=','clientes.id')
        //     ->join('users','clientes.user_id', '=','users.id')
        //     ->select('ventas.id','ventas.fecha','ventas.valor', 'ventas.estado','users.nombres',
        //     'users.apellidos','clientes.id as cliente')
        //     ->orderBy('ventas.id', 'DESC')
        //     ->orWhere('ventas.estado', $estado) //buscar ventas por estado
        //     ->paginate(5);
        // }

        try {

            
            $ventas = Venta::when($estado, function ($query) use ($estado) {
                return $query->orWhere('estado', $estado)
                ->with('cliente');
            },
            function ($query) use ($busqueda) {
                return $query->orWhere('valor', 'like',"%$busqueda%")
                ->orWhereHas('cliente.user', function (Builder $query)  use ($busqueda)  {
                    $query->where('nombres', 'like',"%$busqueda%");
                });
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
            
    
            return view('admin.ventas.index', compact('ventas'));
            
        } catch (\Exception $e) {
            //throw $th;
        }
    }



    public function show($id)
    {

        // $venta = Venta::join('clientes','ventas.cliente_id', '=','clientes.id')
        // ->join('facturas','ventas.factura_id', '=','facturas.id')
        // ->join('users','clientes.user_id', '=','users.id')
        // ->select('ventas.id','ventas.fecha','ventas.valor','ventas.saldo','ventas.estado',
        // 'users.nombres', 'users.apellidos','facturas.prefijo','facturas.consecutivo','clientes.id as cliente')
        // ->where('ventas.id', $id)
        // ->firstOrFail();

        try {


            $venta = Venta::with('cliente.user', 'factura')
                ->where('id', $id)
                ->firstOrFail();
    
            // $pagos = $venta->pagos()->select('*', DB::raw('SUM(monto) as total'))
            //     ->orderBy('pagos.fecha', 'DESC')
            //     ->paginate(5);

            $pagos = $venta->pagos()
                ->orderBy('pagos.fecha', 'DESC')
                ->paginate(5);
    
    
            $devoluciones = $venta->devoluciones()->paginate(5);
    
            $valor_devolucion = 0;
    
            foreach ($devoluciones as $devolucion) {
                $valor_devolucion += $devolucion->productoReferencia->colorProducto->producto->precio_actual;
            };
    
            $devolucion =  new DevolucionController();
    
            $estados = $devolucion->estados_devolucion();
    
            return view('admin.ventas.show', compact('venta', 'pagos', 'valor_devolucion',
                'devoluciones', 'estados'));
          
        } catch (\Exception  $e) {
            //throw $th;
        }

    }



    public function anular(Venta $venta)
    {

        try {
            
            $venta->estado = 3;
            $venta->save();
    
            $pagos = Pago::where('venta_id', $venta->id)->get();
    
            if (count($pagos) > 0) {
                foreach ($pagos as $pago) {
                    $pago->estado = 5; // se anula el pago
                    $pago->save();
                }
            }
    
            $venta->pedido()->update(['estado' => 5]);

            $productoVenta = ProductoVenta::where('venta_id', $venta->id)->get();
            foreach ($productoVenta as $key => $producto) {
               $prod = $producto->producto_referencia_id;
               $cantidad = $producto->cantidad; // se obtiene la cantidad del producto vendida
    
               $prof = ProductoReferencia::where('id', $prod)->first();
               $stock = $prof->stock;
    
               $prof->stock = $stock + $cantidad; // se restituye al stock la cantidad vendida
    
               $prof->save();
            }
    
            session()->flash('message', ['success', ("Se ha anulado la venta exitosamente")]);
    
            return back();

        } catch (\Exception $ex) {
            DB::rollBack();
        }
    }



    public function registrarPago(Request $request, Venta $venta)
    {

        try {
            
            $valor = $request->valor;

            $validator = Validator::make($request->all(), [
                'valor' => ['required', function ($attribute, $value, $fail) use ($request, $venta) {
                    if (!($request->valor <= $venta->saldo)) {
                       $fail('El valor del pago no puede ser mayor al saldo de la venta');
                    }
                }],
            ]);
    
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // $estado_devolucion = $venta->estado;
            
            if ($venta->saldo == $valor) {
                $venta->estado = 1;
            }
            else{
                $venta->estado = 2;
            }
    
            $venta->saldo = $venta->saldo - $valor;
            $venta->save();
    
            $total = $request->valor;
            $venta_id = $venta->id;
            $x_ref_payco = 0;
            $x_cod_response = 1;
    
            $payment =  new PaymentController();
            $payment->store($x_ref_payco, $total, $venta_id, $x_cod_response);// se envían las variables al método store de pagos
    

            $response = ['data' => 'success'];
            
            return response()->json($response);

            // session()->flash('message', ['success', ("Se ha registrado el pago exitosamente")]);
    
            // return back();

        } catch (\Exception $e) {
            return $e;
        }

    }



    public function listadoVentasPdf()
    {

        // $ventas = Venta::join('clientes','ventas.cliente_id', '=','clientes.id')
        // ->join('users','clientes.user_id', '=','users.id')
        // ->select('ventas.id','ventas.fecha','ventas.valor', 'ventas.estado','users.nombres',
        // 'clientes.id as cliente')
        // ->orderBy('ventas.id', 'DESC')
        // ->get();

        try {
           
            $ventas = Venta::with('cliente.user')
            ->orderBy('id', 'DESC')
            ->get();
    
            $count = 0;
            foreach ($ventas as $venta) {
                $count += 1;
            }
    
            $pdf = \PDF::loadView('admin.pdf.listadoventas',['ventas'=>$ventas, 'count'=>$count])
                ->setPaper('a4', 'landscape');
            
            return $pdf->download('listadoventas.pdf');

        } catch (\Exception $e) {
            //throw $th;
        }

    }



    public function facturaVentaAdmin(Request $request, $id)
    {

        // $productos = Producto::join('color_producto','productos.id', '=', 'color_producto.producto_id')
        // ->join('colores', 'color_producto.color_id', '=', 'colores.id') 
        // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
        // ->join('tallas','producto_referencia.talla_id', '=', 'tallas.id')
        // ->join('producto_venta','producto_referencia.id', '=', 'producto_venta.producto_referencia_id')
        // ->join('ventas','ventas.id', '=', 'producto_venta.venta_id')
        // ->select('productos.*', 'producto_venta.cantidad', 'colores.nombre as color', 'tallas.nombre as talla',
        // 'producto_referencia.id as referencia','color_producto.id as cop', 'color_producto.slug as slug', 'ventas.valor') 
        // ->where('ventas.id', '=', $id)
        // ->get();
        
        // $users = Venta::join('clientes','ventas.cliente_id', '=', 'clientes.id')
        // ->join('users','clientes.user_id', '=', 'users.id')
        // ->join('facturas', 'ventas.factura_id', '=', 'facturas.id')
        // ->select('users.nombres', 'users.identificacion','users.direccion','users.departamento',
        // 'users.municipio','users.telefono','users.email',
        // 'ventas.id as venta', 'ventas.fecha','ventas.saldo', 'facturas.prefijo', 'facturas.consecutivo')
        // ->where('ventas.id', '=', $id)
        // ->get();

        // $pdf = \PDF::loadView('admin.pdf.venta',['productos'=>$productos,'users'=>$users]);
        // return $pdf->download('factura-'.$users[0]->consecutivo.'.pdf');

        try {
           
            $productos = ProductoVenta::where('venta_id', $id)
            ->with('venta', 'productoReferencia')
            ->get();
    
            $pdf = \PDF::loadView('admin.pdf.venta',['productos'=>$productos]);
            return $pdf->download('factura-'.$productos[0]->venta->factura->consecutivo.'.pdf');


        } catch (\Exception $e) {
            //throw $th;
        }


    }

}
