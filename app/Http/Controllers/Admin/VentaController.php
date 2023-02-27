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
use Illuminate\Support\Facades\Log;
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
            ->with(['pedido:id,venta_id', 'cliente:id,user_id', 'cliente.user:id,nombres,apellidos'])
            ->withCount('devoluciones')
            ->orderBy('id', 'DESC')
            ->paginate(5);
            
    
            return view('admin.ventas.index', compact('ventas'));
           
        } catch (\Exception $e) {
            Log::debug('Error consultando el index de ventas.Error: '.json_encode($e));
        }


    }



    public function show($id)
    {


        try {

            
            $venta = Venta::with('cliente.user', 'factura')
                ->where('id', $id)
                ->firstOrFail();

    

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


        } catch (\Exception $e) {
            Log::debug('Error consultando la venta.Error: '.json_encode($e));
        }


    }



    public function anular(Venta $venta)
    {

        try {
            
            DB::beginTransaction();

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
    
            DB::commit();

            session()->flash('message', ['success', ("Se ha anulado la venta exitosamente")]);
    
            return back();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug('Error anulando la venta.Error: '.json_encode($e));
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

            DB::beginTransaction();

            
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
    

            DB::commit();
            
            $response = ['data' => 'success'];
            
            return response()->json($response);

            // session()->flash('message', ['success', ("Se ha registrado el pago exitosamente")]);

            // return back();
           
        } catch (\Exception $e) {
            DB::rollBack();
            Log::debug('Error registrando el pago.Error: '.json_encode($e));
            return $e;

        }

    }


    public function listadoVentasPdf()
    {
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
            Log::debug('Error imprimiendo el listado de ventas.Error: '.json_encode($e));
        }

    }


    
    public function facturaVentaAdmin(Request $request, $id)
    {

        try {

            $productos = ProductoVenta::where('venta_id', $id)
            ->with('venta', 'productoReferencia')
            ->get();
    
            $pdf = \PDF::loadView('admin.pdf.venta',['productos'=>$productos]);
            return $pdf->download('factura-'.$productos[0]->venta->factura->consecutivo.'.pdf');
           
        } catch (\Exception $e) {
            Log::debug('Error imprimiendo la factura.Error: '.json_encode($e));
        }

    }
    

}
