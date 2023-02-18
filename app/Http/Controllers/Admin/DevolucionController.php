<?php

namespace App\Http\Controllers\Admin;

use App\Cliente;
Use App\Devolucione;
use App\Events\AddProductEvent;
Use App\ProductoVenta;
Use App\ProductoReferencia;
Use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\DevolucionStatusMail;
use App\Notifications\NotificationDevolution;

use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

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



    public function index()
    {
        //devoluciones en panel de admin
        
        try {
            
            $devoluciones = Devolucione::with('venta.cliente.user')
            ->orderBy('devoluciones.created_at','DESC')
            ->paginate(5);
    
            $estados = $this->estados_devolucion();

            return view('admin.devoluciones.index',compact('devoluciones', 'estados'));

        } catch (\Exception $e) {

            Log::debug('Error obteniendo el index de devoluciones en admin'.json_encode($e));
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
       
        try {
           
            $producto_devolucion = Devolucione::with(['venta', 'productoReferencia'])
                ->where('id', $id)
                ->paginate(5);
            
    
            return view('admin.devoluciones.show',compact('producto_devolucion'));
            
        } catch (\Exception $e) {
            Log::debug('Error mostrando la devolucion.Error :'.json_encode($e));
        }

    }



    public function pdfListarDevoluciones(Request $request)
    {
        
        try {
            
            $devoluciones = Devolucione::with('venta')
                ->get();
    


            $count = $devoluciones->count();

    
            $pdf = \PDF::loadView('admin.pdf.listado_devoluciones', ['devoluciones'=>$devoluciones, 
                'count'=>$count
            ])
            ->setPaper('a4', 'landscape');
            
            return $pdf->download('listado_devoluciones.pdf');

        } catch (\Exception $e) {
            
            Log::debug('Error imprimiendo el listado de devoluciones. Error: '.json_encode($e));
        }
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        try {
            

            DB::beginTransaction();

            $devolucion = Devolucione::where('id', $request->devolucion_id)->firstOrFail();
            $devolucion->estado = $request->estado;
    
            $devolucion->save();
    
            $details = [
                'cliente' => $devolucion->venta->cliente->user->nombres,
                'fecha' => date('d/m/Y', strtotime($devolucion->fecha)),
                'estado' => $devolucion->estado,
                'url' => url('/devoluciones/'. $devolucion->id),
            ];



            if ($request->estado == 4) { // comprobamos si la devolución ha sido efectuada completamente
    
    
                $producto = ProductoVenta::where('producto_referencia_id', $devolucion->producto_referencia_id)
                    ->where('venta_id', $devolucion->venta_id)
                    ->first(); //buscamos el producto
    
                // $producto_data = Producto::join('color_producto', 'productos.id', '=', 'color_producto.producto_id')
                // ->join('producto_referencia', 'color_producto.id', '=', 'producto_referencia.color_producto_id')
                // ->where('producto_referencia.id', $producto->producto_referencia_id)
                // ->select('productos.precio_actual', 'color_producto.id as color')
                // ->first(); // obtenemos el precio
    
                $producto_data = ProductoReferencia::where('id', $producto->producto_referencia_id)
                    ->first();
    
                // $precio = $producto_data->colorProducto->producto->precio_actual;
    
    
                $precio = $producto->precio_venta;
    
                $cantidad = $producto->cantidad; //cantidad del producto vendida
    
                $totalproducto = $precio * $cantidad; // calculamos subtotal
    
                $venta = Venta::where('id', $producto->venta_id)->first();
    
                $pagos = $venta->pagos()->selectRaw('SUM(monto) as total')->get();
    
    
                // if ($totalproducto == $venta->valor) {
    
                //     $venta->saldo = 0;
                    
                //     if ($pagos[0]->total > 0) {
    
                //         //anular pagos
                //     }
                // }
                // else{
    
                if ($pagos[0]->total == 0) {
                    
                    $venta->saldo = $venta->saldo - $totalproducto; //al saldo de la venta se resta el subtotal del producto 
                
                } else {
                    //sumatoria de pagos es > 0
                    
                    if ($totalproducto <= $venta->saldo) {
                        //si el subtotal del producto  es <= al saldo restante

                        $saldo = $venta->saldo - $totalproducto;

                        $venta->saldo = $saldo;

                        $deducciones = $pagos[0]->total + $totalproducto;//se suman los pagos totales y el subtotal del producto

                        if ($saldo == 0 && $deducciones > $venta->total) {
                            //anular pagos
                        }
                    }

                    else {//subtotal del producto > saldo de la venta
                        if ($venta->saldo > 0) {//si queda saldo aún
                            //190 150 40 170

                            //si total pagos > total producto
                            //saldo = (total venta - subtotal producto) - (total pagos - subtotal producto)
                        
                            //si no, saldo = saldo si se devuelve el dinero
                        }

                        else{
                            //anular todos los pagos si el subtotal del producto + 
                            //envío es igual al total de la venta

                        } 
                    }
                }
                
    
                $venta->save();
    
                // $prodreferencia = ProductoReferencia::where('id', $producto->producto_referencia_id)
                // ->first();
    
                // $stock = $prodreferencia->stock; // se obtiene el stock actual del producto
    
                // $prodreferencia->stock = $stock + $devolucion->cantidad; // al stock se suma la cantidad vendida
    
                // $prodreferencia->save();
    
                $stock = $producto_data->stock; // se obtiene el stock actual del producto
    
                $producto_data->stock = $stock + $devolucion->cantidad; // al stock se suma la cantidad vendida
    
                $producto_data->save();
    
                // $producto->delete(); // se borra de la venta el producto
    
                $product = array();
                
                $product['data'] = array();
    
                $product['data'] = $producto_data->colorProducto->id;

                broadcast(new AddProductEvent($product));
    
    
            }
            

            DB::commit();


            if ($devolucion->estado == 2) {
                $mensaje = 'La devolución está en estudio';
            }
    
            if ($devolucion->estado == 3) {
                $mensaje = 'La devolución fue rechazada';
            }
    
            if ($devolucion->estado == 4) {
                $mensaje = 'La devolución fue aprobada';
            }
    
                
            //notificacion para el cliente
            $arrayData = [
                'notificacion' => [
                    'msj' => $mensaje,
                    'url' => url('/devoluciones/'. $devolucion->id)
                ]
            ];


            Cliente::findOrFail($devolucion->venta->cliente->id)->notify(new NotificationDevolution($arrayData));
    
            //return new DevolucionStatusMail($details);
    
            Mail::to($devolucion->venta->cliente->user->email)->send(new DevolucionStatusMail($details));
    
            session()->flash('message', ['success', ("Se ha actualizado el estado de la solicitud")]);
    
            return back();


        } catch (\Exception $e) {

            DB::rollBack();

            Log::debug('Error editando la devolución. Error: '.json_encode($e));
        }

    }
    

    public function estados_devolucion()
    {
        return [
            1,
            2,
            3,
            4
        ];
    }

}
